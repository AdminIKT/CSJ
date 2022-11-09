<?php

namespace App\Http\Controllers\User;

use Doctrine\ORM\EntityManagerInterface;
use App\Http\Controllers\BaseController,
    App\Entities\User,
    App\Entities\Action;

use Illuminate\Http\Request;

class ActionController extends BaseController
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
        $ppg     = $request->input('perPage', Config('app.per_page'));
        $actions = $this->em
                        ->getRepository(Action::class)
                        ->search(array_merge(
                            $request->all(),
                            ['user' => $user->getId()]
                        ), $ppg);

        return view('users.actions', [
            'perPage'    => $ppg,
            'entity'     => $user,
            'collection' => $actions,
        ]);
    }
}
