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
        $ppg     = $request->input('perPage', Config('app.per_page'));
        $actions = $this->em
                        ->getRepository(Action::class)
                        ->search(array_merge(
                            $request->all(),
                            ['entity' => $order->getId()]
                        ), $ppg);

        return view('orders.actions', [
            'perPage'    => $ppg,
            'entity'     => $order,
            'collection' => $actions,
        ]);
    }
}
