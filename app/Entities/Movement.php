<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Movement
 *
 * @ORM\Table(name="movements")
 * @ORM\Entity(repositoryClass="App\Repositories\MovementRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *  "charge" = "Charge", 
 *  "assign" = "Assignment",
 *  "inv" = "InvoiceCharge"
 * })
 * @ORM\HasLifecycleCallbacks
 */
abstract class Movement
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var Subaccount 
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Subaccount", inversedBy="invoiceCharges")
     */
    private $subaccount;

    /**
     * @var float
     *
     * @ORM\Column(name="credit", type="float", options={"default":0})
     */
    private $credit = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="detail", type="string", nullable=true)
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
     * Set Subaccount.
     *
     * @param Subaccount $subaccount
     *
     * @return Movement
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
     * Get area.
     *
     * @return Area 
     */
    public function getArea()
    {
        return $this->getSubaccount()->getArea();
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
     * Set detail.
     *
     * @param string|null $detail
     *
     * @return Movement
     */
    public function setDetail($detail = null)
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
     * Get account.
     *
     * @return Account 
     */
    public function getAccount()
    {
        return $this->getSubaccount()->getAccount();
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
     * Get type name.
     *
     * @return string
     */
    public function getTypeName()
    {
        return static::typeName($this->getType());
    }

    /**
     * @return string
     */
    abstract public static function typeName($type);
}
