<?php

namespace App\Http\Controllers\InvoiceCharge;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use Rap2hpoutre\FastExcel\Facades\FastExcel;
use App\Entities\Order,
    App\Entities\InvoiceCharge;

class ImportController extends BaseController
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('invoiceCharges.imports.form', [
        ]); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $data = $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);
        $path = $request->file->path();
        $rows = FastExcel::import($path);
        $collection = [];
        foreach ($rows as $row) {
            $entity = new InvoiceCharge;
            $entity->setCredit($row['credit'])
                   ->setInvoice($row['invoice']);

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
                $entity->setDetail(str_replace($matches[0], "", $row['sequence']));
            }
            if (!$order) {
                $entity->setDetail($row['sequence']);
            }
            $collection[] = $entity;
        }
        return view('invoiceCharges.imports.list', [
            'collection' => $collection,
        ]); 
    }

    public function store(Request $request)
    {
        dd($request->all());
    } 

    protected function authorization()
    {}
}
