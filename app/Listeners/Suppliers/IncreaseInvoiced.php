<?php

namespace App\Listeners\Suppliers;

use App\Events\MovementEvent,
    App\Entities\InvoiceCharge,
    App\Entities\Supplier\Invoiced;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class IncreaseInvoiced
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MovementEvent  $event
     * @return void
     */
    public function handle(MovementEvent $event)
    {
        $invoiceCharge = $event->entity;
        if (!($invoiceCharge instanceof InvoiceCharge && 
             $event->action === MovementEvent::ACTION_STORE
        )) {
            return;
        }

        $order    = $invoiceCharge->getOrder();
        $supplier = $order->getSupplier();

        //FIXME: Which date must be?
        $year     = (int) $order->getDate()->format("Y");
        if (null === ($invoiced = $supplier->getInvoiced($year))) {
            $invoiced = new Invoiced;
            $invoiced->setYear($year); 
            $supplier->addInvoiced($invoiced);
        }

        //FIXME: Validate Invoiced limit?
        $invoiced->increaseCredit($order->getCredit()); 
    }
}
