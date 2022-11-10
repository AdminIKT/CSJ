<?php

namespace App\Listeners\Accounts;

use App\Events\MovementEvent,
    App\Entities\Charge,
    App\Entities\InvoiceCharge,
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
     * @param  \App\Events\MovementEvent  $event
     * @return void
     */
    public function handle(MovementEvent $event)
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
                        if ($movement instanceof InvoiceCharge) {
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


        /*
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
        */
    }
}
