<?php

namespace App\Http\Controllers\Supplier;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController,
    App\Entities\Account,
    App\Entities\InvoiceCharge,
    App\Entities\Supplier;

class InvoiceChargeController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Supplier $supplier)
    {
        $collection = $this->em->getRepository(InvoiceCharge::class)->search(
            $request->input('sequence'),
            $request->input('from'),
            $request->input('to'),
            $request->input('account'),
            $supplier->getId(),
            $request->input('otype'),
            $request->input('mtype'),
            $request->input('sortBy', 'created'),
            $request->input('sort', 'desc')
        );

        $accounts  = $this->em->getRepository(Account::class)->findBy([], ['name' => 'ASC']);
        $accounts  = array_combine(
            array_map(function($e) { return $e->getId(); }, $accounts),
            array_map(function($e) { return "{$e->getName()}-{$e->getType()}"; }, $accounts),
        );

        return view('suppliers.invoiceCharges', [
            'entity'     => $supplier,
            'accounts'      => $accounts,
            'collection' => $collection,
        ]);
    }
}
