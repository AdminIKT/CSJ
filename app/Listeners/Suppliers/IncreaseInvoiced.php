<?php

namespace App\Listeners\Suppliers;

use App\Events\InvoiceChargeEvent,
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
     * @param  \App\Events\InvoiceChargeEvent  $event
     * @return void
     */
    public function handle(InvoiceChargeEvent $event)
    {
        $invoiceCharge = $event->entity;
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
