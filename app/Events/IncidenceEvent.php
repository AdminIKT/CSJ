<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IncidenceEvent extends AbstractEvent implements UserAwareEvent
{
    const ACTION_CLOSE = 'close';

    /**
     * @var Order 
     */
    public $entity;

    /**
     * @inheritDoc
     */
    public function __construct(\App\Entities\Supplier\Incidence $e, string $a)
    {
        parent::__construct($e, $a);
    }

    /**
     * @inheritDoc
     */
    public function getUserAwareEntity()
    {
        return $this->entity;
    }
}
