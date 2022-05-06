<?php

namespace App\Http\Controllers\Supplier;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Http\Controllers\BaseController,
    App\Entities\Area,
    App\Entities\Account,
    App\Entities\InvoiceCharge,
    App\Entities\Supplier;

class MovementController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Supplier $supplier)
    {
        $ppg = $request->input('perPage', Config('app.per_page'));
        $collection = $this->em->getRepository(InvoiceCharge::class)
                           ->search(array_merge(
                                $request->all(), 
                                ['supplier' => $supplier->getId()]
                           ));

        $accounts  = $this->em->getRepository(Account::class)->findBy([], ['name' => 'ASC']);
        $accounts  = array_combine(
            array_map(function($e) { return $e->getId(); }, $accounts),
            array_map(function($e) { return "{$e->getName()}-{$e->getType()}"; }, $accounts),
        );

        $areas = $this->em->getRepository(Area::class)
                      ->findBy([], ['name' => 'ASC']);

        return view('suppliers.movements', [
            'perPage'    => $ppg,
            'entity'     => $supplier,
            'accounts'   => $accounts,
            'collection' => $collection,
            'areas'      => Arr::pluck($areas, 'name', 'id'),
        ]);
    }
}
