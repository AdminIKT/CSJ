<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SupplierEvent implements UserAwareEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Movemment
     */
    public $entity;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(\App\Entities\Supplier $m)
    {
        $this->entity = $m;
    }

    /**
     * @inheritDoc
     */
    public function getUserAwareEntity()
    {
        return $this->entity;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
