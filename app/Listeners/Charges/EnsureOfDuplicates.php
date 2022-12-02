<?php

namespace App\Listeners\Charges;

use App\Events\AbstractEvent,
    App\Events\MovementEvent,
    App\Entities\Charge,
    App\Entities\OrderCharge,
    App\Entities\InvoiceCharge;
use App\Exceptions\Charge\DuplicatedChargeException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Doctrine\ORM\EntityManagerInterface;

class EnsureOfDuplicates
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
     * @param  \App\Events\AbstractEvent  $event
     * @return void
     */
    public function handle(MovementEvent $event)
    {
        $movement = $event->entity;
        switch (true) {
            case $movement instanceof OrderCharge:
                $stored = $this->em
                               ->getRepository(OrderCharge::class)
                               ->findOneBy([
                                    'hzCode' => $movement->getHzCode(),
                                    'type'   => OrderCharge::TYPE_ORDER_INVOICED,
                                ]);
                break;
            case $movement instanceof InvoiceCharge:
                $stored = $this->em
                               ->getRepository(InvoiceCharge::class)
                               ->findOneBy([
                                    'hzCode' => $movement->getHzCode(),
                                    'type'   => InvoiceCharge::TYPE_INVOICED,
                                ]);
                break;
            case $movement instanceof Charge:
                //TODO
                break;
        }

        if (isset($stored) && $stored) {
            throw new DuplicatedChargeException(trans("charge of type :type with code :code allready stored in :date", [
                'code' => $stored->getHzCode(),
                'type' => $stored->getTypeName(),
                'date' => $stored->getCreated()->format('D, d M Y H:i'),
            ]));
        }
    }
}
