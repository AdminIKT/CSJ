<?php

namespace App\Listeners\Suppliers;

use App\Events\OrderEvent,
    App\Entities\Settings,
    App\Entities\Supplier,
    App\Entities\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Doctrine\ORM\EntityManagerInterface;

class RecommendableSupplier 
{
    /**
     * @EntityManagerInterface
     */ 
    protected $em;

    /**
     * Create the event listener.
     *
     * @param EntityManagerInterface $em
     * @return void
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
        if (!($event->action === OrderEvent::ACTION_STATUS && 
            $event->entity->isStatus(Order::STATUS_CHECKED_AGREED))) {
            return;
        }

        $supplier = $event->entity->getSupplier();
        $supplier->increaseOrderCount();
        if ($supplier->isRecommendable()) {
            return;
        }

        $limit = $this->em->getRepository(Settings::class)
                    ->findOneBy(['type' => Settings::TYPE_SUPPLIER_RECOMMENDABLE_LIMIT])
                    ->getValue();

        if ($supplier->getOrderCount() >= $limit) {
            $supplier->setStatus(Supplier::STATUS_RECOMMENDABLE);
        }
    }
}
