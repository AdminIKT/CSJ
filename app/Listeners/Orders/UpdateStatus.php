<?php

namespace App\Listeners\Orders;

use App\Events\MovementEvent,
    App\Entities\Movement,
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
     * @param  \App\Events\MovementEvent  $event
     * @return void
     */
    public function handle(MovementEvent $event)
    {
        $movement = $event->entity;
        if ($movement->getType() === Movement::TYPE_INVOICED) {
            $movement->getOrder()
                     ->setStatus(Order::STATUS_PAID) 
                     ->setCredit($movement->getCredit())
                     ->setInvoice($movement->getInvoice());
        }
    }
}
