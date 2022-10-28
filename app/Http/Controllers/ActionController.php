<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;

use App\Entities\Action;

class ActionController extends BaseController
{
    /**
     * @inheritDoc
     */
    protected function authorization()
    {
        //TODO
        //$this->authorizeResource(Action::class, 'action');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ppg    = $request->input('perPage', Config('app.per_page'));

        $actions = $this->em
                        ->getRepository(Action::class)
                        ->search($request->all(), $ppg);

        return view('actions.index', [
            'perPage'    => $ppg,
            'collection' => $actions,
        ]); 
    }
}
