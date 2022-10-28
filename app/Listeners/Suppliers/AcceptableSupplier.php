<?php

namespace App\Listeners\Suppliers;

use App\Events\IncidenceEvent,
    App\Entities\Settings,
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

        $collection = $this->em->getRepository(Incidence::class)
                           ->search($supplier, Incidence::STATUS_OPENED);

        $limit = $this->em->getRepository(Settings::class)
                          ->findOneBy(['type' => Settings::TYPE_ACCEPTED_SUPPLIER_LIMIT])
                          ->getValue();

        //TODO: use Actions instead
        if ($incidence->isClosed()) {
        }
        else {
        }
    }
}
