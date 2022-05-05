<?php

namespace App\Listeners\Accounts;

use App\Events\MovementEvent,
    App\Entities\Charge,
    App\Entities\InvoiceCharge,
    App\Entities\Assignment;
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
        $movement   = $event->entity;
        $subaccount = $movement->getSubaccount();

        switch ($event->action) {
            case MovementEvent::ACTION_STORE:
                switch (true) {
                    case $movement instanceof InvoiceCharge:
                        $function = "decreaseCredit";
                        $subaccount->decreaseCompromisedCredit($movement->getCredit())
                                   ->getAccount()
                                   ->decreaseCompromisedCredit($movement->getCredit());
                        break;
                    case $movement instanceof Charge:
                        $function = "decreaseCredit";
                        break;
                    case $movement instanceof Assignment:
                        $function = "increaseCredit";
                        break;
                }
                break;
            case MovementEvent::ACTION_DESTROY:
                switch (true) {
                    case $movement instanceof Assignment:
                        $function = "decreaseCredit";
                        break;
                    case $movement instanceof Charge:
                        $function = "increaseCredit";
                        break;
                }
                break;

        }
        $subaccount->$function($movement->getCredit())
                   ->getAccount()
                   ->$function($movement->getCredit());
    }
}
