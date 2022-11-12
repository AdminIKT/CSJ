<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use Kris\LaravelFormBuilder\FormBuilder;
use Illuminate\Support\Facades\Gate,
    Illuminate\Support\Arr;

use App\Http\Requests\OrderPutRequest,
    App\Entities\InvoiceCharge,
    App\Entities\Movement,
    App\Entities\Account,
    App\Entities\Area,
    App\Entities\Order,
    App\Entities\Order\Product,
    App\Entities\Supplier,
    App\Events\OrderEvent;

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

        $suppliers = $this->em->getRepository(Supplier::class)
                         ->search(['orders' => true], null)
                         ->items();

        $areas = $this->em->getRepository(Area::class)
                      ->findBy([], ['name' => 'ASC']);

        return view('orders.index', [
            'perPage'    => $ppg,
            'collection' => $orders,
            'accounts'   => $accounts,
            'areas'      => Arr::pluck($areas, 'name', 'id'),
            'suppliers'  => Arr::pluck($suppliers, 'name', 'id'),
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(OrderPutRequest $request, Order $order)
    {
        $values = $request->validated();

        $order->setReceiveIn($values['receiveIn'])
              ->setDetail($values['detail']);

        if (isset($values['products'])) {
            $products = $values['products'];
            foreach ($order->getProducts() as $product) {
                if (false !== ($key = array_search(
                        $product->getId(),  
                        array_column($products, 'id')))
                ) { 
                    $product->setDetail($products[$key]['detail'])
                            ->setUnits($products[$key]['units']);
                }
                else {
                    $order->removeProduct($product);
                }
            }
            foreach ($products as $product) {
                if (!$product['id']) {
                    $order->addProduct(Product::fromArray($product));
                }
            }
        }
        else {
            foreach ($order->getProducts() as $product) {
                $order->removeProduct($product);
            }
        }

        $this->em->flush();
        return redirect()->route('orders.show', ['order' => $order->getId()])
                         ->with('success', __('Successfully updated'));
    }

    /**
     * FIXME
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return redirect()->route('orders.index')
                         ->with('success', __('Successfully removed'));
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request, Order $order)
    {
        /*
        TODO
        $refl = new \ReflectionClass(Order::class);
        $cons = $refl->getConstants();
        $cons = array_flip(array_reverse($cons, true));
        $perm = strtolower(str_replace("_", "-", $cons[$request->input('status')]));
        Gate::authorize("order-{$perm}", $order);
        */
        $order->setStatus($request->input('status'));

        OrderEvent::dispatch($order, OrderEvent::ACTION_STATUS);

        $this->em->flush();
        return redirect()->back()
                         ->with("success", __('Successfully updated'));
    }
}
