<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;

use App\Entities\Supplier,
    App\Entities\Supplier\Contact,
    App\Http\Requests\SupplierRequest;

class SupplierController extends Controller
{
    /**
     * @EntityManagerInterface
     */ 
    protected $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = $this->em->getRepository(Supplier::class)->lastest();

        return view('suppliers.index', [
            'collection' => $suppliers,
        ]); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $entity = new Supplier;
        $this->hydrateData($entity, $request->old());
        return view('suppliers.create', [
            'entity' => $entity,
        ]); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupplierRequest $request)
    {
        $entity = new Supplier;
        $this->hydrateData($entity, $request->all());
        $this->em->persist($entity);
        $this->em->flush();
        return redirect()->route('suppliers.index')
                         ->with('success', 'Successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (null === ($entity = $this->em->find(Supplier::class, $id))) {
            abort(404);
        }

        return view('suppliers.show', [
            'entity' => $entity,
        ]); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param Supplier $entity
     * @param array $data
     *
     * @return void 
     */
    protected function hydrateData(Supplier $entity, array $data = [])
    {
        if (isset($data['name'])) $entity->setName($data['name']);
        if (isset($data['nif'])) $entity->setNif($data['nif']);
        if (isset($data['zip'])) $entity->setZip($data['zip']);
        if (isset($data['city'])) $entity->setCity($data['city']);
        if (isset($data['address'])) $entity->setAddress($data['address']);

        if (!isset($data['contacts'])) $data['contacts'] = [[]];
        foreach ($data['contacts'] as $raw) {
            $contact = new Contact;
            if (isset($raw['name'])) $contact->setName($raw['name']);
            if (isset($raw['email'])) $contact->setEmail($raw['email']);
            if (isset($raw['phone'])) $contact->setPhone($raw['phone']);
            if (isset($raw['position'])) $contact->setPosition($raw['position']);
            $entity->addContact($contact);
        }
    }
}
