<?php

namespace App\Listeners\Users;

use App\Events\AbstractEvent,
    App\Events\UserAwareEvent;
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
     * @param  \App\Events\AbstractEvent  $event
     * @return void
     */
    public function handle(AbstractEvent $event)
    {
        if (!$event instanceof UserAwareEvent) {
            return;
        }

        $entity = $event->getUserAwareEntity();

        if (!($entity->getUser() === null && 
            $event->getAction() === AbstractEvent::ACTION_STORE)) {
            return;
        }

        $entity->setUser(Auth::user());
        $this->em->flush();
    }
}
