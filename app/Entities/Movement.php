<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Movement
 *
 * @ORM\Table(name="movements")
 * @ORM\Entity(repositoryClass="App\Repositories\MovementRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Movement
{
    /**
     * - Cargos por factura
     * - Cobros en caja
     * etc
     */
    const TYPE_INVOICED = 0;
    const TYPE_CASH     = 1;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="type", type="integer", options={"default":0})
     */
    private $type = Movement::TYPE_INVOICED;

    /**
     * @var Area 
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Order", inversedBy="movements")
     */
    private $order;

    /**
     * @var float
     *
     * @ORM\Column(name="credit", type="float", options={"default":0})
     */
    private $credit = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="invoice", type="string")
     */
    private $invoice;

    /**
     * @var string
     *
     * @ORM\Column(name="detail", type="string")
     */
    private $detail;

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
     * Constructor
     */
    public function __construct()
    {
    }

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
     * @return Movement
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
     * Get invoice.
     *
     * @return string
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * Set invoice.
     *
     * @param string $invoice
     *
     * @return Movement
     */
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * Set credit.
     *
     * @param float $credit
     *
     * @return Movement
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
     * Set type.
     *
     * @param int $type
     *
     * @return Movement
     */
    public function setType(int $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get type name.
     *
     * @return string
     */
    public function getTypeName()
    {
        return self::typeName($this->getType());
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
     * Get area.
     *
     * @return Area 
     */
    public function getArea()
    {
        return $this->getOrder()->getArea();
    }

    /**
     * Set created.
     *
     * @param \Datetime $created
     *
     * @return Movement
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
     * @return Movement
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
     * @return string
     */
    public static function typeName($type) 
    {
        switch ($type) {
            case self::TYPE_INVOICED: 
                return trans("Cargo por factura");
            case self::TYPE_CASH: 
                return trans("Cobro en caja");
            return trans("Undefined");
        }
    }
}
