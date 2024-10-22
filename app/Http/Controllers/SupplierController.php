<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Laminas\Hydrator\DoctrineObject;

use App\Events\SupplierEvent,
    App\Entities\Supplier,
    App\Entities\Supplier\Contact,
    App\Http\Requests\SupplierRequest;

class SupplierController extends BaseController
{
    /**
     * @inheritDoc
     */
    protected function authorization()
    {
        $this->authorizeResource(Supplier::class, 'supplier');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ppg     = $request->input('perPage', Config('app.per_page'));
        $cities  = $this->em->getRepository(Supplier::class)->cities();
        $cities  = array_combine($cities, $cities);
        $regions = $this->em->getRepository(Supplier::class)->regions();
        $regions = array_combine($regions, $regions);

        $suppliers = $this->em
                          ->getRepository(Supplier::class)
                          ->search($request->all(), $ppg);

        return view('suppliers.index', [
            'perPage'    => $ppg,
            'cities'     => $cities,
            'regions'    => $regions,
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
        $cities  = $this->em->getRepository(Supplier::class)->cities();
        $regions = $this->em->getRepository(Supplier::class)->regions();
        return view('suppliers.form', [
            'route'     => route('suppliers.store'),
            'method'    => 'POST',
            'entity'    => new Supplier,
            'cities'    => $cities,
            'regions'   => $regions,
            'dst'       => $request->input('destination'),
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
        $em = app('em');
        $hydrator = new DoctrineObject($em);

        $entity = new Supplier;
        $hydrator->hydrate($request->all(), $entity);
        SupplierEvent::dispatch($entity, SupplierEvent::ACTION_STORE);
        $em->persist($entity);
        $em->flush();

        $dst = $request->get(
            'destination', route('suppliers.show', ['supplier' => $entity->getId()])
        );
        return redirect()->to($dst)->with('success', __('Successfully created'));
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
        $cities  = $this->em->getRepository(Supplier::class)->cities();
        $regions = $this->em->getRepository(Supplier::class)->regions();
        return view('suppliers.form', [
            'route' => route('suppliers.update', ['supplier' => $supplier->getId()]),
            'method'    => 'PUT',
            'entity'    => $supplier,
            'cities'    => $cities,
            'regions'   => $regions,
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
        $values = $request->all();

        $em = app('em');
        $hydrator = new DoctrineObject($em);
        if ($supplier->isNoAcceptable() 
            && (int) $values['status'] > $supplier->getStatus()) {
            $supplier->setIncidenceCount(0);
        }
        $hydrator->hydrate($values, $supplier);
        $em->flush();

        return redirect()->route('suppliers.show', ['supplier' => $supplier->getId()])
                         ->with('success', __('Successfully updated'));
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

        return redirect()->back()->with('success', __('Successfully removed'));
    }
}
