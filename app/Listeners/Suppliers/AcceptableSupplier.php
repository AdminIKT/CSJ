<?php

namespace App\Listeners\Suppliers;

use App\Events\IncidenceEvent,
    App\Entities\Settings,
    App\Entities\Supplier,
    App\Entities\Supplier\Incidence;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Doctrine\ORM\EntityManagerInterface;

class AcceptableSupplier 
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
     * @param  IncidenceEvent  $event
     * @return void
     */
    public function handle(IncidenceEvent $event)
    {
        $incidence = $event->entity;
        $supplier  = $incidence->getSupplier();

        $incidence->isClosed() ?
            $supplier->decreaseIncidenceCount() :
            $supplier->increaseIncidenceCount();

        $limit = $this->em->getRepository(Settings::class)
                          ->findOneBy(['type' => Settings::TYPE_SUPPLIER_NO_ACCEPTABLE_LIMIT])
                          ->getValue();

        if ($supplier->getIncidenceCount() >= $limit) {
            $supplier->setStatus(Supplier::STATUS_NO_ACCEPTABLE);
        }
        elseif ($supplier->isNoAcceptable()) {
            $limit = $this->em->getRepository(Settings::class)
                        ->findOneBy(['type' => Settings::TYPE_SUPPLIER_RECOMMENDABLE_LIMIT])
                        ->getValue();
            $supplier->getOrderCount() >= $limit ?
                $supplier->setStatus(Supplier::STATUS_RECOMMENDABLE) :
                $supplier->setStatus(Supplier::STATUS_VALIDATED);
        }
    }
}
