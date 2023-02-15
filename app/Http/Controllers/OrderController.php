<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use Kris\LaravelFormBuilder\FormBuilder;
use Illuminate\Support\Facades\Gate,
    Illuminate\Support\Arr,
    Illuminate\Validation\ValidationException;

use App\Http\Requests\OrderPutRequest,
    App\Entities\OrderCharge,
    App\Entities\Movement,
    App\Entities\Account,
    App\Entities\Area,
    App\Entities\Order,
    App\Entities\Order\Product,
    App\Entities\Supplier,
    App\Entities\Account\DriveFile,
    App\Entities\Action\OrderAction as Action,
    App\Events\OrderEvent,
    App\Services\CSJDriveService;

class OrderController extends BaseController
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
        $ppg     = $request->input('perPage', Config('app.per_page'));
        $actions = $this->em
                        ->getRepository(Action::class)
                        ->search(array_merge(
                            $request->all(),
                            ['entity' => $order->getId()]
                        ), $ppg);

        return view('orders.show', [
            'perPage'    => $ppg,
            'entity'     => $order,
            'collection' => $actions,
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
              ->setDetail($values['detail'])
              ->setDate(new \DateTime($values['date']));

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
    public function destroy(Order $order)
    {
        $this->em->remove($order);

        OrderEvent::dispatch($order, __FUNCTION__);

        $this->em->flush();
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

        $values = $request->validate([
            'status'  => [
                //'required'
            ],
            'invoice' => [
                //'required_if:status,5',
                'mimes:pdf'
            ],
        ]);

        if (isset($values['status'])) {
            $order->setStatus($values['status']);
            OrderEvent::dispatch($order, OrderEvent::ACTION_STATUS);
        }

        if (null !== ($f = $request->file('invoice'))) {
            try {
                $file = $this->drive->uploadFile($f, $order, DriveFile::TYPE_INVOICE);
            } catch (\Exception $e) {
                throw ValidationException::withMessages([
                    'invoice' => $e->getMessage()
                ]);
            }

            $order->setFileId($file->getId(), DriveFile::TYPE_INVOICE)
                  ->setFileUrl($file->getWebViewLink(), DriveFile::TYPE_INVOICE);
            OrderEvent::dispatch($order, OrderEvent::ACTION_INVOICE);
        }

        if ($order->isStatus(Order::STATUS_CHECKED_INVOICED) && 
            $order->getFileId(DriveFile::TYPE_INVOICE) === null
        ) {
            throw ValidationException::withMessages([
                'invoice' => __("Invoice is required for order in ':status' state", ['status' => Order::statusName(Order::STATUS_CHECKED_INVOICED)]) 
            ]);
        } 

        $this->em->flush();
        return redirect()->back()
                         ->with("success", __('Successfully updated'));
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function receptions(Request $request)
    {
        $ppg    = $request->input('perPage', Config('app.per_page'));
        $orders = $this->em->getRepository(Order::class)
                        ->search(array_merge(
                            $request->all(), [
                           // 'receiveIn' => Order::RECEIVE_IN_RECEPTION,
                            'status'    => [Order::STATUS_CREATED,Order::STATUS_CHECKED_PARTIAL_AGREED]
                            ]
                        ), $ppg);

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

        return view('orders.receptions', [
            'perPage'    => $ppg,
            'collection' => $orders,
            'accounts'   => $accounts,
            'areas'      => Arr::pluck($areas, 'name', 'id'),
            'suppliers'  => Arr::pluck($suppliers, 'name', 'id'),
        ]); 
    }
}
