<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class AbstractEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    const ACTION_STORE  = 'store';
    const ACTION_UPDATE = 'update';
    const ACTION_DELETE = 'destroy';

    /**
     * @var mixed
     */
    public $entity;

    /**
     * @var string
     */
    public $action;

    /**
     * Create a new event instance.
     *
     * @param mixed $entity
     * @param string $action
     * @return void
     */
    public function __construct($entity, string $action)
    {
        $this->entity = $entity;
        $this->action = (string) $action;
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
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
