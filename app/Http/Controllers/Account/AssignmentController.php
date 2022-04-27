<?php

namespace App\Http\Controllers\Account;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController,
    App\Entities\Assignment,
    App\Entities\Account;

class AssignmentController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Account $account)
    {
        $years = $this->em->getRepository(Assignment::class)->years($account);
        $years = array_combine($years, $years);
        $collection = $this->em->getRepository(Assignment::class)->search(
            $request->input('year'),
            $account->getId(),
            $request->input('type'),
            $request->input('operator'),
            $request->input('credit'),
            $request->input('sortBy', 'created'),
            $request->input('sort', 'desc')
        );

        return view('accounts.assignments', [
            'years'  => $years,
            'entity' => $account,
            'collection' => $collection,
        ]);
    }
}
