<?php

namespace App\Entities\Action;

use Doctrine\ORM\Mapping as ORM;
use App\Entities\Action;
use App\Entities\Order;

/**
 * Charge 
 *
 * @ORM\Entity(repositoryClass="App\Repositories\Action\OrderRepository")
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
     * @param Order $order
     * @return OrderAction
     */
    public function setOrder(Order $order)
    {
        $this->setEntity($order);
        return $this;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->getEntity();
    }

    /**
     * @inheritDoc
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getEntity() 
    {
        return $this->entity;
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
