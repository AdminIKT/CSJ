<?php

namespace App\Listeners\Accounts;

use App\Events\AbstractEvent,
    App\Events\MovementEvent,
    App\Events\OrderEvent,
    App\Entities\Charge,
    App\Entities\OrderCharge,
    App\Entities\Assignment,
    App\Exceptions\Account\InsufficientCreditException;
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
        switch (true) {
            case $event->action === OrderEvent::ACTION_STORE:
                $order->getSubaccount()
                      ->increaseCompromisedCredit($order->getEstimatedCredit())
                      ->getAccount()
                      ->increaseCompromisedCredit($order->getEstimatedCredit())
                      ;
                break;

            case $event->action ===  OrderEvent::ACTION_STATUS:
                if (!$order->isCancelled()) {
                    return;
                }
                if ($order->hasCredit()) {
                    $order->getSubaccount()
                          ->increaseCredit($order->getCredit())
                          ->getAccount()
                          ->increaseCredit($order->getCredit())
                          ;
                }
                else {
                    $order->getSubaccount()
                          ->decreaseCompromisedCredit($order->getEstimatedCredit())
                          ->getAccount()
                          ->decreaseCompromisedCredit($order->getEstimatedCredit())
                          ;
                }
                break;
        }
    }

    /**
     * @param  \App\Events\MovementEvent  $event
     * @return void
     */
    protected function _movementCredit(MovementEvent $event)
    {
        $movement   = $event->entity;
        $credit     = $movement->getCredit();
        $subaccount = $movement->getSubaccount();

        switch ($event->action) {
            case MovementEvent::ACTION_STORE:
                switch (true) {
                    case $movement instanceof Assignment:
                        $subaccount->increaseCredit($credit)
                                   ->getAccount()
                                   ->increaseCredit($credit);
                        break;
                    case $movement instanceof Charge:
                        if ($movement instanceof OrderCharge) {
                            $compromised = $movement->getOrder()->getEstimatedCredit();
                            $subaccount->decreaseCompromisedCredit($compromised)
                                       ->getAccount()
                                       ->decreaseCompromisedCredit($compromised);
                        }
                        if ($credit > $subaccount->getAvailableCredit()) {
                            throw new InsufficientCreditException($subaccount, $credit); 
                        }
                        $subaccount->decreaseCredit($credit)
                                   ->getAccount()
                                   ->decreaseCredit($credit);
                        break;
                    }
            break;
            case MovementEvent::ACTION_DESTROY:
                //TODO
                break;
        }
    }
}
