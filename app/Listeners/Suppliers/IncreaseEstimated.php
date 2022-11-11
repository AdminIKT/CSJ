<?php

namespace App\Listeners\Suppliers;

use App\Events\OrderEvent,
    App\Entities\Order,
    App\Entities\Settings,
    App\Entities\Supplier\Invoiced;
use App\Exceptions\Supplier\InvoicedLimitException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Doctrine\ORM\EntityManagerInterface;

class IncreaseEstimated
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
        if ($event->action !== OrderEvent::ACTION_STORE) {
            return;
        }

        $order    = $event->entity;
        $supplier = $order->getSupplier();

        //FIXME: Which date must be?
        $year     = (int) $order->getDate()->format("Y");
        if (null === ($invoiced = $supplier->getInvoiced($year))) {
            $invoiced = new Invoiced;
            $invoiced->setYear($year); 
            $supplier->addInvoiced($invoiced);
        }

        //FIXME: Throw Exception: Validate Estimated limit
        $invoiced->increaseEstimated($order->getEstimatedCredit()); 

        $limit = $this->em->getRepository(Settings::class)
                          ->findOneBy(['type' => Settings::TYPE_SUPPLIER_INVOICED_LIMIT])
                          ->getValue();

        if (($ex = $invoiced->getEstimated() - $limit) > 0) {
            throw new InvoicedLimitException(__("The order credit exceeds in :credit€ the supplier's annual billing limit established on :limit€", ['limit' => $limit, 'credit' => $ex]));
        }
    }
}
