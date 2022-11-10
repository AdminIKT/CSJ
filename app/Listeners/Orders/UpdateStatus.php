<?php

namespace App\Listeners\Orders;

use App\Events\OrderEvent,
    App\Events\MovementEvent,
    App\Entities\InvoiceCharge,
    App\Entities\Order;
use App\Exceptions\Order\InvalidStatusException;
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
        $charge = $event->entity;
        if (!($charge instanceof InvoiceCharge && 
             $event->action === MovementEvent::ACTION_STORE
        )) {
            return;
        }

        $order = $charge->getOrder();
        if ($order->isPaid()) {
            throw new InvalidStatusException(__("Order status is :status", ['status' => $order->getStatusName()]));
        }

        $order->setStatus(Order::STATUS_PAID) 
              ->setCredit($charge->getCredit())
              ->setInvoice($charge->getInvoice())
              ->setInvoiceDate($charge->getInvoiceDate());

        OrderEvent::dispatch($order, OrderEvent::ACTION_STATUS);
    }
}
