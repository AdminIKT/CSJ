<?php

namespace App\Http\Controllers\Movement;

use App\Http\Controllers\BaseController;
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
    protected function authorization(){}

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
        if ($request->old() !== []) {
            //dd($request->old());
        }

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
    public function storeStep2(Request $request)
    {
        $data = $request->validate([
            'item.*.credit'  => 'required|min:1', 
            'item.*.invoice' => 'required', 
            'item.*.date'    => 'required', 
        ]);
        dd($request->all());
    }

    /**
     * Retrieve charges
     */
    protected function getCharges($rows = [])
    {
        $hzPattern  = "@^(".Charge::HZ_PREFIX."|".InvoiceCharge::HZ_PREFIX.")#.*@i";
        $collection = [];

        foreach ($rows as $row) {
            $hzSequence = $row['sequence'];
            if (preg_match($hzPattern, $hzSequence, $matches)) {
                $row['sequence'] = substr($matches[0], 2);
                switch (strtoupper($matches[1])) {
                    case Charge::HZ_PREFIX:
                        $collection[] = $this->getCharge($row);
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
               ->setInvoiceDate($row['date']);

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
     * @return InvoiceCharge
     */
    protected function getCharge(array $row)
    {
        $entity = new Charge;
        $entity->setCredit($row['credit'])
               ->setInvoice($row['invoice'])
               ->setInvoiceDate($row['date'])
               ->setType(Charge::TYPE_INVOICED_ACCOUNT);

        if (preg_match(
            Account::SEQUENCE_PATTERN, 
            $row['sequence'], 
            $matches)) 
        {
            dd($matches);
        }
        if (!isset($account)) {
            $entity->setDetail($row['sequence']);
        }
        return $entity;
    }
}
