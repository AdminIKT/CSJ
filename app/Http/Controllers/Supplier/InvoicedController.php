<?php

namespace App\Http\Controllers\Supplier;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController,
    App\Entities\Supplier;

class InvoicedController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Supplier $supplier)
    {
        return view('suppliers.invoiceds', [
            'entity' => $supplier,
        ]);
    }
}
