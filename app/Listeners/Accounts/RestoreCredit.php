<?php

namespace App\Listeners\Accounts;

use App\Events\MovementEvent,
    App\Entities\Movement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RestoreCredit
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
            $order = $movement->getOrder();
            $order->getSubaccount()
                  ->decreaseCompromisedCredit($order->getEstimatedCredit())
                  ->decreaseCredit($order->getCredit())
                  ->getAccount()
                  ->decreaseCompromisedCredit($order->getEstimatedCredit())
                  ->decreaseCredit($order->getCredit());
        }
    }
}
