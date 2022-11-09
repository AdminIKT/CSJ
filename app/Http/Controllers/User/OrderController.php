<?php

namespace App\Http\Controllers\User;

use Doctrine\ORM\EntityManagerInterface;
use App\Http\Controllers\BaseController,
    App\Entities\User,
    App\Entities\Order;

use Illuminate\Http\Request;

class OrderController extends BaseController
{
    /**
     * @inheritDoc
     */
    protected function authorization()
    {
        $this->middleware('can:view,user')->only(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, User $user)
    {
        $ppg    = $request->input('perPage', Config('app.per_page'));
        $orders = $this->em
                       ->getRepository(Order::class)
                       ->search(array_merge(
                           $request->all(),
                           ['user' => $user->getId()]
                       ), $ppg);

        return view('users.orders', [
            'perPage'    => $ppg,
            'entity'     => $user,
            'collection' => $orders,
        ]);
    }
}
