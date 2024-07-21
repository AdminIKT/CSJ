<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\Facades\Storage,
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

        $this->hydrateData($entity, $request->validated());

        try {
            $estimates = $this->drive->getFolder($entity, DriveFile::TYPE_ESTIMATE);
            $invoices  = $this->drive->getFolder($entity, DriveFile::TYPE_INVOICE);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'acronym' => $e->getMessage()
            ]);
        }

        $entity->setFilesId($estimates->getId(), DriveFile::TYPE_ESTIMATE)
               ->setFilesUrl($estimates->getWebViewLink(), DriveFile::TYPE_ESTIMATE)
               ->setFilesId($invoices->getId(), DriveFile::TYPE_INVOICE)
               ->setFilesUrl($invoices->getWebViewLink(), DriveFile::TYPE_INVOICE);

        $this->em->persist($entity);
        $this->em->flush();
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
        $this->hydrateData($account, $request->validated());
        $this->em->flush();
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
     * @param Account $entity
     * @param array $data
     *
     * @return void 
     */
    protected function hydrateData(Account $entity, array $data)
    {
        $entity->setName($data['name'])            
            ->setDetail($data['detail'])
            ->setStatus($data['status'])
            ;

        if (isset($data['acronym'])) {
            $entity->setAcronym($data['acronym']);
        }

        if (isset($data['type'])) {
            $entity->setType($data['type']);
            $entity->setLCode();
            if (isset($data['lcode'])) {
                $entity->setLCode($data['lcode']);
            }
        }

        $entity->getUsers()->clear();
        if (isset($data['users']) && is_array($data['users'])) {
            $er = $this->em->getRepository(User::class);
            foreach ($data['users'] as $id) {
                $entity->addUser($er->find($id));
            }
        }

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
