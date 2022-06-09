<?php

namespace App\Listeners\Suppliers;

use App\Events\MovementEvent,
    App\Entities\Settings,
    App\Entities\Movement,
    App\Entities\InvoiceCharge;
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
     * @param  MovementEvent  $event
     * @return void
     */
    public function handle(MovementEvent $event)
    {
        $movement = $event->entity;
        if (!($movement instanceof InvoiceCharge && 
             $event->action === MovementEvent::ACTION_STORE
        )) {
            return;
        }

        $supplier = $movement->getSupplier();

        $collection = $this->em
                           ->getRepository(InvoiceCharge::class)
                           ->search([
                            'supplier' => $supplier->getId(),
                           ]);

        $limit = $this->em->getRepository(Settings::class)
                          ->findOneBy(['type' => Settings::TYPE_RECOMMENDED_SUPPLIER_LIMIT])
                          ->getValue();

        dd($limit, $collection->total());
    }
}
