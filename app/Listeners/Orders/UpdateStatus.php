<?php

namespace App\Listeners\Orders;

use App\Events\MovementEvent,
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
        $invoiceCharge->getOrder()
                      ->setStatus(Order::STATUS_PAID) 
                      ->setCredit($event->entity->getCredit())
                      ->setInvoice($event->entity->getInvoice());
    }
}
