<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderCharge
 *
 * @ORM\Entity(repositoryClass="App\Repositories\OrderChargeRepository")
 */
class OrderCharge extends InvoiceCharge 
{
    const TYPE_ORDER_INVOICED = 6;

    const HZ_PREFIX = 'P';

    const HZ_PATTERN = "@^(".HzCharge::HZ_PREFIX."|".InvoiceCharge::HZ_PREFIX."|".OrderCharge::HZ_PREFIX.")#(.*)@i";

    /**
     * @var Account 
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Order", inversedBy="orderCharges")
     */
    private $order;

    /**
     * @inheritDoc
     */
    public function __construct()
    {
        $this->type = self::TYPE_ORDER_INVOICED;
    }

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
            case self::TYPE_ORDER_INVOICED:
                return trans("Order charge");
            default:
                return parent::typeName($type);
        }
    }
}
