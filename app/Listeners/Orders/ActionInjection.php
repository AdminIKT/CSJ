<?php

namespace App\Listeners\Orders;

use App\Events\OrderEvent,
    App\Entities\Order,
    App\Entities\Action\OrderAction,
    App\Entities\Account\DriveFile;
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
        $action = new OrderAction;
        $action->setOrder($event->entity)
            ->setUser(Auth::user())
            ->setCreated(new \DateTime())
            ->setAction($event->entity->getStatus());

        switch ($event->action) {

            case OrderEvent::ACTION_INVOICE:
                $action->setType(OrderAction::TYPE_INVOICE)
                       ->setDetail($event->entity->getFileUrl(DriveFile::TYPE_INVOICE));
                break;

            default:
                $action->setType(OrderAction::TYPE_STATUS);
        }

        $this->em->persist($action);
    }
}
