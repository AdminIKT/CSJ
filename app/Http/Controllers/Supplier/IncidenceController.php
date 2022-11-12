<?php

namespace App\Http\Controllers\Supplier;

use Doctrine\ORM\EntityManagerInterface;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

use App\Events\IncidenceEvent,
    App\Entities\Order,
    App\Entities\Supplier,
    App\Entities\Supplier\Incidence;

class IncidenceController extends BaseController
{
    /**
     * @inheritDoc
     */
    protected function authorization()
    {
        $this->authorizeResource(Incidence::class, 'incidence');
        $this->middleware('can:update,incidence')->only('close');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Supplier $supplier)
    {
        return view('suppliers.incidences', [
            'entity' => $supplier,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Supplier $supplier, Request $request)
    {
        return view('suppliers.incidences.form', [
            'route'     => route('suppliers.incidences.store', ['supplier' => $supplier->getId()]),
            'order'     => $request->input('order'),
            'dst'       => $request->input('destination'),
            'entity'    => $supplier,
            'incidence' => new Incidence,
        ]); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Supplier $supplier, Request $request)
    {
        $values = $request->validate([
            'detail' => ['required', 'max:255'],
            'order'  => ['nullable'],
        ]);


        $incidence = new Incidence;
        $incidence->setSupplier($supplier)
                  ->setDetail($values['detail']);
        
        if ($values['order'] && null !== ($e = $this->em->find(Order::class, $values['order']))) {
            $incidence->setOrder($e); 
        }

        IncidenceEvent::dispatch($incidence, IncidenceEvent::ACTION_STORE);

        $this->em->persist($incidence);
        $this->em->flush();

        $dst = $request->get(
            'destination', route('suppliers.incidences.index', ['supplier' => $supplier->getId()])
        );
        return redirect()->to($dst)->with('success', __('Successfully created'));
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
    public function edit(Request $request, Supplier $supplier, Incidence $incidence)
    {
        if (!$supplier->getIncidences()->contains($incidence)) {
            abort(404);
        }

        return view('suppliers.incidences.form', [
            'route' => route('suppliers.incidences.update', [
                'supplier' => $supplier->getId(), 
                'incidence' => $incidence->getId(),
            ]),
            'method'    => 'PUT',
            'entity'    => $supplier,
            'incidence' => $incidence,
            'dst'       => $request->input('destination'),
        ]); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier, Incidence $incidence)
    {
        if (!$supplier->getIncidences()->contains($incidence)) {
            abort(404);
        }
        $values = $request->validate([
            'detail' => ['required', 'max:255'],
        ]);
        $incidence->setDetail($values['detail']);
        $this->em->flush();
        $dst = $request->get(
            'destination', route('suppliers.incidences.index', ['supplier' => $supplier->getId()])
        );
        return redirect()->to($dst)->with('success', __('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function close(Supplier $supplier, Incidence $incidence)
    {
        if (!$supplier->getIncidences()->contains($incidence)) {
            abort(404);
        }

        $incidence->setStatus(Incidence::STATUS_CLOSED);

        IncidenceEvent::dispatch($incidence, IncidenceEvent::ACTION_CLOSE);

        $this->em->flush();

        return redirect()->back()->with('success', 'Successfully closed');
    }
}
