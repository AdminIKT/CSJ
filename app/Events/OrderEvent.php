<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderEvent extends AbstractEvent implements UserAwareEvent
{
    const ACTION_STATUS  = 'status';
    const ACTION_INVOICE = 'invoice';

    /**
     * @inheritDoc
     */
    public function __construct(\App\Entities\Order $order, string $action)
    {
        parent::__construct($order, $action);
    }

    /**
     * @inheritDoc
     */
    public function getUserAwareEntity()
    {
        return $this->entity;
    }
}
