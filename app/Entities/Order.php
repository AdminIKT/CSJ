<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

use App\Entities\Order\Product,
    App\Entities\Supplier,
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
    const STATUS_CANCELLED              = -1;
    const STATUS_CREATED                = 0;
    const STATUS_RECEIVED               = 1;
    const STATUS_CHECKED_NOT_AGREED     = 2;
    const STATUS_CHECKED_PARTIAL_AGREED = 3;
    const STATUS_CHECKED_AGREED         = 4;
    const STATUS_CHECKED_INVOICED       = 5;
    const STATUS_PAID                   = 6;
    const STATUS_MOVED                  = 7;

    const RECEIVE_IN_DEPARTMENT = 0;
    const RECEIVE_IN_RECEPTION  = 1;

    const SEQUENCE_PATTERN = "@(^[\w]+)-(E|F|L|O)-?([\w]*)/([\d]{2})-([\d|-]+)@";

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    public $id;

    /**
     * @var string
     *
     * @ORM\Column(name="seq", type="string", unique=true)
     */
    public $sequence;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer", options={"default":0})
     */
    private $status = Order::STATUS_CREATED;

    /**
     * @var int
     *
     * @ORM\Column(name="r_in", type="integer", options={"default":0})
     */
    private $receiveIn = Order::RECEIVE_IN_DEPARTMENT;

    /**
     * @var string
     *
     * @ORM\Column(name="detail", type="string", nullable=true)
     */
    private $detail;

    /**
     * @var DateTime 
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var Subaccount 
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Subaccount", inversedBy="orders")
     */
    private $subaccount;

    /**
     * @var User 
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\User", inversedBy="orders")
     */
    private $user;

    /**
     * @var Supplier 
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Supplier", inversedBy="orders")
     */
    private $supplier;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entities\Order\Product", mappedBy="order", cascade={"persist","remove"})
     */
    private $products;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entities\OrderCharge", mappedBy="order")
     */
    private $orderCharges;

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
     * @ORM\Column(name="estimated", type="string", nullable=true)
     */
    private $estimated;

    /**
     * @var string
     *
     * @ORM\Column(name="drive_file", type="string", nullable=true)
     */
    private $fileId;

    /**
     * @var string
     *
     * @ORM\Column(name="drive_url", type="string", nullable=true)
     */
    private $fileUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="invoice", type="string", nullable=true)
     */
    private $invoice;

     /**
     * @var DateTime 
     *
     * @ORM\Column(name="invoiceDate", type="datetime", nullable=true)
     */
    private $invoiceDate;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entities\Action\OrderAction", mappedBy="entity", cascade={"persist","remove"})
     */
    private $actions;

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
        $this->orderCharges  = new \Doctrine\Common\Collections\ArrayCollection();
        $this->incidences = new \Doctrine\Common\Collections\ArrayCollection();
        $this->actions = new \Doctrine\Common\Collections\ArrayCollection();
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
    public function setDetail($detail = null)
    {
        $this->detail = $detail ? (string) $detail : null;

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
     * Set fileId.
     *
     * @param string $fileId
     *
     * @return Order 
     */
    public function setFileId($fileId = null)
    {
        $this->fileId = $fileId;

        return $this;
    }

    /**
     * Get fileId.
     *
     * @return string|null
     */
    public function getFileId()
    {
        return $this->fileId;
    }

    /**
     * Set folder.
     *
     * @param string $fileUrl
     *
     * @return Order 
     */
    public function setFileUrl($fileUrl)
    {
        $this->fileUrl = (string) $fileUrl;

        return $this;
    }

    /**
     * Get fileUrl.
     *
     * @return string
     */
    public function getFileUrl()
    {
        return $this->fileUrl;
    }

    /**
     * Set estimated.
     *
     * @param string $estimated
     *
     * @return Order 
     */
    public function setEstimated($estimated = null)
    {
        $this->estimated = $estimated;

        return $this;
    }

    /**
     * Get estimated.
     *
     * @return string|null
     */
    public function getEstimated()
    {
        return $this->estimated;
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
     * @return bool 
     */
    public function hasCredit()
    {
        return $this->credit !== null;
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
     * Get invoice date.
     *
     * @return DateTime
     */
    public function getInvoiceDate()
    {
        return $this->invoiceDate;
    }

    /**
     * Set invoice date.
     *
     * @param DateTime $invoiceDate
     *
     * @return OrderCharge
     */
    public function setInvoiceDate(\DateTime $invoiceDate)
    {
        $this->invoiceDate = $invoiceDate;

        return $this;
    }

    /**
     * Set receiveIn.
     *
     * @param int $receiveIn
     *
     * @return Order
     */
    public function setReceiveIn(int $receiveIn)
    {
        $this->receiveIn = $receiveIn;

        return $this;
    }

    /**
     * Get receiveIn.
     *
     * @return int
     */
    public function getReceiveIn()
    {
        return $this->receiveIn;
    }

    /**
     * Get string.
     *
     * @return string
     */
    public function getReceiveInName()
    {
        return self::receiveInName($this->getReceiveIn());
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
     * Get status.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
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
    public function isCancelled()
    {
        return $this->isStatus(self::STATUS_CANCELLED);
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
     * @return bool
     */
    public function isPayable()
    {
        return !($this->isStatus(self::STATUS_CANCELLED)
                || $this->isStatus(self::STATUS_PAID));
    }

    /**
     * Get color.
     *
     * @return string
     */
    public function getStatusColor()
    {
        return self::statusColor($this->getStatus());
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
     * Set Subaccount.
     *
     * @param Subaccount $subaccount
     *
     * @return Order
     */
    public function setSubaccount(Subaccount $subaccount)
    {
        $this->subaccount = $subaccount;

        return $this;
    }

    /**
     * Get subaccount.
     *
     * @return Subaccount 
     */
    public function getSubaccount()
    {
        return $this->subaccount;
    }

    /**
     * Get account.
     *
     * @return Account 
     */
    public function getAccount()
    {
        return $this->getSubaccount()->getAccount();
    }

    /**
     * Get area.
     *
     * @return Area 
     */
    public function getArea()
    {
        return $this->getSubaccount()->getArea();
    }

    /**
     * Add orderCharge.
     *
     * @param OrderCharge $orderCharge
     *
     * @return Order
     */
    public function addOrderCharge(OrderCharge $orderCharge)
    {
        $orderCharge->setOrder($this);
        $this->orderCharges[] = $orderCharge;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasOrderCharge()
    {
        return $this->getOrderCharges()->count() > 0;
    }

    /**
     * Get orderCharges.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrderCharges()
    {
        return $this->orderCharges;
    }

    /**
     * Get incidences.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIncidences()
    {
        return $this->incidences;
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
        $this->products->add($product);
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
        $product->setOrder(null);
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
     * @param Supplier $supplier
     * @return Order
     */
    public function setSupplier(Supplier $supplier)
    {
        $this->supplier = $supplier;
        return $this;
    }

    /**
     * @return Supplier
     */
    public function getSupplier()
    {
        return $this->supplier;
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
     * @return OrderAction[]
     */
    public function getActions()
    {
        return $this->actions;
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
     * @return string
     */
    public static function receiveInName($status) 
    {
        switch ($status) {
            case self::RECEIVE_IN_DEPARTMENT: return trans("Department");
            case self::RECEIVE_IN_RECEPTION:  return trans("Reception");
            default: return trans("Undefined");
        }
    }

    /**
     * @return string
     */
    public static function statusColor($status) 
    {
        switch ($status) {
            case self::STATUS_CREATED: 
                return "bg-dark";
            case self::STATUS_RECEIVED: 
                return "bg-secondary";
            case self::STATUS_CHECKED_NOT_AGREED: 
                return "bg-danger";
            case self::STATUS_CHECKED_PARTIAL_AGREED: 
                return "bg-warning text-dark";
            case self::STATUS_CHECKED_AGREED: 
                return "bg-success";
            case self::STATUS_CHECKED_INVOICED: 
                return "bg-info text-dark";
            case self::STATUS_PAID: 
                return "bg-primary";
            case self::STATUS_CANCELLED: 
                return "bg-cancelled";
            case self::STATUS_MOVED: 
            default: return "bg-light text-dark";
        }
    }

    /**
     * @return string
     */
    public static function statusName($status) 
    {
        switch ($status) {
            case self::STATUS_CANCELLED: 
                return trans("Cancelled");
            case self::STATUS_CREATED: 
                return trans("Pending");
            case self::STATUS_RECEIVED: 
                return trans("Received");
            case self::STATUS_CHECKED_NOT_AGREED: 
                return trans("Checked not agreed");
            case self::STATUS_CHECKED_PARTIAL_AGREED: 
                return trans("Checked partial agreed");
            case self::STATUS_CHECKED_AGREED: 
                return trans("Checked agreed");
            case self::STATUS_CHECKED_INVOICED: 
                return trans("Checked invoiced");
            case self::STATUS_PAID: 
                return trans("Paid");
            case self::STATUS_MOVED: 
                return trans("Moved");
            default: return trans("Undefined");
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
