<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use Kris\LaravelFormBuilder\FormBuilder;
use Illuminate\Support\Facades\Gate,
    Illuminate\Support\Arr;

use App\Http\Requests\OrderPostRequest,
    App\Entities\InvoiceCharge,
    App\Entities\Movement,
    App\Entities\Account,
    App\Entities\Area,
    App\Entities\Order;

class OrderController extends BaseController
{
    /**
     * @inheritDoc
     */
    protected function authorization()
    {
        $this->authorizeResource(Order::class, 'order');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ppg    = $request->input('perPage', Config('app.per_page'));
        $orders = $this->em->getRepository(Order::class)
                        ->search($request->all(), $ppg);

        $accounts = $this->em->getRepository(Account::class)
                         ->findBy([], ['name' => 'ASC']);
        $accounts = array_combine(
            array_map(function($e) { return $e->getId(); }, $accounts),
            array_map(function($e) { return $e->getSerial(); }, $accounts),
        );

        $areas = $this->em->getRepository(Area::class)
                      ->findBy([], ['name' => 'ASC']);

        return view('orders.index', [
            'perPage'    => $ppg,
            'collection' => $orders,
            'accounts'   => $accounts,
            'areas'      => Arr::pluck($areas, 'name', 'id'),
        ]); 
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Order $order)
    {
        $ppg = $request->input('perPage', Config('app.per_page'));
        $collection = $this->em->getRepository(InvoiceCharge::class)
                           ->search(array_merge(
                                $request->all(), 
                                ['order' => $order->getId()]
                           ), $ppg);

        return view('orders.show', [
            'entity'     => $order,
            'collection' => $collection,
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
        return redirect()->route('orders.index')
                         ->with('success', 'Successfully deleted');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request, Order $order)
    {
        /*
        $refl = new \ReflectionClass(Order::class);
        $cons = $refl->getConstants();
        $cons = array_flip(array_reverse($cons, true));
        $perm = strtolower(str_replace("_", "-", $cons[$request->input('status')]));
        Gate::authorize("order-{$perm}", $order);
        */
        $order->setStatus($request->input('status'));
        $this->em->flush();
        return redirect()->back()
                         ->with("success", "Successfully updated");
    }
}
