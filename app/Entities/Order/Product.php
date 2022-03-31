<?php

namespace App\Entities\Order;

use Doctrine\ORM\Mapping as ORM;

use App\Entities\Order,
    App\Entities\Supplier;

/**
 * Product
 *
 * @ORM\Table(name="order_products")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 */
class Product
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
     * @ORM\Column(name="credit", type="float", options={"default":0})
     */
    private $credit = 0;

    /**
     * @var Order
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Order", inversedBy="products")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", nullable=false)
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
     * Set credit.
     *
     * @param float $credit
     *
     * @return Product
     */
    public function setCredit(float $credit)
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     * Get credit.
     *
     * @return float
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * Set order.
     *
     * @param Order $order
     *
     * @return Product
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
    public function fromArray(array $values) 
    {
        $this->setDetail($values['detail'])
             ->setCount($values['count'])
             ->setTotal($values['total'])
         ;

        return $this;
    }
}
