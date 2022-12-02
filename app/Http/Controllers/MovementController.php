<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\Arr;

use App\Entities\Movement,
    App\Entities\Account,
    App\Entities\Order,
    App\Entities\Area,
    App\Entities\Supplier,
    App\Entities\OrderCharge,
    App\Events\MovementEvent,
    App\Http\Requests\MovementRequest;

class MovementController extends BaseController
{
    /**
     * @inheritDoc
     */
    protected function authorization()
    {
        $this->authorizeResource(Movement::class, 'movement');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ppg   = $request->input('perPage', Config('app.per_page'));
        $class = $request->input('supplier') ?
            OrderCharge::class : 
            $request->input('movement', Movement::class);
        $coll  = $this->em->getRepository($class)
                      ->search($request->all(), $ppg);

        $accounts  = $this->em->getRepository(Account::class)->findBy([], ['name' => 'ASC']);
        $accounts  = array_combine(
            array_map(function($e) { return $e->getId(); }, $accounts),
            array_map(function($e) { return "{$e->getName()}-{$e->getType()}"; }, $accounts),
        );

        $suppliers = $this->em->getRepository(Supplier::class)
                         ->search(['invoices' => true], null)
                         ->items();

        $areas = $this->em->getRepository(Area::class)
                      ->findBy([], ['name' => 'ASC']);

        return view('movements.index', [
            'perPage'    => $ppg,
            'collection' => $coll,
            'accounts'   => $accounts,
            'areas'      => Arr::pluck($areas, 'name', 'id'),
            'suppliers'  => Arr::pluck($suppliers, 'name', 'id'),
        ]); 
    }
}
