<?php

namespace App\Listeners\Areas;

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
        $area       = $assignment->getArea();
        switch ($event->action) {
            case AssignmentEvent::ACTION_STORE:
                $area->increaseCredit($assignment->getCredit());
                break;
            case AssignmentEvent::ACTION_DESTROY:
                $area->decreaseCredit($assignment->getCredit());
                break;
        }
    }
}
