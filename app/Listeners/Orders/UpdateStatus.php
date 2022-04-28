<?php

namespace App\Listeners\Orders;

use App\Events\InvoiceChargeEvent,
    App\Entities\InvoiceCharge,
    App\Entities\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateStatus
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
        if ($invoiceCharge->getType() === InvoiceCharge::TYPE_INVOICED) {
            $invoiceCharge->getOrder()
                     ->setStatus(Order::STATUS_PAID) 
                     ->setCredit($invoiceCharge->getCredit())
                     ->setInvoice($invoiceCharge->getInvoice());
        }
    }
}
