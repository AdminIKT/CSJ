<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AssignmentEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    const ACTION_STORE   = 'store';
    const ACTION_DESTROY = 'destroy';

    /**
     * @var Movemment
     */
    public $entity;

    /**
     * @var string 
     */
    public $action;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(\App\Entities\Movement $entity, string $action)
    {
        $this->entity = $entity;
        $this->action = $action;
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
