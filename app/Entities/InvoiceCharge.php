<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * InvoiceCharge
 *
 * @ORM\Entity(repositoryClass="App\Repositories\InvoiceChargeRepository")
 */
class InvoiceCharge extends Charge 
{
    const TYPE_INVOICED = 5;

    const HZ_PREFIX = 'P';

    const HZ_PATTERN = "@^(".Charge::HZ_PREFIX."|".InvoiceCharge::HZ_PREFIX.")#.*@i";


    /**
     * @var Account 
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Order", inversedBy="invoiceCharges")
     */
    private $order;

    /**
     * @var int
     */
    private $type = self::TYPE_INVOICED;

    /**
     * Set order.
     *
     * @param Order $order
     *
     * @return Order
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order.
     *
     * @return Order 
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Get supplier.
     *
     * @return Supplier 
     */
    public function getSupplier()
    {
        return $this->getOrder()->getSupplier();
    }

    /**
     * @inheritDoc
     */
    public static function typeName($type)
    {
        switch ($type) {
            case self::TYPE_INVOICED:
                return trans("Invoice charge");
            default:
                return parent::typeName($type);
        }
    }
}
