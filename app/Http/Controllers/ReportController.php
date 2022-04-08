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
        $collection = $this->em->getRepository(Order::class)->search(
            $request->input('sequence'),
            $request->input('from'),
            $request->input('to'),
            $request->input('area'),
            $request->input('supplier'),
            $request->input('type'),
            $request->input('status'),
            $request->input('sortBy', 'date'),
            $request->input('sort', 'desc'),
            $request->input('perPage', OrderRepository::PER_PAGE)
        );

        $report = PDF::loadView('reports.orders', [
            'collection' => $collection,
            'perPage'    => $request->input('perPage'),
        ]); 

        $report->setPaper('a4', 'landscape');
        return $report->stream();
    }

    public function movements(Request $request)
    {
        $collection = $this->em->getRepository(Movement::class)->search(
            $request->input('sequence'),
            $request->input('from'),
            $request->input('to'),
            $request->input('area'),
            $request->input('supplier'),
            $request->input('otype'),
            $request->input('mtype'),
            $request->input('sortBy', 'created'),
            $request->input('sort', 'desc'),
            null
        );

        $report = PDF::loadView('reports.movements', [
            'collection' => $collection,
        ]); 

        $report->setPaper('a4', 'landscape');
        return $report->stream();
    }

    public function assignments(Request $request)
    {
        $collection = $this->em->getRepository(Assignment::class)->search(
            $request->input('year'),
            $request->input('area'),
            $request->input('type'),
            $request->input('operator'),
            $request->input('credit'),
            $request->input('sortBy', 'created'),
            $request->input('sort', 'desc'),
            null
        );

        $report = PDF::loadView('reports.assignments', [
            'collection' => $collection,
        ]); 

        $report->setPaper('a4', 'landscape');
        return $report->stream();
    }
}
