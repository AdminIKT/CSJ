<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use Kris\LaravelFormBuilder\FormBuilder;
use Illuminate\Support\Facades\Gate;

use App\Entities\Supplier,
    App\Entities\Order,
    App\Entities\Movement,
    App\Entities\Assignment,
    App\Repositories\OrderRepository;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends BaseController
{
    /**
     * @inheritDoc
     */
    protected function authorization()
    {
        $this->middleware('can:viewAny,'.Order::class)->only(['orders']);
        $this->middleware('can:viewAny,'.Movement::class)->only(['movements']);
        $this->middleware('can:viewAny,'.Supplier::class)->only(['suppliers']);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function orders(Request $request)
    {
        $ppg    = $request->input('perPage');
        $orders = $this->em->getRepository(Order::class)
                           ->search($request->all(), $ppg);

        $report = PDF::loadView('reports.orders', [
            'collection' => $orders,
        ]); 

        $report->setPaper('a4', 'landscape');
        return $report->stream();
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function movements(Request $request)
    {
        $ppg       = $request->input('perPage');
        $class     = $request->input('movement', Movement::class);
        $movements = $this->em->getRepository($class)
                           ->search($request->all(), $ppg);

        $report = PDF::loadView('reports.movements', [
            'collection' => $movements,
        ]); 

        $report->setPaper('a4', 'landscape');
        return $report->stream();
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function suppliers(Request $request)
    {
        $ppg       = $request->input('perPage');
        $suppliers = $this->em->getRepository(Supplier::class)
                          ->search($request->all(), $ppg);

        $report = PDF::loadView('reports.suppliers', [
            'collection' => $suppliers,
        ]); 

        $report->setPaper('a4', 'landscape');
        return $report->stream();
    }
}
