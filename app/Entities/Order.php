<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

use App\Entities\Order\Product,
    App\Entities\Supplier\Incidence;

/**
 * Order
 *
 * @ORM\Table(name="orders")
 * @ORM\Entity(repositoryClass="App\Repositories\OrderRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Order implements UserAwareInterface, \JsonSerializable
{
    const STATUS_CREATED  = 0;
    const STATUS_PAID     = 1;
    const STATUS_RECEIVED = 2;
    const STATUS_MOVED    = 3;

    const SEQUENCE_PATTERN = "@(^[\w]+)-(E|F|L)-?([\d]*)/([\d]{2})-([\d|-]+)@";

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
     * @ORM\Column(name="status", type="integer", options={"default":0})
     */
    private $status = Order::STATUS_CREATED;

    /**
     * @var string
     *
     * @ORM\Column(name="detail", type="string", nullable=true)
     */
    private $detail;

    /**
     * @var string
     *
     * @ORM\Column(name="seq", type="string")
     */
    private $sequence;

    /**
     * @var DateTime 
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var Area 
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Area", inversedBy="orders")
     */
    private $area;

    /**
     * @var User 
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\User", inversedBy="orders")
     */
    private $user;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entities\Order\Product", mappedBy="order", cascade={"persist","remove"})
     */
    private $products;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entities\Movement", mappedBy="order")
     */
    private $movements;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entities\Supplier\Incidence", mappedBy="order")
     */
    private $incidences;

    /**
     * @var float
     *
     * @ORM\Column(name="s_credit", type="float", options={"default":0})
     */
    private $estimatedCredit = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="credit", type="float", nullable=true)
     */
    private $credit;

    /**
     * @var string
     *
     * @ORM\Column(name="invoice", type="string", nullable=true)
     */
    private $invoice;

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
        $this->products   = new \Doctrine\Common\Collections\ArrayCollection();
        $this->movements  = new \Doctrine\Common\Collections\ArrayCollection();
        $this->incidences = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Get sequence.
     *
     * @return string
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * Set sequence.
     *
     * @param string $sequence
     *
     * @return Order
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;

        return $this;
    }

    /**
     * Set detail.
     *
     * @param string $detail
     *
     * @return Order
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
     * Set estimatedCredit.
     *
     * @param float $credit
     *
     * @return Order 
     */
    public function setEstimatedCredit(float $credit)
    {
        $this->estimatedCredit = $credit;

        return $this;
    }

    /**
     * Get estimatedCredit.
     *
     * @return float
     */
    public function getEstimatedCredit()
    {
        return $this->estimatedCredit;
    }

    /**
     * Set credit.
     *
     * @param float|null $credit
     *
     * @return Order
     */
    public function setCredit(float $credit = null)
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
     * @return Order 
     */
    public function setInvoice($invoice = null)
    {
        $this->invoice = $invoice;

        return $this;
    }


    /**
     * Set status.
     *
     * @param int $status
     *
     * @return Order
     */
    public function setStatus(int $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Is status.
     *
     * @param int $status
     *
     * @return bool
     */
    public function isStatus(int $status)
    {
        return $this->getStatus() === $status;
    }

    /**
     * @return bool
     */
    public function isPending()
    {
        return $this->isStatus(self::STATUS_CREATED);
    }

    /**
     * @return bool
     */
    public function isPaid()
    {
        return $this->isStatus(self::STATUS_PAID);
    }

    /**
     * Get status.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get status.
     *
     * @return string
     */
    public function getStatusName()
    {
        return self::statusName($this->getStatus());
    }

    /**
     * Set user.
     *
     * @param User $user
     *
     * @return Order
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set area.
     *
     * @param Area $area
     *
     * @return Order
     */
    public function setArea(Area $area)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area.
     *
     * @return Area 
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Add movement.
     *
     * @param Movement $movement
     *
     * @return Order
     */
    public function addMovement(Movement $movement)
    {
        $movement->setOrder($this);
        $this->movements[] = $movement;
        return $this;
    }

    /**
     * Get movements.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMovements()
    {
        return $this->movements;
    }

    /**
     * Add product.
     *
     * @param Product $product
     *
     * @return Order
     */
    public function addProduct(Product $product)
    {
        $product->setOrder($this);
        $this->products[] = $product;
        return $this;
    }

    /**
     * Remove product.
     *
     * @param Product $product
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProduct(Product $product)
    {
        return $this->products->removeElement($product);
    }

    /**
     * Get products.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Set date.
     *
     * @param \Datetime $date
     *
     * @return Order
     */
    public function setDate(\Datetime $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \Datetime
     */
    public function getDate()
    {
        return $this->date;
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
     * @return Order
     */
    public function fromArray(array $values) 
    {
        $this->setDetail($values['detail']);

        if (isset($values['products']) && is_array($values['products'])) {
            foreach ($values['products'] as $_product) {
                $product = new Product;
                $product->fromArray($_product);
                $this->addProduct($product);
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public static function statusName($status) 
    {
        switch ($status) {
            case self::STATUS_CREATED: return "Pending";
            case self::STATUS_PAID: return "Paid";
            case self::STATUS_RECEIVED: return "Recibido";
            case self::STATUS_MOVED: return "Moved";
            return "Undefined";
        }
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'sequence' => $this->getSequence(),
            'date' => $this->getDate()->format("Y-m-d"),
        ];
    }
}
