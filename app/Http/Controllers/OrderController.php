<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use Kris\LaravelFormBuilder\FormBuilder;
use Illuminate\Support\Facades\Gate;

use App\Http\Requests\OrderPostRequest,
    App\Repositories\OrderRepository,
    App\Entities\Area,
    App\Entities\Order;

class OrderController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders = $this->em->getRepository(Order::class)->search(
            $request->input('sequence'),
            $request->input('from'),
            $request->input('to'),
            $request->input('area'),
            $request->input('supplier'),
            $request->input('type'),
            $request->input('status'),
            $request->input('sortBy', 'date'),
            $request->input('sort', 'desc'),
            $request->input('perPage', OrderRepository::PER_PAGE)
        );

        $areas  = $this->em->getRepository(Area::class)->findBy([], ['name' => 'ASC']);
        $areas  = array_combine(
            array_map(function($e) { return $e->getId(); }, $areas),
            array_map(function($e) { return "{$e->getName()}-{$e->getType()}"; }, $areas),
        );
        
        return view('orders.index', [
            'collection' => $orders,
            'areas'      => $areas,
            'perPage'    => $request->input('perPage', OrderRepository::PER_PAGE),
        ]); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //Gate::authorize('show-order', $order);

        return view('orders.show', [
            'entity' => $order,
        ]); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //Gate::authorize('update-order', $order);

        return view('orders.edit', [
            'entity' => $order,
        ]); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return redirect()->route('orders.index')->with('success', 'Successfully deleted');
    }
}
