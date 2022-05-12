<?php

namespace App\Http\Controllers\Order;

use Doctrine\ORM\EntityManagerInterface;
use App\Http\Controllers\BaseController,
    App\Entities\Order,
    App\Entities\Supplier\Incidence;

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
        $collection = $this->em->getRepository(Incidence::class)
                               ->findBy(['order' => $order->getId()], ['created' => 'DESC']);

        return view('orders.incidences', [
            'entity'     => $order,
            'collection' => $collection,
        ]);
    }
}
