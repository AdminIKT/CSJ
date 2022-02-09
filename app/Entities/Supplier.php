<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

use App\Entities\Supplier;

/**
 * Supplier 
 *
 * @ORM\Table(name="suppliers")
 * @ORM\Entity(repositoryClass="App\Repositories\SupplierRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Supplier 
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
     * @var int
     *
     * @ORM\Column(name="nif", type="bigint", unique=true)
     */
    private $nif;

    /**
     * @var int
     *
     * @ORM\Column(name="zip", type="integer")
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
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="accp", type="boolean", options={"default":true})
     */
    private $acceptable = true;

    /**
     * @var string
     *
     * @ORM\Column(name="rcmm", type="boolean", options={"default":false})
     */
    private $recommendable = false;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entities\Supplier\Contact", mappedBy="supplier", cascade={"persist","merge"})
     */
    private $contacts;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entities\Order\Product", mappedBy="supplier", cascade={"persist","merge"})
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
        $this->contacts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nif.
     *
     * @param int $nif
     *
     * @return Supplier
     */
    public function setNif($nif)
    {
        $this->nif = (int) $nif;

        return $this;
    }

    /**
     * Get nif.
     *
     * @return int
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
     * @param int $zip
     *
     * @return Supplier
     */
    public function setZip($zip)
    {
        $this->zip = (int) $zip;

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
     * Get acceptable.
     *
     * @return bool.
     */
    public function getAcceptable()
    {
        return $this->acceptable;
    }
    
    /**
     * Set acceptable.
     *
     * @param bool acceptable the value to set.
     * @return Supplier
     */
    public function setAcceptable($acceptable)
    {
        $this->acceptable = (bool) $acceptable;
        return $this;
    }
    
    /**
     * Get recommendable.
     *
     * @return bool.
     */
    public function getRecommendable()
    {
        return $this->recommendable;
    }
    
    /**
     * Set recommendable.
     *
     * @param bool recommendable the value to set.
     * @return Supplier
     */
    public function setRecommendable($recommendable)
    {
        $this->recommendable = (bool) $recommendable;
        return $this;
    }

    /**
     * Add contact.
     *
     * @param Supplier\Contact $contact
     *
     * @return Supplier
     */
    public function addContact(Supplier\Contact $contact)
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
    public function removeContact(Supplier\Contact $contact)
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
    public function __tostring() 
    {
        return (string) $this->getId();
    }
}
