<?php

namespace App\Http\Controllers\Order;

use Doctrine\ORM\EntityManagerInterface;
use App\Http\Controllers\Controller,
    App\Entities\Order;
use Illuminate\Http\Request;

class IncidenceController extends Controller
{
    /**
     * @EntityManagerInterface
     */ 
    protected $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

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
