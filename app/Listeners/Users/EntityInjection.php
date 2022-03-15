<?php

namespace App\Listeners\Users;

use App\Events\OrderEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\Facades\Auth;

class EntityInjection
{
    /**
     * @EntityManagerInterface
     */ 
    protected $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\OrderEvent  $event
     * @return void
     */
    public function handle(OrderEvent $event)
    {
        $order = $event->entity;
        $order->setUser(Auth::user());
        $this->em->flush();
    }
}
