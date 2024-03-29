<?php

namespace App\Entities\Order;

use Doctrine\ORM\Mapping as ORM;
use Illuminate\Contracts\Support\Arrayable;

use App\Entities\Order,
    App\Entities\Supplier;

/**
 * Product
 *
 * @ORM\Table(name="order_products")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 */
class Product implements Arrayable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="detail", type="string")
     */
    private $detail;

    /**
     * @var int
     *
     * @ORM\Column(name="units", type="integer", options={"default":0})
     */
    private $units = 0;

    /**
     * @var Order
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Order", inversedBy="products")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", nullable=true)
     */
    private $order;

    /**
     * @var DateTime 
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var DateTime 
     *
     * @ORM\Column(name="updated", type="datetime")
     */
    private $updated;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set detail.
     *
     * @param string $detail
     *
     * @return Product
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * Get detail.
     *
     * @return string
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * Set units.
     *
     * @param float $units
     *
     * @return Product
     */
    public function setUnits(float $units)
    {
        $this->units = $units;

        return $this;
    }

    /**
     * Get units.
     *
     * @return float
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * Set order.
     *
     * @param Order|null $order
     *
     * @return Product
     */
    public function setOrder(Order $order = null)
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
     * Set created.
     *
     * @param \Datetime $created
     *
     * @return Order
     */
    public function setCreated(\Datetime $created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created.
     *
     * @return \Datetime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated.
     *
     * @param \Datetime $updated
     *
     * @return Order
     */
    public function setUpdated(\Datetime $updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated.
     *
     * @return \Datetime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateTimestamps()
    {
        $this->setUpdated(new \DateTime('now'));
        if ($this->getCreated() === null) {
            $this->setCreated(new \DateTime('now'));
        }
    }

    /**
     * @param Array $values
     * @return product 
     */
    static public function fromArray(array $values) 
    {
        $product = new static();
        $product->setDetail($values['detail'])
             ->setUnits($values['units'])
             //->setTotal($values['total'])
         ;

        return $product;
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return [
            'id'     => $this->getId(),
            'detail' => $this->getDetail(),
            'units'  => $this->getUnits(),
        ];
    }
}
