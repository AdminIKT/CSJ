<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;

use App\Events\AssignmentEvent,
    App\Entities\Assignment,
    App\Entities\Account;

class AssignmentController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $years = $this->em->getRepository(Assignment::class)->years();
        $years = array_combine($years, $years);

        $accounts = $this->em->getRepository(Account::class)->findBy([], ['name' => 'ASC']);
        $accounts = array_combine(
            array_map(function($e) { return $e->getId(); }, $accounts),
            array_map(function($e) { return "{$e->getName()}-{$e->getType()}"; }, $accounts),
        );

        $collection = $this->em->getRepository(Assignment::class)->search(
            $request->input('year'),
            $request->input('account'),
            $request->input('type'),
            $request->input('operator'),
            $request->input('credit'),
            $request->input('sortBy', 'created'),
            $request->input('sort', 'desc')
        );

        return view('assignments.index', [
            'collection' => $collection,
            'years'      => $years,
            'accounts'      => $accounts,
        ]); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $entity = new Assignment;
        if (null !== ($id = $request->input('account')) && 
            null !== ($account = $this->em->find(Account::class, $id))) { 
            $entity->setAccount($account);
            $accounts = [$account->getId() => "{$account->getName()}-{$account->getType()}"];
        }
        else {
            $accounts  = $this->em->getRepository(Account::class)->findBy([], ['name' => 'ASC']);
            $accounts  = array_combine(
                array_map(function($e) { return $e->getId(); }, $accounts),
                array_map(function($e) { return "{$e->getName()}-{$e->getType()}"; }, $accounts),
            );
        }

        return view('assignments.form', [
            'dst'    => $request->input('destination'),
            'entity' => $entity,
            'accounts'  => $accounts,
        ]); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $values = $request->validate([
            'account'   => ['required'],
            'type'   => ['required'],
            'credit' => ['required'],
            'detail' => ['max:255'],
        ]);

        $account = $this->em->find(Account::class, $values['account']); 
        $entity = new Assignment;
        $entity->setAccount($account)
               ->setCredit($values['credit'])
               ->setType($values['type'])
               ->setDetail($values['detail']);

        AssignmentEvent::dispatch($entity, __FUNCTION__);

        $this->em->persist($entity);
        $this->em->flush();
        $dst = $request->get('destination', route('assignments.index'));
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
