<?php

namespace App\Http\Controllers\Supplier;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController,
    App\Entities\Account,
    App\Entities\Order,
    App\Entities\Supplier;

class OrderController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Supplier $supplier)
    {
        $collection = $this->em->getRepository(Order::class)->search(
            $request->input('sequence'),
            $request->input('from'),
            $request->input('to'),
            $request->input('account'),
            $supplier->getId(),
            $request->input('type'),
            $request->input('status'),
            $request->input('sortBy', 'date'),
            $request->input('sort', 'desc')
        );
        $accounts  = $this->em->getRepository(Account::class)->findBy([], ['name' => 'ASC']);
        $accounts  = array_combine(
            array_map(function($e) { return $e->getId(); }, $accounts),
            array_map(function($e) { return "{$e->getName()}-{$e->getType()}"; }, $accounts),
        );

        return view('suppliers.orders', [
            'accounts'      => $accounts,
            'entity'     => $supplier,
            'collection' => $collection,
        ]);
    }
}
