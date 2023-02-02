<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

use App\Entities\Supplier\Contact,
    App\Entities\Supplier\Incidence,
    App\Entities\Supplier\Invoiced;

/**
 * Supplier 
 *
 * @ORM\Table(name="suppliers")
 * @ORM\Entity(repositoryClass="App\Repositories\SupplierRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Supplier 
{
    const STATUS_PENDING       = 0;
    const STATUS_VALIDATED     = 1;
    const STATUS_RECOMMENDABLE = 2;
    const STATUS_NO_ACCEPTABLE = -1;

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
     * @ORM\Column(name="status", type="integer", options={"default":0})
     */
    private $status = Supplier::STATUS_PENDING;

    /**
     * @var string
     *
     * @ORM\Column(name="nif", type="string", unique=true)
     */
    private $nif;

    /**
     * @var int
     *
     * @ORM\Column(name="zip", type="string", nullable=true)
     */
    private $zip;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string")
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="region", type="string")
     */
    private $region;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     */
    public $name;

    /**
     * @var string
     *
     * @ORM\Column(name="detail", type="string", nullable=true)
     */
    private $detail;

    /**
     * @var User 
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\User", inversedBy="suppliers")
     */
    private $user;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entities\Supplier\Contact", mappedBy="supplier", cascade={"persist","merge"})
     */
    private $contacts;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entities\Supplier\Incidence", mappedBy="supplier")
     * @ORM\OrderBy({"created" = "DESC"})
     */
    private $incidences;

    /**
     * @var int
     *
     * @ORM\Column(name="incidences", type="integer", options={"default":0})
     */
    private $incidenceCount = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="orders", type="integer", options={"default":0})
     */
    private $orderCount = 0;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entities\Supplier\Invoiced", mappedBy="supplier", cascade={"persist","merge"})
     */
    private $invoiced;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entities\Order", mappedBy="supplier")
     */
    private $orders;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entities\Order\Product", mappedBy="supplier")
     */
    private $products;

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
        $this->orders     = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contacts   = new \Doctrine\Common\Collections\ArrayCollection();
        $this->products   = new \Doctrine\Common\Collections\ArrayCollection();
        $this->incidences = new \Doctrine\Common\Collections\ArrayCollection();
        $this->invoiced   = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Supplier
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
    public function isPending()
    {
        return $this->isStatus(self::STATUS_PENDING);
    }

    /**
     * @return bool
     */
    public function isValidated()
    {
        return $this->isStatus(self::STATUS_VALIDATED);
    }

    /**
     * @return bool
     */
    public function isRecommendable()
    {
        return $this->isStatus(self::STATUS_RECOMMENDABLE);
    }

    /**
     * @return bool
     */
    public function isNoAcceptable()
    {
        return $this->isStatus(self::STATUS_NO_ACCEPTABLE);
    }

    /**
     * Set nif.
     *
     * @param string $nif
     *
     * @return Supplier
     */
    public function setNif($nif)
    {
        $this->nif = $nif;

        return $this;
    }

    /**
     * Get nif.
     *
     * @return string
     */
    public function getNif()
    {
        return $this->nif;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Supplier
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
     * Set zip.
     *
     * @param string $zip
     *
     * @return Supplier
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get zip.
     *
     * @return int
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set city.
     *
     * @param string $city
     *
     * @return Supplier
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set address.
     *
     * @param string $address
     *
     * @return Supplier
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address.
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set region.
     *
     * @param string $region
     *
     * @return Supplier
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region.
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set detail.
     *
     * @param string|null $detail
     *
     * @return Supplier
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
     * Set user.
     *
     * @param User $user
     *
     * @return Supplier
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
     * Add contact.
     *
     * @param Supplier\Contact $contact
     *
     * @return Supplier
     */
    public function addContact(Contact $contact)
    {
        $contact->setSupplier($this);
        $this->contacts[] = $contact;
        return $this;
    }

    /**
     * Remove contact.
     *
     * @param \Contact $contact
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeContact(Contact $contact)
    {
        return $this->contacts->removeElement($contact);
    }

    /**
     * Get contacts.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * Get incidences.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Get incidences.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Set orderCount.
     *
     * @param int $count
     * @return int
     */
    public function setOrderCount($count)
    {
        $this->orderCount = (int) $count;
        return $this;
    }

    /**
     * Get orders.
     *
     * @return int
     */
    public function getOrderCount()
    {
        return $this->orderCount;
    }

    /**
     * @return Supplier
     */
    public function increaseOrderCount()
    {
        $this->orderCount++;
        return $this;
    }

    /**
     * @return Supplier
     */
    public function decreaseOrderCount()
    {
        $this->orderCount--;
        return $this;
    }

    /**
     * Set incidenceCount.
     *
     * @param int $count
     * @return int
     */
    public function setIncidenceCount($count)
    {
        $this->incidenceCount = (int) $count;
        return $this;
    }

    /**
     * Get incidences.
     *
     * @return int
     */
    public function getIncidenceCount()
    {
        return $this->incidenceCount;
    }

    /**
     * @return Supplier
     */
    public function increaseIncidenceCount()
    {
        $this->incidenceCount++;
        return $this;
    }

    /**
     * @return Supplier
     */
    public function decreaseIncidenceCount()
    {
        $this->incidenceCount--;
        return $this;
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
     * @param int year|null
     * @return Invoiced|null
     */
    public function getInvoiced(int $year = null)
    {
        if ($year === null) {
            return $this->invoiced;
        }
        foreach ($this->invoiced as $invoiced) {
            if ($invoiced->getYear() === $year) return $invoiced;
        }
    }

    /**
     * @param Invoiced $invoiced
     * @return Supplier
     */
    public function addInvoiced(Invoiced $invoiced)
    {
        $invoiced->setSupplier($this);
        $this->invoiced->add($invoiced);
        return $this;
    }

    /**
     * Set created.
     *
     * @param \Datetime $created
     *
     * @return Supplier
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
     * @return Supplier
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
            case self::STATUS_PENDING: 
                return trans("Validation pending");
            case self::STATUS_VALIDATED: 
                return trans("In evaluation");
            case self::STATUS_RECOMMENDABLE: 
                return trans("Acceptable");
            case self::STATUS_NO_ACCEPTABLE: 
                return trans("No acceptable");
            default: return trans("Undefined");
        }
    }

    /**
     * @return string
     */
    public static function statusColor($status) 
    {
        switch ($status) {
            case self::STATUS_PENDING: 
                return "bg-dark";
            case self::STATUS_VALIDATED: 
                return "bg-warning";
            case self::STATUS_RECOMMENDABLE: 
                return "bg-success";
            case self::STATUS_NO_ACCEPTABLE: 
                return "bg-danger";
            default: return "bg-light text-dark";
        }
    }

    /**
     * Get status name.
     *
     * @return string
     */
    public function getStatusName()
    {
        return self::statusName($this->getStatus());
    }

    /**
     * Get status color.
     *
     * @return string
     */
    public function getStatusColor()
    {
        return self::statusColor($this->getStatus());
    }

    /**
     * @return string
     */
    public function __tostring() 
    {
        return (string) $this->getId();
    }
}
