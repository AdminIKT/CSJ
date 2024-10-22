<?php

namespace App\Http\Controllers\Subaccount;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Laminas\Hydrator\DoctrineObject;

use App\Http\Controllers\BaseController,
    App\Events\MovementEvent,
    App\Entities\Assignment,
    App\Entities\Subaccount,
    App\Entities\Account;

class AssignmentController extends BaseController
{
    /**
     * @inheritDoc
     */
    protected function authorization()
    {
        $this->middleware('can:update,subaccount')->only([
            'create', 
            'store',
            'destroy'
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Subaccount $subaccount, Request $request)
    {
        $entity = new Assignment;
        $entity->setSubaccount($subaccount);

        return view('subaccounts.assignments.form', [
            'dst'    => $request->input('destination'),
            'entity' => $entity,
        ]); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Subaccount $subaccount, Request $request)
    {
        $values = $request->validate([
            'type'   => ['required'],
            'credit' => ['required'],
            'detail' => ['max:255'],
        ]);

        $em = app('em');
        $hydrator = new DoctrineObject($em);

        $entity = new Assignment;
        $entity->setSubaccount($subaccount);
        $hydrator->hydrate($values, $entity);

        MovementEvent::dispatch($entity, __FUNCTION__);

        $em->persist($entity);
        $em->flush();

        $dst = $request->get('destination', route('accounts.movements.index', ['account' => $subaccount->getAccount()->getId()]));

        return redirect()->to($dst)->with('success', __('Successfully created'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Assignment $assignment)
    {
        MovementEvent::dispatch($assignment, __FUNCTION__);
        $this->em->remove($assignment);
        $this->em->flush();
        return redirect()->back()->with('success', __('Successfully removed'));
    }
}
