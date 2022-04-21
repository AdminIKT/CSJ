<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Area
 *
 * @ORM\Table(name="areas")
 * @ORM\Entity(repositoryClass="App\Repositories\AreaRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Area
{
    const TYPE_EQUIPAMIENTO = "E";
    const TYPE_FUNGIBLE     = "F";
    const TYPE_LANBIDE      = "L";


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
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="acronym", type="string", length=3)
     */
    private $acronym;

    /**
     * @var string
     *
     * @ORM\Column(name="lcode", type="string", nullable=true)
     */
    private $lcode;

    /**
     * @var char
     *
     * @ORM\Column(name="type", type="string", length=1)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="detail", type="string", nullable=true)
     */
    private $detail;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Subaccount", mappedBy="area", cascade={"persist", "remove"})
     */
    private $subaccounts;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="User", inversedBy="areas")
     * @ORM\JoinTable(name="area_user_rel", 
     *  joinColumns={@ORM\JoinColumn(name="area_id", referencedColumnName="id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *  )
     */
    private $users;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entities\Order", mappedBy="area", cascade={"persist","merge"})
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
        $this->subaccounts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->orders   = new \Doctrine\Common\Collections\ArrayCollection();
        $this->users    = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name.
     *
     * @param string $name
     *
     * @return Area
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return Area
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set detail.
     *
     * @param string $detail
     *
     * @return Area 
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
     * Set acronym.
     *
     * @param string $acronym
     *
     * @return Area
     */
    public function setAcronym($acronym)
    {
        $this->acronym = $acronym;

        return $this;
    }

    /**
     * Get acronym.
     *
     * @return string
     */
    public function getAcronym()
    {
        return $this->acronym;
    }

    /**
     * Set lcode.
     *
     * @param string $lcode
     *
     * @return Area
     */
    public function setLCode($lcode = null)
    {
        $this->lcode = $lcode;

        return $this;
    }

    /**
     * Get lcode.
     *
     * @return string
     */
    public function getLCode()
    {
        return $this->lcode;
    }

    /**
     * @return string
     */
    public function getSerial()
    {
        $serial = [
            $this->getAcronym(),
            $this->getType(),
        ];

        if (null !== ($code = $this->getLCode())) {
            $serial[] = $code;
        }

        return implode("-", $serial);
    }

    /**
     * @param float $credit
     * @return Area
     */
    public function increaseCredit(float $credit)
    {
        $this->credit += $credit;
        return $this;
    }

    /**
     * @param float $credit
     * @return Area
     */
    public function decreaseCredit(float $credit)
    {
        $this->credit -= $credit;
        return $this;
    }

    /**
     * @param float $credit
     * @return Area
     */
    public function increaseCompromisedCredit(float $credit)
    {
        $this->compromisedCredit += $credit;
        return $this;
    }

    /**
     * @param float $credit
     * @return Area
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
     * @return Area
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
     * @return Area
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
     * Add User.
     *
     * @param \User $user
     *
     * @return Area
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;
        return $this;
    }

    /**
     * Remove user.
     *
     * @param \User $user
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeUser(User $user)
    {
        return $this->users->removeElement($user);
    }

    /**
     * Get users.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add Subaccount.
     *
     * @param \Subaccount $subaccount
     *
     * @return Area
     */
    public function addSubaccount(Subaccount $subaccount)
    {
        $subaccount->setArea($this);
        $this->subaccounts[] = $account;
        return $this;
    }

    /**
     * Remove Subaccount.
     *
     * @param \Subaccount $subaccount
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeSubaccount(Subaccount $subaccount)
    {
        return $this->subaccounts->removeElement($account);
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getDepartments()
    {
        return new \Doctrine\Common\Collections\ArrayCollection($this->getSubaccounts()->map(function($e) {
            return $e->getDepartment();
        })->toArray());
    }

    /**
     * Get subaccounts.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubaccounts()
    {
        return $this->subaccounts;
    }

    /**
     * Add Order.
     *
     * @param Order $order
     *
     * @return Area
     */
    public function addOrder(Order $order)
    {
        $order->setArea($this);
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
     * Set created.
     *
     * @param \Datetime $created
     *
     * @return Area
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
     * @return Area
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
     * @param string $type
     * @return string
     */
    public static function typeName($type)
    {
        switch ($type) {
            case Area::TYPE_EQUIPAMIENTO: return trans("Equipamiento");
            case Area::TYPE_FUNGIBLE: return trans("Fungible");
            case Area::TYPE_LANBIDE: return trans("Lanbide");
            default: return trans("Undefined");
        }
    }

    /**
     * @return string
     */
    public function getTypeName()
    {
        return Area::typeName($this->type);
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
