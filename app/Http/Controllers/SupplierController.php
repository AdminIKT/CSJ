<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;

use App\Events\SupplierEvent,
    App\Entities\Supplier,
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
    public function index(Request $request)
    {
        $cities = $this->em->getRepository(Supplier::class)->cities();
        $cities = array_combine($cities, $cities);

        $suppliers = $this->em->getRepository(Supplier::class)->search(
            $request->input('nif'),
            $request->input('name'),
            $request->input('city'),
            $request->input('recommendable'),
            $request->input('acceptable'),
            $request->input('sortBy', 'name'),
            $request->input('sort', 'asc')
        );

        return view('suppliers.index', [
            'cities'     => $cities,
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
        return view('suppliers.form', [
            'route'  => route('suppliers.store'),
            'method' => 'POST',
            'entity' => new Supplier,
            'dst'    => $request->input('destination'),
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
        SupplierEvent::dispatch($entity);
        $this->em->persist($entity);
        $this->em->flush();
        $dst = $request->get(
            'destination', route('suppliers.show', ['supplier' => $entity->getId()])
        );
        return redirect()->to($dst)->with('success', 'Successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        return view('suppliers.show', [
            'entity' => $supplier,
        ]); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        return view('suppliers.form', [
            'route' => route('suppliers.update', ['supplier' => $supplier->getId()]),
            'method' => 'PUT',
            'entity' => $supplier,
        ]); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SupplierRequest $request, Supplier $supplier)
    {
        $this->hydrateData($supplier, $request->all());
        $this->em->flush();
        return redirect()->route('suppliers.show', ['supplier' => $supplier->getId()])
                         ->with('success', 'Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        $this->em->remove($supplier);
        $this->em->flush();

        return redirect()->back()->with('success', 'Successfully removed');
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
        $entity->setAcceptable(isset($data['acceptable']));
        $entity->setRecommendable(isset($data['recommendable']));

        if (isset($data['contacts'])) {
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
}
