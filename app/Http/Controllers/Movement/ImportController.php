<?php

namespace App\Http\Controllers\Movement;

use App\Http\Controllers\BaseController,
    App\Http\Requests\Movement\ImportChargeRequest;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use Rap2hpoutre\FastExcel\Facades\FastExcel;
use App\Entities\Account,
    App\Entities\Order,
    App\Entities\Charge,
    App\Entities\InvoiceCharge;

class ImportController extends BaseController
{
    /**
     * @inheritDoc
     * TODO
     */
    protected function authorization()
    {
        $this->middleware('can:viewAny,'.Movement::class);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function createStep1(Request $request)
    {
        $request->session()->forget('file-rows');

        return view('movements.import.step1'); 
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function storeStep1(Request $request)
    {
        $data = $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);
        if (empty($request->session()->get('file-rows'))) {
            $path = $request->file->path();
            $rows = FastExcel::import($path);
            $request->session()->put('file-rows', $rows);
        }

        return redirect()->route('imports.create.step2');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function createStep2(Request $request)
    {
        if (null === ($rows = $request->session()->get('file-rows'))) {
            return redirect()->route('imports.create.step1');
        }

        return view('movements.import.step2', [
            'collection' => $this->getCharges($rows),
        ]); 
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function storeStep2(ImportChargeRequest $request)
    {
        $values  = $request->all();
        $charges = [];
        foreach ($values['item'] as $item) {
            switch ($item['charge']) {
                case InvoiceCharge::HZ_PREFIX:
                    $charge = InvoiceCharge::fromArray($item);
                    break;
                case Charge::HZ_PREFIX:
                    $charge = Charge::fromArray($item);
                    break;
            }    
            $charges[] = $charge;
        }
        dd($charges);
    }

    /**
     * Retrieve charges
     */
    protected function getCharges($rows = [])
    {
        $hzPattern  = InvoiceCharge::HZ_PATTERN;
        $collection = [];

        foreach ($rows as $row) {
            $hzSequence = $row['sequence'];
            if (preg_match($hzPattern, $hzSequence, $matches)) {
                $row['sequence'] = substr($matches[0], 2);
                switch (strtoupper($matches[1])) {
                    case Charge::HZ_PREFIX:
                        $collection[] = $this->getAccountCharge($row);
                        break;
                    case InvoiceCharge::HZ_PREFIX:
                        $collection[] = $this->getInvoiceCharge($row);
                        break;
                }
            } 
            else {
                continue;
            }
        }
        //dd($collection);
        return $collection;
    }

    /**
     * @param array $row
     * @return InvoiceCharge
     */
    protected function getInvoiceCharge(array $row)
    {
        $entity = new InvoiceCharge;
        $entity->setCredit($row['credit'])
               ->setInvoice($row['invoice'])
               ->setInvoiceDate($row['date'])
               ->setHzCode("{$row['year']}-{$row['entry']}")
               ;

        if (preg_match(
            Order::SEQUENCE_PATTERN, 
            $row['sequence'], 
            $matches)) 
        {
            $order = $this->em->getRepository(Order::class)
                          ->findOneBy(['sequence' => $matches[0]]);
            if ($order) {
                $entity->setOrder($order);
            }
            $entity->setDetail(trim(str_replace($matches[0], "", $row['sequence'])));
        }
        if (!isset($order)) {
            $entity->setDetail($row['sequence']);
        }

        return $entity;
    }

    /**
     * @param array $row
     * @return Charge
     */
    protected function getAccountCharge(array $row)
    {
        $hzCode = "{$row['year']}-{$row['entry']}";
        $charge = $this->em
                       ->getRepository(Charge::class)
                       ->findOneBy([
                            'hzCode' => $hzCode,
                            'type'   => Charge::TYPE_INVOICED_ACCOUNT,
                        ]);

        if ($charge) {
            return $charge;
        }

        $charge = new Charge;
        $charge->setCredit($row['credit'])
               ->setInvoice($row['invoice'])
               ->setInvoiceDate($row['date'])
               ->setType(Charge::TYPE_INVOICED_ACCOUNT)
               ->setHzCode($hzCode)
               ;

        if (preg_match(
            Account::SEQUENCE_PATTERN, 
            $row['sequence'], 
            $matches)) 
        {
            $criteria = [
                'acronym' => $matches[1],
                'type'    => $matches[2],
            ];
            if ($matches[3]) $criteria['lcode'] = $matches[3];
            $account = $this->em
                            ->getRepository(Account::class)
                            ->findOneBy($criteria);

            //fixme: which subaccount
            if ($account) {
                $subaccount = $account->getSubaccounts()->first();
                $charge->setSubaccount($subaccount);
            }
            $charge->setDetail(trim(str_replace($matches[0], "", $row['sequence'])));
        }
        if (!isset($subaccount)) {
            $charge->setDetail($row['sequence']);
        }
        return $charge;
    }
}
