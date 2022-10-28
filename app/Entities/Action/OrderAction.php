<?php

namespace App\Entities\Action;

use Doctrine\ORM\Mapping as ORM;
use App\Entities\Action;
use App\Entities\Order;

/**
 * Charge 
 *
 * @ORM\Entity
 */
class OrderAction extends Action 
{
    const TYPE_STATUS = 0;

    /**
     * @var Order 
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Order", inversedBy="actions")
     */
    private $entity;

    /**
     * @inheritDoc
     */
    public function __construct(Order $entity)
    {
        parent::__construct($entity);
    }

    /**
     * @param Order $order
     * @return OrderAction
     */
    protected function setOrder(Order $order)
    {
        $this->setEntity($order);
        return $this;
    }

    /**
     * @return Order
     */
    protected function getOrder()
    {
        return $this->getEntity();
    }

    /**
     * @inhertiDoc
     */
    public function typeName($type)
    {
        switch ($type) {
            case self::TYPE_STATUS:
                switch ($this->getAction()) {
                    case Order::STATUS_CREATED:
                        return 'New order';
                }
                return 'State changed';
            return 'Undefined';
        }
    }
}
