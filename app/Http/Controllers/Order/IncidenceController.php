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
     * @inheritDoc
     */
    protected function authorization()
    {
        $this->middleware('can:view,order')->only(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Order $order)
    {
        $ppg       = $request->input('perPage', Config('app.per_page'));
        $collection = $this->em->getRepository(Incidence::class)
                               ->findBy(
                                ['order' => $order->getId()], 
                                ['created' => 'DESC']);

        return view('orders.incidences', [
            'perPage'    => $ppg,
            'entity'     => $order,
            'collection' => $collection,
        ]);
    }
}
