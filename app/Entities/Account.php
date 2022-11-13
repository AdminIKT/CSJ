<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use App\Entities\Account\DriveFile;

/**
 * Account
 *
 * @ORM\Table(name="accounts")
 * @ORM\Entity(repositoryClass="App\Repositories\AccountRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Account
{
    const TYPE_EQUIPAMIENTO = "E";
    const TYPE_FUNGIBLE     = "F";
    const TYPE_LANBIDE      = "L";
    const TYPE_OTHER        = "O";

    const STATUS_INACTIVE   = 0;
    const STATUS_ACTIVE     = 1;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    public $id;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer", options={"default":1})
     */
    private $status = Account::STATUS_ACTIVE;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     */
    public $name;

    /**
     * @var string
     *
     * @ORM\Column(name="drive_file", type="string")
     */
    private $fileId;

    /**
     * @var string
     *
     * @ORM\Column(name="drive_url", type="string")
     */
    private $fileUrl;

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
     * @ORM\OneToMany(targetEntity="App\Entities\Account\DriveFile", mappedBy="account", cascade={"persist", "remove"})
     */
    private $files;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Subaccount", mappedBy="account", cascade={"persist", "remove"})
     */
    private $subaccounts;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="User", inversedBy="accounts")
     * @ORM\JoinTable(name="account_user_rel", 
     *  joinColumns={@ORM\JoinColumn(name="account_id", referencedColumnName="id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *  )
     */
    private $users;

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
        $this->files    = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set status.
     *
     * @param int $status
     *
     * @return Account
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
    public function isActive()
    {
        return $this->isStatus(Account::STATUS_ACTIVE);
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Account
     */
    public function setName($name)
    {
        $this->name = (string) $name;

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
     * @return Account
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
     * @return Account 
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
     * @return Account
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
     * @return Account
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
     * Set folder.
     *
     * @param string $fileId
     *
     * @return Account
     */
    public function setFileId($fileId)
    {
        $this->fileId = (string) $fileId;

        return $this;
    }

    /**
     * Get fileId.
     *
     * @return string
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
     * @return Account
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
     * Add DriveFile.
     *
     * @param DriveFile $file
     *
     * @return Account
     */
    public function addFile(DriveFile $file)
    {
        $file->setAccount($this);
        $this->files[] = $file;
        return $this;
    }

    /**
     * Get files.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Add User.
     *
     * @param \User $user
     *
     * @return Account
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
     * @return Account
     */
    public function addSubaccount(Subaccount $subaccount)
    {
        $subaccount->setAccount($this);
        $this->subaccounts[] = $subaccount;
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
    public function getAreas()
    {
        return new \Doctrine\Common\Collections\ArrayCollection($this->getSubaccounts()->map(function($e) {
            return $e->getArea();
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
     * Get orders.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrders()
    {
        return new \Doctrine\Common\Collections\ArrayCollection($this->getSubaccounts()->map(function($e) {
            return $e->getOrder();
        })->toArray());
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
     * @param string $type
     * @return string
     */
    public static function typeName($type)
    {
        switch ($type) {
            case Account::TYPE_EQUIPAMIENTO: return trans("Equipamiento");
            case Account::TYPE_FUNGIBLE: return trans("Fungible");
            case Account::TYPE_LANBIDE: return trans("Lanbide");
            case Account::TYPE_OTHER: return trans("Other");
            default: return trans("Undefined");
        }
    }

    /**
     * @return string
     */
    public function getTypeName()
    {
        return Account::typeName($this->type);
    }

    /**
     * @return string
     */
    public static function statusColor($status) 
    {
        switch ($status) {
            case self::STATUS_INACTIVE: 
                return "bg-danger";
            case self::STATUS_ACTIVE: 
                return "bg-success";
            default: return "bg-light text-dark";
        }
    }

    /**
     * @return string
     */
    public static function statusName($status) 
    {
        switch ($status) {
            case self::STATUS_INACTIVE: 
                return trans("Inactive");
            case self::STATUS_ACTIVE: 
                return trans("Active");
            default: return trans("Undefined");
        }
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
