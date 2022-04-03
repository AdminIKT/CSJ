<?php

namespace App\Http\Controllers\Supplier;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller,
    App\Http\Requests\Supplier\ContactRequest;
use App\Entities\Supplier,
    App\Entities\Supplier\Contact;

class ContactController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Supplier $supplier, Request $request)
    {
        return view('suppliers.contacts.form', [
            'route'   => route('suppliers.contacts.store', ['supplier' => $supplier->getId()]),
            'dst'     => $request->input('destination'),
            'entity'  => $supplier,
            'contact' => new Contact,
        ]); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Supplier $supplier, ContactRequest $request)
    {
        $contact = new Contact;
        $contact->setSupplier($supplier);
        $this->hydrateData($contact, $request->all());
        $this->em->persist($contact);
        $this->em->flush();
        $dst = $request->get(
            'destination', route('suppliers.show', ['supplier' => $supplier->getId()])
        );
        return redirect()->to($dst)->with('success', 'Successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier, Contact $contact)
    {
        if (!$supplier->getContacts()->contains($contact)) {
            abort(404);
        }

        return view('suppliers.contacts.form', [
            'route' => route('suppliers.contacts.update', [
                'supplier' => $supplier->getId(), 
                'contact' => $contact->getId(),
            ]),
            'method' => 'PUT',
            'entity'  => $supplier,
            'contact' => $contact,
        ]); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ContactRequest $request, Supplier $supplier, Contact $contact)
    {
        if (!$supplier->getContacts()->contains($contact)) {
            abort(404);
        }
        $this->hydrateData($contact, $request->all());
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
    public function destroy(Supplier $supplier, Contact $contact)
    {
        if (!$supplier->getContacts()->contains($contact)) {
            abort(404);
        }
        $this->em->remove($contact);
        $this->em->flush();

        return redirect()->back()->with('success', 'Successfully removed');
    }

    /**
     * @param Contact $entity
     * @param array $data
     *
     * @return void 
     */
    protected function hydrateData(Contact $entity, array $data = [])
    {
        $entity->setName($data['name']);
        $entity->setEmail($data['email']);
        $entity->setPhone($data['phone']);
        $entity->setPosition($data['position']);
    }
}
