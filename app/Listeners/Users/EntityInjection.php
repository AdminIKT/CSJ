<?php

namespace App\Listeners\Users;

use App\Events\UserAwareEvent;
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
    public function handle(UserAwareEvent $event)
    {
        $entity = $event->getUserAwareEntity();
        $entity->setUser(Auth::user());
        $this->em->flush();
    }
}
