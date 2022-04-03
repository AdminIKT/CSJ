<?php

namespace App\Http\Controllers\Area;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Controller,
    App\Http\Requests\OrderPostRequest;
use App\Entities\Area,
    App\Entities\Supplier,
    App\Entities\Settings,
    App\Entities\Order,
    App\Entities\Order\Product,
    App\Events\OrderEvent;

class OrderController extends Controller
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
    public function index(Area $area)
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Area $area, Request $request)
    {
        $collection = $this->em->getRepository(Supplier::class)
                               ->findBy(['acceptable' => true], ['name' => 'asc']);

        $limit = $this->em->getRepository(Settings::class)
                               ->findOneBy(['type' => Settings::TYPE_INVOICED_LIMIT]);

        $suppliers = array_combine(
            array_map(function($e) { return $e->getId(); }, $collection),
            array_map(function($e) { return $e->getName(); }, $collection),
        );

        $disableds = array_combine(
            array_map(function($e) { return $e->getId(); }, $collection),
            array_map(function($e) use ($limit) { 
                return ['disabled' => null !== ($inv = $e->getInvoiced(date('Y'))) && $inv->getCredit() >= $limit->getValue()];
            }, $collection),
        );

        return view('areas.orders.create', [
            'area'      => $area,
            'suppliers' => $suppliers,
            'disableds' => $disableds,
            'entity'    => new Order,
        ]); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Area $area, OrderPostRequest $request)
    {
        $last = $this->em->getRepository(Order::class)->findOneBy([
            'area' => $area,
        ], ['date' => 'DESC']);

        if ($last) {
            $matches = [];
            if (!preg_match(Order::SEQUENCE_PATTERN, $last->getSequence(), $matches)) {
                throw new \RuntimeException(sprintf("Description not matches with $pattern pattern"));
            }
            $sequence = (int) trim($matches[5], "-") + 1;
        }

        $order = new Order;
        $this->hydrateData($order, $request->all());
        if (!$order->getSequence()) {
            $order->setDate(new \DateTime);
            $order->setSequence(implode("-", [
                "{$area->getSerial()}/{$order->getDate()->format('y')}",
                isset($sequence) ? $sequence : 1
            ])); //FIXME
        }

        if (null !== ($file = $request->file('estimated'))) {
            $path = Storage::disk('public')->putFileAs('files', $file, "{$order->getSequence()}.pdf");
            $order->setEstimated($path);
        }

        $area->addOrder($order)
            ->increaseCompromisedCredit($order->getEstimatedCredit())
            ;

        OrderEvent::dispatch($order);

        $this->em->persist($order);
        $this->em->flush();
        return redirect()->route('orders.show', $order->getId())
                         ->with('success', 'Successfully created');
    }

    /**
     * @param Order $entity
     * @param array $data
     *
     * @return void 
     */
    protected function hydrateData(Order $entity, array $data = [])
    {
        if (isset($data['estimatedCredit']))    $entity->setEstimatedCredit($data['estimatedCredit']);
        if (isset($data['detail']))             $entity->setDetail($data['detail']);
        if (isset($data['sequence']))           $entity->setSequence($data['sequence']);
        if (isset($data['date']))               $entity->setDate(new \Datetime($data['date']));
        if (isset($data['supplier'])) {
            if (null === ($e = $this->em->find(Supplier::class, $data['supplier']))) {
                throw new \RuntimeException("Supplier {$data['supplier']} not found"); 
            }
            $entity->setSupplier($e);
        }
        if (isset($data['products'])) {
            foreach ($data['products'] as $raw) {
                $product = new Product;
                if (isset($raw['detail'])) $product->setDetail($raw['detail']);
                if (isset($raw['units']))  $product->setUnits($raw['units']);
                $entity->addProduct($product);
            }
        }
    }
}
