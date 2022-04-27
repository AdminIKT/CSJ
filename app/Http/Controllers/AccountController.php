<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;

use App\Entities\Account,
    App\Entities\User,
    App\Entities\Order,
    App\Entities\Subaccount,
    App\Entities\Area,
    App\Http\Requests\AccountRequest;

class AccountController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $accounts = $this->em->getRepository(Account::class)->search(
            $request->input('name'),
            $request->input('type'),
            $request->input('creditOp'),
            $request->input('credit'),
            $request->input('compromisedOp'),
            $request->input('compromised'),
            $request->input('sortBy', 'name'),
            $request->input('sort', 'desc')
        );

        return view('accounts.index', [
            'accounts' => $accounts,
        ]); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $areas = $this->em->getRepository(Area::class)
                               ->findBy([], ['name' => 'asc']);
        $users = $this->em->getRepository(User::class)
                                ->findBy([], ['email' => 'asc']);

        return view('accounts.form', [
            'route' => route('accounts.store'),
            'method' => 'POST',
            'entity' => new Account,
            'users' => $users,
            'areas' => $areas,
        ]); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccountRequest $request)
    {
        //$data = $request->validated();
        $entity = new Account;
        $this->hydrateData($entity, $request->all());
        $this->em->persist($entity);
        $this->em->flush();
        return redirect()->route('accounts.show', ['account' => $entity->getId()])
                         ->with('success', 'Successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Account $account)
    {
        $orders = $this->em->getRepository(Order::class)->search(
            $request->input('sequence'),
            $request->input('from'),
            $request->input('to'),
            $account->getId(),
            $request->input('supplier'),
            $request->input('type'),
            $request->input('status'),
            $request->input('sortBy', 'date'),
            $request->input('sort', 'desc')
        );

        return view('accounts.show', [
            'entity' => $account,
            'collection' => $orders,
        ]); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Account $account)
    {
        $areas = $this->em->getRepository(Area::class)
                                ->findBy([], ['name' => 'asc']);
        $users = $this->em->getRepository(User::class)
                                ->findBy([], ['email' => 'asc']);

        return view('accounts.form', [
            'route' => route('accounts.update', ['account' => $account->getId()]),
            'method' => 'PUT',
            'entity' => $account,
            'users' => $users,
            'areas' => $areas,
        ]); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AccountRequest $request, Account $account)
    {
        $this->hydrateData($account, $request->all());
        $this->em->flush();
        return redirect()->route('accounts.show', ['account' => $account->getId()])
                         ->with('success', 'Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account)
    {
        $this->em->remove($account);
        $this->em->flush();

        return redirect()->back()->with('success', 'Successfully removed');
    }

    /**
     * @param Account $entity
     * @param array $data
     *
     * @return void 
     */
    protected function hydrateData(Account $entity, array $data)
    {
        $entity->setName($data['name'])
            ->setType($data['type'])
            ->setAcronym($data['acronym'])
            ->setDetail($data['detail'])
            ;

        $entity->setLCode();
        if (isset($data['lcode'])) {
            $entity->setLCode($data['lcode']);
        }

        $entity->getUsers()->clear();
        if (isset($data['users']) && is_array($data['users'])) {
            $er = $this->em->getRepository(User::class);
            foreach ($data['users'] as $id) {
                $entity->addUser($er->find($id));
            }
        }

        foreach ($entity->getSubaccounts() as $subaccount) $this->em->remove($subaccount); //FIXME
        //$entity->getSubaccounts()->clear();
        if (isset($data['accounts']) && is_array($data['accounts'])) {
            $er = $this->em->getRepository(Area::class);
            foreach ($data['accounts'] as $id) {
                $account = new Subaccount();
                $account->setArea($er->find($id));
                $entity->addSubaccount($account);
            }
        }
    }
}
