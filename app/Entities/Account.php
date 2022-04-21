<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Account
 *
 * @ORM\Table(name="accounts")
 * @ORM\Entity(repositoryClass="App\Repositories\AccountRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Account
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
     * @var float
     *
     * @ORM\Column(name="c_credit", type="float", options={"default":0})
     */
    private $compromisedCredit = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="credit", type="float", options={"default":0})
     */
    private $credit = 0;

    /**
     * @var Area
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Area", inversedBy="accounts")
     */
    private $area;

    /**
     * @var Department
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Department", inversedBy="accounts")
     */
    private $department;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entities\Order", mappedBy="account", cascade={"persist","merge"})
     * @ORM\OrderBy({"date" = "ASC"})
     */
    private $orders;

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
        $this->orders = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set Area.
     *
     * @param Area $area
     *
     * @return Account
     */
    public function setArea(Area $area)
    {
        $this->area = $area;
        return $this;
    }

    /**
     * @return Area
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set department.
     *
     * @param Department $department
     *
     * @return Account
     */
    public function setDepartment(Department $department)
    {
        $this->department = $department;
        return $this;
    }

    /**
     * @return Department
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param float $credit
     * @return Account
     */
    public function increaseCredit(float $credit)
    {
        $this->credit += $credit;
        return $this;
    }

    /**
     * @param float $credit
     * @return Account
     */
    public function decreaseCredit(float $credit)
    {
        $this->credit -= $credit;
        return $this;
    }

    /**
     * @param float $credit
     * @return Account
     */
    public function increaseCompromisedCredit(float $credit)
    {
        $this->compromisedCredit += $credit;
        return $this;
    }

    /**
     * @param float $credit
     * @return Account
     */
    public function decreaseCompromisedCredit(float $credit)
    {
        $this->compromisedCredit -= $credit;
        return $this;
    }

    /**
     * Set compromisedCredit.
     *
     * @param float $credit
     *
     * @return Account
     */
    public function setCompromisedCredit(float $credit)
    {
        $this->compromisedCredit = $credit;

        return $this;
    }

    /**
     * Get compromisedCredit.
     *
     * @return float
     */
    public function getCompromisedCredit()
    {
        return $this->compromisedCredit;
    }

    /**
     * Set credit.
     *
     * @param float $credit
     *
     * @return Account
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
     * Get availableCredit.
     *
     * @return float
     */
    public function getAvailableCredit()
    {
        return $this->credit - $this->getCompromisedCredit();
    }

    /**
     * Add Order.
     *
     * @param Order $order
     *
     * @return Account
     */
    public function addOrder(Order $order)
    {
        $order->setAccount($this);
        $this->orders[] = $order;
        return $this;
    }

    /**
     * Get orders.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getArea()->getName();
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->getArea()->getType();
    }

    /**
     * Get acronym.
     *
     * @return string
     */
    public function getAcronym()
    {
        return $this->getArea()->getAcronym();
    }

    /**
     * Get lcode.
     *
     * @return string
     */
    public function getLCode()
    {
        return $this->getArea()->getLCode();
    }

    /**
     * Set created.
     *
     * @param \Datetime $created
     *
     * @return Account
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
     * @return Account
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
     * @return string
     */
    public function getTypeName()
    {
        return $this->getArea()->getTypeName();
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
    public function __toString()
    {
        return $this->getName();
    }
}
