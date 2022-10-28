<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SupplierEvent extends AbstractEvent implements UserAwareEvent
{
    /**
     * @inheritDoc
     */
    public function __construct(\App\Entities\Supplier $supplier, string $action)
    {
        parent::__construct($supplier, $action);
    }

    /**
     * @inheritDoc
     */
    public function getUserAwareEntity()
    {
        return $this->entity;
    }
}
