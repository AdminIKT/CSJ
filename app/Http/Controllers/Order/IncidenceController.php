<?php

namespace App\Http\Controllers\Order;

use Doctrine\ORM\EntityManagerInterface;
use App\Http\Controllers\BaseController,
    App\Entities\Order;
use Illuminate\Http\Request;

class IncidenceController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Order $order)
    {
        return view('orders.incidences', [
            'entity' => $order,
        ]);
    }
}
