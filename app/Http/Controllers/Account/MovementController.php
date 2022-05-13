<?php

namespace App\Http\Controllers\Account;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController,
    App\Entities\Movement,
    App\Entities\Account;

class MovementController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Account $account)
    {
        $ppg   = $request->input('perPage', Config('app.per_page'));
        $class = $request->input('movement', Movement::class);
        $collection = $this->em->getRepository($class)
                           ->search(array_merge(
                                $request->all(), 
                                ['account' => $account->getId()]
                            ), $ppg);

        return view('accounts.movements', [
            'perPage'    => $ppg,
            'entity'     => $account,
            'collection' => $collection,
        ]);
    }
}
