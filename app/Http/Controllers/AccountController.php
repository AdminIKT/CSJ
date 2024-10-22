<?php

namespace App\Http\Controllers;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Laminas\Hydrator\DoctrineObject;
use Illuminate\Http\Request,
    Illuminate\Support\Facades\Storage,
    Illuminate\Support\Facades\Gate,
    Illuminate\Support\Arr,
    Illuminate\Validation\ValidationException;

use App\Entities\Account,
    App\Entities\User,
    App\Entities\Order,
    App\Entities\Supplier,
    App\Entities\Subaccount,
    App\Entities\Area,
    App\Entities\Account\DriveFile,
    App\Repositories\OrderRepository,
    App\Http\Requests\AccountPutRequest,
    App\Http\Requests\AccountPostRequest,
    App\Services\CSJDriveService;

class AccountController extends BaseController
{
    /**
     * @var CSJDriveService
     */
    protected $drive;

    /**
     * @param EntityManagerInterface $em
     * @param CSJDriveService $drive 
     */
    public function __construct(EntityManagerInterface $em, CSJDriveService $drive)
    {
        $this->drive = $drive;
        parent::__construct($em);
    }

    /**
     * @inheritDoc
     */
    protected function authorization()
    {
        $this->authorizeResource(Account::class, 'account');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ppg      = $request->input('perPage', Config('app.per_page'));
        $accounts = $this->em->getRepository(Account::class)
                         ->search($request->all(), $ppg);

        $areas = $this->em->getRepository(Area::class)
                      ->findBy([], ['name' => 'ASC']);

        return view('accounts.index', [
            'perPage'  => $ppg,
            'accounts' => $accounts,
            'areas'    => Arr::pluck($areas, 'name', 'id'),
            // borja solicitar el calculo del total
            'totals' => app('em')->getRepository(Account::class)->totals($request->all())
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

        return view('accounts.create', [
            'route'     => route('accounts.store'),
            'method'    => 'POST',
            'entity'    => new Account,
            'users'     => $users,
            'areas'     => $areas,
            'accounts'  => Arr::pluck($areas, 'name', 'id'),
            'users'     => Arr::pluck($users, 'email', 'id'),
        ]); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccountPostRequest $request)
    {
        $entity = new Account;

        $em = app('em');
        $hydrator = new DoctrineObject($em);
        $hydrator->hydrate($request->validated(), $entity);
        $this->hydrateData($request->validated(), $entity);
        try {
            $estimates = $this->drive->createAccountFolder($entity, DriveFile::TYPE_ESTIMATE);
            $invoices  = $this->drive->createAccountFolder($entity, DriveFile::TYPE_INVOICE);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'acronym' => $e->getMessage()
            ]);
        }

        $entity->setFilesId($estimates->getId(), DriveFile::TYPE_ESTIMATE)
               ->setFilesUrl($estimates->getWebViewLink(), DriveFile::TYPE_ESTIMATE)
               ->setFilesId($invoices->getId(), DriveFile::TYPE_INVOICE)
               ->setFilesUrl($invoices->getWebViewLink(), DriveFile::TYPE_INVOICE);

        $em->persist($entity);
        $em->flush();
        return redirect()->route('accounts.show', ['account' => $entity->getId()])
                         ->with('success', __('Successfully created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Account $account)
    {
        $request->merge(['account' => $account->getId()]);
        $ppg    = $request->input('perPage', Config('app.per_page'));
        $orders = $this->em->getRepository(Order::class)
                       ->search($request->all(), $ppg);

        $suppliers = $this->em->getRepository(Supplier::class)
                         ->search(['account'=>$account->getId()], null)
                         ->items();

        return view('accounts.show', [
            'perPage'    => $ppg,
            'entity'     => $account,
            'collection' => $orders,
            'suppliers'  => Arr::pluck($suppliers, 'name', 'id'),
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

        return view('accounts.edit', [
            'route' => route('accounts.update', ['account' => $account->getId()]),
            'method' => 'PUT',
            'entity' => $account,
            'areas' => $areas,
            'users' => Arr::pluck($users, 'email', 'id'),
        ]); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AccountPutRequest $request, Account $account)
    {
        $em = app('em');
        $hydrator = new DoctrineObject($em);
        $hydrator->hydrate($request->validated(), $account);
        $this->hydrateData($request->validated(), $account);
        $em->flush();

        return redirect()->route('accounts.show', ['account' => $account->getId()])
                         ->with('success', __('Successfully updated'));
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

        return redirect()->back()->with('success', __('Successfully removed'));
    }

    /**
     * FIXME
     * @param Account $entity
     * @param array $data
     *
     * @return void 
     */
    protected function hydrateData(array $data, Account $entity)
    {
        if (isset($data['accounts']) && is_array($data['accounts'])) {
            //$entity->getSubaccounts()->clear();
            $er = $this->em->getRepository(Area::class);
            foreach ($data['accounts'] as $id) {
                $account = new Subaccount();
                $account->setArea($er->find($id));
                $entity->addSubaccount($account);
            }
        }
    }
}
