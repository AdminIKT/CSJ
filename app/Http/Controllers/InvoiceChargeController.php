<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request,
    Illuminate\Validation\ValidationException;
use Doctrine\ORM\EntityManagerInterface;

use App\Entities\InvoiceCharge,
    App\Entities\Order,
    App\Entities\Account,
    App\Events\MovementEvent,
    App\Http\Requests\InvoiceChargeRequest,
    App\Exceptions\Account\InsufficientCreditException
        as AccountInsufficientCredit,
    App\Exceptions\Order\InvalidStatusException
        as OrderInvalidStatus;

class InvoiceChargeController extends BaseController
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('invoiceCharges.create', [
        ]); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvoiceChargeRequest $request)
    {
        $data = $request->validated();
        $description = $data['detail'];
        $matches = [];
        if (!preg_match(Order::SEQUENCE_PATTERN, $description, $matches)) {
            throw new \RuntimeException(sprintf("Description not matches with $pattern pattern"));
        }

        $order = $this->em->getRepository(Order::class)
                          ->findOneBy(['sequence' => $matches[0]]);

        if (!$order) {
            throw ValidationException::withMessages([
                "detail" => "Order {$matches[0]} not found"
                ]);
        }

        $movement = new InvoiceCharge;
        $movement->setCredit($data['credit'])
                 ->setInvoice($data['invoice'])
                 ->setInvoiceDate(new \Datetime($data['invoiceDate']))
                 ->setDetail(str_replace($matches[0], "", $description))
                 ->setType(InvoiceCharge::TYPE_INVOICED)
                 ->setSubaccount($order->getSubaccount())
                 ->setOrder($order);

        try {
            MovementEvent::dispatch($movement, __FUNCTION__);
        } catch (OrderInvalidStatus $e) {
            throw ValidationException::withMessages([
                'detail' => $e->getMessage()
            ]);
        } catch (AccountInsufficientCredit $e) {
            throw ValidationException::withMessages([
                'credit' => $e->getMessage()
            ]);
        } catch (\Exception $e) {
            throw $e;
        }

        $this->em->persist($movement);
        $this->em->flush();

        return redirect()->route('movements.index')
                         ->with('success', __('Successfully created'));
    }

    protected function authorization()
    {}
}
