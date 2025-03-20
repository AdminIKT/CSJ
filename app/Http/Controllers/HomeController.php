<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entities\Account,
    App\Entities\Order,
    App\Entities\Action,
    App\Entities\Action\OrderAction,
    App\Entities\Supplier\Incidence,
    App\Entities\Assignment,
    App\Entities\Charge;

class HomeController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function home(Request $request)
    {
        return view('home', []); 
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function admin(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied.');
        }
        $em = app('em');

        return view('admin', [
            'accounts' => $em->getRepository(Account::class)->findAll(),
            //'orders' => $em->getRepository(OrderAction::class)->search(['type' => OrderAction::TYPE_STATUS, 'action' => Order::STATUS_CREATED], 5),
            'orders' => $em->getRepository(Order::class)->search([], 5),
            'incidences' => $em->getRepository(Incidence::class)->findBy([], ['created' => 'DESC'], 5),
            'assignments'=> $em->getRepository(Assignment::class)->search([], 5),
            'charges'  => $em->getRepository(Charge::class)->search([], 5),
            'totals'   => $em->getRepository(Account::class)->totals(),
            'actions'  => $em->getRepository(Action::class)->search([], 5),
        ]); 
    }
}
