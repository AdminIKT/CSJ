<?php

namespace App\Http\Controllers\Subaccount;

use Doctrine\ORM\EntityManagerInterface,
    Doctrine\Common\Collections\Criteria 
        as DoctrineCriteria;
use Illuminate\Http\Request,
    Illuminate\Support\Arr,
    Illuminate\Http\UploadedFile,
    Illuminate\Support\Facades\Storage,
    Illuminate\Validation\ValidationException;

use App\Http\Controllers\BaseController,
    App\Http\Requests\OrderPostRequest;
use App\Entities\Account,
    App\Entities\Subaccount,
    App\Entities\Supplier,
    App\Entities\Settings,
    App\Entities\Order,
    App\Entities\Order\Product,
    App\Entities\Account\DriveFile,
    App\Events\OrderEvent;
use App\Exceptions\Supplier\InvoicedLimitException;

class OrderController extends BaseController
{
    /**
     * @inheritDoc
     */
    protected function authorization()
    {
        $this->middleware('can:view,subaccount')
             ->only(['create', 'store']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Subaccount $subaccount, Request $request)
    {
        $criteria = new DoctrineCriteria;
        $criteria->where(DoctrineCriteria::expr()
                 ->gte('status', Supplier::STATUS_VALIDATED))
                 ->orderBy(['name' => DoctrineCriteria::ASC]);
        $suppliers = $this->em->getRepository(Supplier::class)
                          ->matching($criteria)
                          ->toArray();

        $limit = $this->em->getRepository(Settings::class)
                          ->findOneBy(['type' => Settings::TYPE_SUPPLIER_INVOICED_LIMIT]);

        $disableds = array_combine(
            array_map(function($e) { return $e->getId(); }, $suppliers),
            array_map(function($e) use ($limit) { 
                return ['disabled' => null !== ($inv = $e->getInvoiced(date('Y'))) && $inv->getTotal() >= $limit->getValue()];
            }, $suppliers),
        );

        $lastest = $this->em->getRepository(Order::class)
                        ->search([
                            'account' => $subaccount->getAccount(),
                            'from' => new \Datetime("1-1-". date('Y')),
                            'sort' => 'DESC',
                        ], null);

        return view('subaccounts.orders.create', [
            'subaccount' => $subaccount,
            'disableds'  => $disableds,
            'entity'     => new Order,
            'suppliers'  => Arr::pluck($suppliers, 'name', 'id'),
            'lastest'    => $lastest,
        ]); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Subaccount $subaccount, OrderPostRequest $request)
    {
        $last = $this->em->getRepository(Order::class)->lastest($subaccount->getAccount());

        if ($last) {
            $matches = [];
            if (!preg_match(Order::SEQUENCE_PATTERN, $last->getSequence(), $matches)) {
                throw new \RuntimeException(sprintf("%s description not matches with %s pattern", 
                    $last->getSequence(), Order::SEQUENCE_PATTERN));
            }
            $sequence = (int) trim($matches[5], "-") + 1;
        }

        $order = new Order;
        $this->hydrateData($order, $request->all());
        if (!$order->getSequence()) {
            $order->setDate(new \DateTime('today'));
            $order->setSequence(implode("-", [
                "{$subaccount->getAccount()->getSerial()}/{$order->getDate()->format('y')}",
                isset($sequence) ? $sequence : 1
            ])); //FIXME
        }

        $subaccount->addOrder($order);

        try {
            OrderEvent::dispatch($order, OrderEvent::ACTION_STORE);
        } catch (InvoicedLimitException $e) {
            throw ValidationException::withMessages([
                'supplier' => $e->getMessage()
            ]);
        } catch (\Exception $e) {
            throw $e;
        }

        if (null !== ($file = $request->file('estimated'))) {
            $path = Storage::disk('public')->putFileAs('files', $file, "{$order->getSequence()}.pdf");
            $order->setEstimated($path);

            try {
                $this->uploadToDrive($file, $order);
            } catch (\Google\Exception $e) {
                foreach ($e->getErrors() as $error) {
                    throw ValidationException::withMessages([
                        'estimated' => $error['message']
                    ]);
                }
            } catch (\Exception $e) {
                throw ValidationException::withMessages([
                    'estimated' => $e->getMessage()
                ]);
            }
        }

        $this->em->persist($order);
        $this->em->flush();
        return redirect()->route('orders.show', $order->getId())
                         ->with('success', __('Successfully created'));
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
        if (isset($data['date'])) {
            $entity->setDate(new \Datetime($data['date']));
            //FIXME: Is necessary?
            $entity->getDate()->settime(0,0);
        }
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

    //FIXME: Permissions
    protected function uploadToDrive(UploadedFile $file, Order $order)
    {
        $parent   = $order->getAccount()->getEstimatedFileId();
        $storage  = Storage::disk('google');

        $adapter = $storage->getAdapter();
        $service = $adapter->getService();

        //$children = $service->files->listFiles([
        //                  'q' => "'{$parent}' in parents", 
        //             ])->getFiles();
        //foreach ($children as $child) {
        //    if ($child->getName() == $order->getDate()->format('y')) {
        //        $folder = $child;
        //    }
        //}
        foreach ($order->getAccount()->getEstimatedFiles() as $folder) {
            if ($folder->getName() === $order->getDate()->format('Y')) {
                $child = $folder;
            }
        }

        if (!isset($child)) {
            $fileMetadata = new \Google_Service_Drive_DriveFile([
                'name'     => $order->getDate()->format('Y'),
                'parents'  => [$parent],
                'mimeType' => 'application/vnd.google-apps.folder',
            ]);
            $folder = $service->files->create($fileMetadata, [
                        'fields' => 'id, name, webViewLink'
                        ]);
            $child  = new DriveFile;
            $child->setName($folder->getName())
                  ->setFileId($folder->getId())
                  ->setFileUrl($folder->getWebViewLink());
            $order->getAccount()->addEstimatedFile($child);
        }

        $fileMetadata = new \Google_Service_Drive_DriveFile([
            'name' => "{$order->getSequence()}.pdf",
            'parents' => [$child->getFileId()], 
        ]);

        if (null !== ($res = $service->files->create($fileMetadata, [
                   'data' => file_get_contents($file),
                   'mimeType' => $file->getClientMimeType(),
                   'uploadType' => 'multipart',
                   'fields' => 'id, webViewLink',
            ]))) {
            $order->setEstimatedFileId($res->getId())
                  ->setEstimatedFileUrl($res->getWebViewLink());
        }

        return $res;
    }
}
