<?php

namespace App\Http\Controllers\Order;

use Doctrine\ORM\EntityManagerInterface;
use App\Http\Controllers\BaseController,
    App\Entities\Order,
    App\Entities\Action\OrderAction as Action;

use Illuminate\Http\Request;

class ActionController extends BaseController
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
        $collection = $this->em->getRepository(Action::class)
                               ->findBy(['order' => $order->getId()], ['created' => 'DESC']);

        return view('orders.actions', [
            'entity'     => $order,
            'collection' => $collection,
        ]);
    }
}
