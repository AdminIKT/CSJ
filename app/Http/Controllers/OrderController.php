<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use Kris\LaravelFormBuilder\FormBuilder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Arr;

use App\Http\Requests\OrderPostRequest,
    App\Entities\Department,
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
            $request->input('department'),
            $request->input('area'),
            $request->input('supplier'),
            $request->input('type'),
            $request->input('status'),
            $request->input('sortBy', 'date'),
            $request->input('sort', 'desc')
        );

        $departments = $this->em->getRepository(Department::class)->findBy([], ['name' => 'ASC']);

        return view('orders.index', [
            'collection'  => $orders,
            'departments' => Arr::pluck($departments, 'name', 'id'),
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
