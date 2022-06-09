<?php

namespace App\Entities\Supplier;

use Doctrine\ORM\Mapping as ORM;

use App\Entities\User,
    App\Entities\Order,
    App\Entities\UserAwareInterface,
    App\Entities\Supplier;

/**
 * Incidence 
 *
 * @ORM\Table(name="supplier_incidences")
 * @ORM\Entity(repositoryClass="App\Repositories\Supplier\IncidenceRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Incidence implements UserAwareInterface
{
    const STATUS_OPENED = 0;
    const STATUS_CLOSED = 1;

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
    private $status = Incidence::STATUS_OPENED;

    /**
     * @var string
     *
     * @ORM\Column(name="detail", type="string")
     */
    private $detail;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Supplier", inversedBy="incidences")
     */
    private $supplier;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Order", inversedBy="incidences")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", nullable=true)
     */
    private $order;

    /**
     * @var User 
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\User", inversedBy="incidences")
     */
    private $user;

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
     * Set status.
     *
     * @param int $status
     *
     * @return Incidence 
     */
    public function setStatus(int $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Is status.
     *
     * @return bool 
     */
    public function isStatus(int $status)
    {
        return $this->status === $status;
    }

    /**
     * Is closed.
     *
     * @return bool
     */
    public function isClosed()
    {
        return $this->isStatus(self::STATUS_CLOSED);
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
     * Get color.
     *
     * @return string
     */
    public function getStatusColor()
    {
        return self::statusColor($this->getStatus());
    }

    /**
     * Set order.
     *
     * @param Order $order
     *
     * @return Incidence
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
     * Set supplier.
     *
     * @param Supplier $supplier
     *
     * @return Incidence
     */
    public function setSupplier(Supplier $supplier)
    {
        $this->supplier = $supplier;

        return $this;
    }

    /**
     * Get supplier.
     *
     * @return Supplier
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * Set detail.
     *
     * @param string $detail
     *
     * @return Incidence
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
     * Set user.
     *
     * @param User $user
     *
     * @return Incidence
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
     * Set created.
     *
     * @param \Datetime $created
     *
     * @return Incidence
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
     * @return Incidence
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
    public static function statusName($status) 
    {
        switch ($status) {
            case self::STATUS_OPENED: 
                return trans("Opened");
            case self::STATUS_CLOSED: 
                return trans("Closed");
            default: return trans("Status undefined");
        }
    }
    /**
     * @return string
     */
    public static function statusColor($status) 
    {
        switch ($status) {
            case self::STATUS_OPENED: 
                return "bg-danger";
            case self::STATUS_CLOSED: 
                return "bg-success";
            default: return "bg-light text-dark";
        }
    }
}
