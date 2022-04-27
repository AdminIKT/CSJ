<?php

namespace App\Http\Controllers\Subaccount;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;

use App\Http\Controllers\BaseController,
    App\Events\AssignmentEvent,
    App\Entities\Assignment,
    App\Entities\Subaccount,
    App\Entities\Account;

class AssignmentController extends BaseController
{
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

        $entity = new Assignment;
        $entity->setSubaccount($subaccount)
               ->setCredit($values['credit'])
               ->setType($values['type'])
               ->setDetail($values['detail']);

        AssignmentEvent::dispatch($entity, __FUNCTION__);

        $this->em->persist($entity);
        $this->em->flush();
        $dst = $request->get('destination', route('accounts.assignments.index', ['account' => $subaccount->getAccount()->getId()]));

        return redirect()->to($dst)->with('success', 'Successfully created');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Assignment $assignment)
    {
        AssignmentEvent::dispatch($assignment, __FUNCTION__);
        $this->em->remove($assignment);
        $this->em->flush();
        return redirect()->back()->with('success', 'Successfully removed');
    }
}