<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use Kris\LaravelFormBuilder\FormBuilder;
use Illuminate\Support\Facades\Gate;

use App\Entities\Order,
    App\Entities\Movement,
    App\Entities\Assignment,
    App\Repositories\OrderRepository;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends BaseController
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function orders(Request $request)
    {
        $ppg    = $request->input('perPage', Config('app.per_page'));
        $orders = $this->em->getRepository(Order::class)
                           ->search($request->all(), $ppg);

        $report = PDF::loadView('reports.orders', [
            'collection' => $orders,
        ]); 

        $report->setPaper('a4', 'landscape');
        return $report->stream();
    }
}
