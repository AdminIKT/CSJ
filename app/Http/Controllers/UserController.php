<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Laminas\Hydrator\DoctrineObject;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Illuminate\Pagination\LengthAwarePaginator;
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
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', Config('app.per_page'));
        $page = max(1, (int)$request->input('page', 1));

        $qb = $this->em->createQueryBuilder();
        $qb->select('u')
           ->from(User::class, 'u')
           ->orderBy('u.email', 'ASC');

        // Status handling:
        // - initial request (no 'status' param): default to Active
        // - if 'status' param is present and non-empty: filter by that status
        // - if 'status' param is present but empty: show both statuses (no filter)
        if ($request->has('status')) {
            $statusVal = $request->input('status');
            if ($statusVal !== null && $statusVal !== '') {
                $qb->andWhere('u.status = :status')
                   ->setParameter('status', (int)$statusVal);
            } // else: explicit empty -> no status filter (both types)
        } else {
            $qb->andWhere('u.status = :status')
               ->setParameter('status', User::STATUS_ACTIVE);
        }

        if ($email = $request->input('email')) {
            $qb->andWhere('LOWER(u.email) LIKE :email')
               ->setParameter('email', '%'.strtolower($email).'%');
        }

        if ($name = $request->input('name')) {
            $qb->andWhere('LOWER(u.name) LIKE :name')
               ->setParameter('name', '%'.strtolower($name).'%');
        }

        if ($role = $request->input('role')) {
            $qb->leftJoin('u.roles', 'r')
               ->andWhere('r.id = :role')
               ->setParameter('role', (int)$role);
        }

        // If perPage is empty/null => return all results (no pagination)
        if (empty($perPage)) {
            $collection = $qb->getQuery()->getResult();
        } else {
            $perPage = (int)$perPage;
            $query = $qb->getQuery()
                        ->setFirstResult(($page - 1) * $perPage)
                        ->setMaxResults($perPage);

            $doctrinePaginator = new DoctrinePaginator($query, true);
            $total = count($doctrinePaginator);

            $items = [];
            foreach ($doctrinePaginator as $item) {
                $items[] = $item;
            }

            $collection = new LengthAwarePaginator($items, $total, $perPage, $page, [
                'path' => url()->current(),
                'query' => $request->query(),
            ]);
        }

        $roles = $this->em->getRepository(Role::class)
                       ->findBy([], ['name' => 'asc']);

        $roles = array_combine(
            array_map(function($e) { return $e->getId(); }, $roles),
            array_map(function($e) { return $e->getName(); }, $roles),
        );

        return view('users.index', [
            'perPage'    => $perPage,
            'collection' => $collection,
            'roles'      => $roles,
            'pagination' => !empty($perPage),
            'statusDefault' => $request->has('status') ? $request->input('status') : User::STATUS_ACTIVE,
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
