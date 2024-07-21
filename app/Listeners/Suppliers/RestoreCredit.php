<?php

namespace App\Listeners\Suppliers;

use App\Events\AbstractEvent,
    App\Events\OrderEvent,
    App\Events\MovementEvent,
    App\Entities\Order,
    App\Entities\Movement,
    App\Entities\OrderCharge,
    App\Entities\Settings,
    App\Entities\Supplier\Invoiced;
use App\Exceptions\Supplier\InvoicedLimitException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Doctrine\ORM\EntityManagerInterface;

class RestoreCredit 
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
     * Handle the event.
     *
     * @param  \App\Events\AbstractEvent  $event
     * @return void
     */
    public function handle(AbstractEvent $event)
    {
        switch (true) {
            case $event instanceof MovementEvent:
                $this->_movementCredit($event);
                break;
            case $event instanceof OrderEvent:
                $this->_orderCredit($event);
                break;
        }
    }

    /**
     * @param  \App\Events\OrderEvent  $event
     * @return void
     */
    protected function _orderCredit(OrderEvent $event)
    {
        $order = $event->entity;
        if ($event->action === OrderEvent::ACTION_STORE) {
            $this->_increaseEstimated($event);
        }
        elseif (($event->action === OrderEvent::ACTION_STATUS
                && $order->isCancelled()) 
                || $event->action === OrderEvent::ACTION_DELETE) {
            $this->_decreaseEstimatedCredit($event);
        }         
    }

    /**
     * @param  OrderEvent  $event
     * @return void
     */
    protected function _increaseEstimated(OrderEvent $event)
    {
        $order    = $event->entity;
        $invoiced = $this->_getSupplierInvoiced($order);
        $invoiced->increaseEstimated($order->getEstimatedCredit()); 

        $this->_ensureOfInvoicedLimit($invoiced);
    }

    /**
     * @param  OrderEvent  $event
     * @return void
     */
    protected function _decreaseEstimatedCredit(OrderEvent $event)
    {
        $order    = $event->entity;
        $invoiced = $this->_getSupplierInvoiced($order);
        if ($order->hasCredit()) {
            $invoiced->decreaseCredit($order->getCredit());
        }
        else {
            $invoiced->decreaseEstimated($order->getEstimatedCredit());
        }
    }

    /**
     * @param  \App\Events\MovementEvent  $event
     * @return void
     */
    protected function _movementCredit(MovementEvent $event)
    {
        $movement = $event->entity;
        if (!($movement instanceof OrderCharge && 
             $event->action === MovementEvent::ACTION_STORE
        )) {
            return;
        }

        $order    = $movement->getOrder();
        $invoiced = $this->_getSupplierInvoiced($order);
        $invoiced->decreaseEstimated($order->getEstimatedCredit()) 
                 ->increaseCredit($order->getCredit());

        $this->_ensureOfInvoicedLimit($invoiced);
    }

    /**
     * @param Order $order
     * @return Supplier\Invoiced 
     */
    protected function _getSupplierInvoiced(Order $order)
    {
        $supplier = $order->getSupplier();
        $year     = (int) $order->getDate()->format("Y");
        if (null === ($invoiced = $supplier->getInvoiced($year))) {
            $invoiced = new Invoiced;
            $invoiced->setYear($year); 
            $supplier->addInvoiced($invoiced);
        }
        return $invoiced;
    }

    /**
     * @throws InvoicedLimitException
     * @param Supplier\Invoiced
     */
    protected function _ensureOfInvoicedLimit(Invoiced $invoiced)
    {
        $limit = $this->em->getRepository(Settings::class)
                          ->findOneBy(['type' => Settings::TYPE_SUPPLIER_INVOICED_LIMIT])
                          ->getValue();

        if (($ex = $invoiced->getTotal() - $limit) > 0) {
            throw new InvoicedLimitException(__("The order credit exceeds in :credit€ the supplier's annual billing limit established on :limit€", ['limit' => $limit, 'credit' => $ex]));
        }
    }
}
