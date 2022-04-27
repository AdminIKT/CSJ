<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subaccount
 *
 * @ORM\Table(name="subaccounts")
 * @ORM\Entity(repositoryClass="App\Repositories\SubaccountRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Subaccount
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
     * @var Account
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Account", inversedBy="subaccounts")
     */
    private $account;

    /**
     * @var Area
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Area", inversedBy="subaccounts")
     */
    private $area;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entities\Order", mappedBy="subaccount", cascade={"persist","merge"})
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
     * Set Account.
     *
     * @param Account $account
     *
     * @return Subaccount
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;
        return $this;
    }

    /**
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set area.
     *
     * @param Area $area
     *
     * @return Subaccount
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
     * @param float $credit
     * @return Subaccount
     */
    public function increaseCredit(float $credit)
    {
        $this->credit += $credit;
        return $this;
    }

    /**
     * @param float $credit
     * @return Subaccount
     */
    public function decreaseCredit(float $credit)
    {
        $this->credit -= $credit;
        return $this;
    }

    /**
     * @param float $credit
     * @return Subaccount
     */
    public function increaseCompromisedCredit(float $credit)
    {
        $this->compromisedCredit += $credit;
        return $this;
    }

    /**
     * @param float $credit
     * @return Subaccount
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
     * @return Subaccount
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
     * @return Subaccount
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
     * @return Subaccount
     */
    public function addOrder(Order $order)
    {
        $order->setSubaccount($this);
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
        return $this->getAccount()->getName();
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->getAccount()->getType();
    }

    /**
     * Get acronym.
     *
     * @return string
     */
    public function getAcronym()
    {
        return $this->getAccount()->getAcronym();
    }

    /**
     * Get lcode.
     *
     * @return string
     */
    public function getLCode()
    {
        return $this->getAccount()->getLCode();
    }

    /**
     * Get serial.
     *
     * @return string
     */
    public function getSerial()
    {
        return $this->getAccount()->getSerial();
    }

    /**
     * Set created.
     *
     * @param \Datetime $created
     *
     * @return Subaccount
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
     * @return Subaccount
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
        return $this->getAccount()->getTypeName();
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
