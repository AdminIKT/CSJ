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
        $class = $request->input('movement', Movement::class);
        $collection = $this->em->getRepository($class)->search(
            //$request->input('sequence'),
            //$request->input('from'),
            //$request->input('to'),
            //$account->getId(),
            //$request->input('supplier'),
            //$request->input('otype'),
            //$request->input('mtype'),
            //$request->input('sortBy', 'created'),
            //$request->input('sort', 'desc')
        );

        return view('accounts.movements', [
            'entity' => $account,
            'collection' => $collection,
        ]);
    }
}
