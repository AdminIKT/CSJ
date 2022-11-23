<?php

namespace App\Http\Controllers\Movement;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use Rap2hpoutre\FastExcel\Facades\FastExcel;
use App\Entities\Order,
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
    public function createStep1()
    {
        return view('movements.import.step1', [
        ]); 
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function storeStep1(Request $request)
    {
        $data = $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);
        if (empty($request->session()->get('rows'))) {
            $path = $request->file->path();
            $rows = FastExcel::import($path);
            $request->session()->put('rows', $rows);
        }
        else {
            $rows = $request->session()->get('rows');
            $request->session()->put('rows', $rows);
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

        $rows = $request->session()->get('rows');

        return view('movements.import.step2', [
            'collection' => $this->getRowCharges($rows),
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

    protected function getRowCharges($rows = [])
    {
        $collection = [];
        foreach ($rows as $row) {
            $entity = new InvoiceCharge;
            $entity->setCredit($row['credit'])
                   ->setInvoice($row['invoice'])
                   ->setInvoiceDate($row['date']);

            $order = null;
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
            if (!$order) {
                $entity->setDetail($row['sequence']);
            }
            $collection[] = $entity;
        }
        return $collection;
    }
}
