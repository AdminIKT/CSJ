<?php

namespace App\Listeners\Orders;

use App\Events\OrderEvent,
    App\Entities\Action\OrderAction,
    App\Entities\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Doctrine\ORM\EntityManagerInterface;

class ActionInjection 
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
     * @param  OrderEvent  $event
     * @return void
     */
    public function handle(OrderEvent $event)
    {
        //if (!$event->action === OrderEvent::ACTION_STATUS) {
        //    return;
        //}

        $action = new OrderAction($event->entity);
        $action->setType(OrderAction::TYPE_STATUS)
            ->setAction($event->entity->getStatus())
            ->setUser(Auth::user())
            ->setCreated(new \DateTime())
            ;

        $this->em->persist($action);
    }
}
