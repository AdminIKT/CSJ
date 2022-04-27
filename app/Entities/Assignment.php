<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Assignment
 *
 * @ORM\Table(name="assignments")
 * @ORM\Entity(repositoryClass="App\Repositories\AssignmentRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Assignment
{
    /**
     * @var int
     */
    const TYPE_ANUAL         = 0;
    const TYPE_EXTRAORDINARY = 1;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Subaccount 
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Subaccount", inversedBy="assignments")
     */
    private $subaccount;

    /**
     * @var int
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var float
     *
     * @ORM\Column(name="credit", type="float", options={"default":0})
     */
    private $credit = 0;

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
     * Set detail.
     *
     * @param string $detail
     *
     * @return Assignment
     */
    public function setDetail($detail = null)
    {
        $this->detail = $detail ? (string) $detail : null;

        return $this;
    }

    /**
     * Get detail.
     *
     * @return string|null
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
     * @return Assignment
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
     * @return Assignment
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
     * Set subaccount.
     *
     * @param Subaccount $subaccount
     *
     * @return Assignment
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
     * Set created.
     *
     * @param \Datetime $created
     *
     * @return Assignment
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
     * @return Assignment
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
            case self::TYPE_ANUAL: 
                return trans("Anual");
            case self::TYPE_EXTRAORDINARY: 
                return trans("Extraordinary");
            default:
                return trans("Undefined");
        }
    }
}
