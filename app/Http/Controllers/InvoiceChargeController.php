<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request,
    Illuminate\Validation\ValidationException;
use Doctrine\ORM\EntityManagerInterface;

use App\Entities\Movement,
    App\Entities\InvoiceCharge,
    App\Entities\OrderCharge,
    App\Entities\Order,
    App\Entities\Account,
    App\Events\MovementEvent,
    App\Http\Requests\InvoiceChargeRequest,
    App\Exceptions\Charge\DuplicatedChargeException
        as DuplicatedCharge,
    App\Exceptions\Account\InsufficientCreditException
        as AccountInsufficientCredit,
    App\Exceptions\Order\InvalidStatusException
        as OrderInvalidStatus,
    App\Exceptions\Supplier\InvoicedLimitException;

class InvoiceChargeController extends BaseController
{
    /**
     * @inheritDoc
     */
    protected function authorization()
    {
        $this->middleware('can:viewAny,'.Movement::class);
    }

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
        $data      = $request->validated();
        $detail    = $data['detail'];
        $hzPattern = OrderCharge::HZ_PATTERN;

        preg_match($hzPattern, $detail, $matches);

        switch (strtoupper($matches[1])) {
            case InvoiceCharge::HZ_PREFIX:
                $charge = new InvoiceCharge;
                $charge->hydrate($data)
                       ->setType(InvoiceCharge::TYPE_INVOICED);
                $this->hydrateInvoicedAccount($matches[2], $charge);
                if (null === ($acc = $charge->getSubaccount())) {
                    throw ValidationException::withMessages([
                        "detail" => trans("Account not found in :sequence", ['sequence' => $matches[2]])
                    ]);
                }
                break;
            case OrderCharge::HZ_PREFIX:
                $charge = new OrderCharge;
                $charge->hydrate($data)
                       ->setType(OrderCharge::TYPE_ORDER_INVOICED);
                $this->hydrateInvoicedOrder($matches[2], $charge);
                if (null === ($order = $charge->getOrder())) {
                    throw ValidationException::withMessages([
                        "detail" => trans("Order not found in :sequence", ['sequence' => $matches[2]])
                    ]);
                }
                break;
        }

        try {
            MovementEvent::dispatch($charge, __FUNCTION__);
        } catch (DuplicatedCharge $e) {
            throw ValidationException::withMessages([
                'hzentry' => $e->getMessage()
            ]);
        } catch (OrderInvalidStatus $e) {
            throw ValidationException::withMessages([
                'detail' => $e->getMessage()
            ]);
        } catch (AccountInsufficientCredit $e) {
            throw ValidationException::withMessages([
                'credit' => $e->getMessage()
            ]);
        } catch (InvoicedLimitException $e) {
            throw ValidationException::withMessages([
                'credit' => $e->getMessage()
            ]);
        } catch (\Exception $e) {
            throw $e;
        }

        $this->em->persist($charge);
        $this->em->flush();

        return redirect()->route('movements.index')
                         ->with('success', __('Successfully created'));
    }

    /**
     * @param string $sequence
     * @param OrderCharge $charge
     * @return OrderCharge
     */
    protected function hydrateInvoicedOrder($sequence, OrderCharge $charge)
    {
        if (preg_match(Order::SEQUENCE_PATTERN, $sequence, $matches)) {
            $order = $this->em
                          ->getRepository(Order::class)
                          ->findOneBy(['sequence' => $matches[0]]);
            if ($order) {
                $charge->setOrder($order)
                       ->setSubaccount($order->getSubaccount());
            }
            $charge->setDetail(trim(str_replace($matches[0], "", $sequence)));
        }
        if (!isset($order)) {
            $charge->setDetail($sequence);
        }

        return $charge;
    }

    /**
     * @param string $sequence
     * @param InvoiceCharge $charge
     * @return InvoiceCharge
     */
    protected function hydrateInvoicedAccount($sequence, InvoiceCharge $charge)
    {
        if (preg_match(Account::SEQUENCE_PATTERN, $sequence, $matches)){
            $criteria = [
                'acronym' => $matches[1],
                'type'    => $matches[2],
            ];
            if (isset($matches[4]) && $matches[4]) { 
                $criteria['lcode'] = $matches[4];
            }

            $account = $this->em
                            ->getRepository(Account::class)
                            ->findOneBy($criteria);

            //fixme: which subaccount
            if ($account) {
                $subaccounts = $account->getSubaccounts();
                if ($subaccounts->count() > 1) {
                    if (isset($matches[6])) {
                        foreach ($subaccounts as $sub) {
                            if ($sub->getArea()->getAcronym() === $matches[6]) $subaccount = $sub;
                        }
                    }
                }
                else {
                    $sub = $subaccounts->first();
                    if (!isset($matches[6]) || $sub->getArea()->getAcronym() === $matches[6]) $subaccount = $sub;
                }
                if (isset($subaccount)) {
                    $charge->setSubaccount($subaccount);
                }
            }
            $charge->setDetail(trim(str_replace($matches[0], "", $sequence)));
        }
        if (!isset($subaccount)) {
            $charge->setDetail($sequence);
        }
        return $charge;
    }

    /**
     * @param string $sequence
     * @param array $row
     * @return Charge
     */
    protected function getInvoiceCharge($sequence, array $row)
    {
        $hzCode = "{$row['hzyear']}-{$row['hzentry']}";
        $stored = $this->em
                       ->getRepository(InvoiceCharge::class)
                       ->findOneBy([
                            'hzCode' => $hzCode,
                            'type'   => InvoiceCharge::TYPE_INVOICED,
                        ]);

        $charge = $stored ? clone $stored : new InvoiceCharge;
        $charge->hydrate($row);
        $charge->setType(InvoiceCharge::TYPE_INVOICED);
        if (preg_match(
            Account::SEQUENCE_PATTERN, 
            $sequence, 
            $matches)) 
        {
            $criteria = [
                'acronym' => $matches[1],
                'type'    => $matches[2],
            ];
            if (isset($matches[4]) && $matches[4]) { 
                $criteria['lcode'] = $matches[4];
            }

            $account = $this->em
                            ->getRepository(Account::class)
                            ->findOneBy($criteria);

            //fixme: which subaccount
            if ($account) {
                $subaccount = $account->getSubaccounts()->first();
                $charge->setSubaccount($subaccount);
            }
            $charge->setDetail(trim(str_replace($matches[0], "", $sequence)));
        }
        if (!isset($subaccount)) {
            $charge->setDetail($sequence);
        }
        return $charge;
    }

    /**
     * @param string $sequence
     * @param array $row
     * @return OrderCharge
     */
    protected function getOrderCharge($sequence, array $row)
    {
        $hzCode = "{$row['hzyear']}-{$row['hzentry']}";
        $stored = $this->em
                       ->getRepository(OrderCharge::class)
                       ->findOneBy([
                            'hzCode' => $hzCode,
                            'type'   => OrderCharge::TYPE_ORDER_INVOICED,
                        ]);

        $charge = $stored ? clone $stored : new OrderCharge;
        $charge->hydrate($row);
        $charge->setType(OrderCharge::TYPE_ORDER_INVOICED);

        if (preg_match(
            Order::SEQUENCE_PATTERN, 
            $sequence, 
            $matches)) 
        {
            $order = $this->em
                          ->getRepository(Order::class)
                          ->findOneBy(['sequence' => $matches[0]]);
            if ($order) {
                $charge->setOrder($order);
            }
            $charge->setDetail(trim(str_replace($matches[0], "", $sequence)));
        }
        if (!isset($order)) {
            $charge->setDetail($sequence);
        }

        return $charge;
    }
}
