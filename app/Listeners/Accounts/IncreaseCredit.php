<?php

namespace App\Listeners\Accounts;

use App\Events\AssignmentEvent,
    App\Entities\Assignment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class IncreaseCredit
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
     * @param  \App\Events\AssignmentEvent  $event
     * @return void
     */
    public function handle(AssignmentEvent $event)
    {
        $assignment = $event->entity;
        $subaccount = $assignment->getSubaccount();
        switch ($event->action) {
            case AssignmentEvent::ACTION_STORE:
                $subaccount->increaseCredit($assignment->getCredit())
                           ->getAccount()
                           ->increaseCredit($assignment->getCredit())
                           ;
                break;
            case AssignmentEvent::ACTION_DESTROY:
                $subaccount->decreaseCredit($assignment->getCredit())
                           ->getAccount()
                           ->decreaseCredit($assignment->getCredit())
                           ;
                break;
        }
    }
}
