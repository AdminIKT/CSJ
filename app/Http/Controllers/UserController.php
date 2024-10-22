<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Laminas\Hydrator\DoctrineObject;
use App\Http\Requests\UserRequest;
use App\Entities\User,
    App\Entities\Role,
    App\Entities\Account,
    App\Entities\Action;

class UserController extends BaseController
{
    /**
     * @inheritDoc
     */
    protected function authorization()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collection = $this->em->getRepository(User::class)
                               ->findBy([], ['email' => 'asc']);

        return view('users.index', [
            'collection' => $collection,
        ]); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $user)
    {
        $ppg      = $request->input('perPage', Config('app.per_page'));
        $accounts = $this->em->getRepository(Account::class)
                             ->search(array_merge(
                                $request->all(), [
                                    'user' => $user->getId(),
                                    'status' => Account::STATUS_ACTIVE,
                                ]), $ppg);

        return view('users.show', [
            'perPage'    => $ppg,
            'entity'     => $user,
            'collection' => $accounts,
        ]); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = $this->em->getRepository(Role::class)
                      ->findBy([], ['name' => 'asc']);

        return view('users.form', [
            'route'  => route('users.store'),
            'method' => 'POST',
            'entity' => new User,
            'roles'  => $roles,
        ]); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AreaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $em = app('em');
        $hydrator = new DoctrineObject($em);

        $user = new User;
        $hydrator->hydrate($request->validated(), $user);

        $em->persist($user);
        $em->flush();
        return redirect()->route('users.show', ['user' => $user->getId()])
                         ->with('success', __('Successfully created'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = $this->em->getRepository(Role::class)
                      ->findBy([], ['name' => 'asc']);

        return view('users.form', [
            'route'  => route('users.update', ['user' => $user->getId()]),
            'method' => 'PUT',
            'entity' => $user,
            'roles'  => $roles,
        ]); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $em = app('em');
        $hydrator = new DoctrineObject($em);
        $hydrator->hydrate($request->validated(), $user);
        $em->flush();
        return redirect()->route('users.show', ['user' => $user->getId()])
                         ->with('success', __('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->em->remove($user);
        $this->em->flush();

        return redirect()->back()->with('success', __('Successfully removed'));
    }
}
