<?php

namespace App\Entities\Supplier;

use Doctrine\ORM\Mapping as ORM;

use App\Entities\Supplier;

/**
 * Invoiced 
 *
 * @ORM\Table(name="supplier_invoiced")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Invoiced
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
     * @var string
     *
     * @ORM\Column(name="year", type="integer", length=4)
     */
    private $year;

    /**
     * @var float
     *
     * @ORM\Column(name="credit", type="float", options={"default":0})
     */
    private $credit = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="estimated", type="float", options={"default":0})
     */
    private $estimated = 0;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToOne(targetEntity="App\Entities\Supplier", inversedBy="invoiced")
     */
    private $supplier;

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
     * Set supplier.
     *
     * @param Supplier $supplier
     *
     * @return Invoiced
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
     * Set year.
     *
     * @param int $year
     *
     * @return Invoiced
     */
    public function setYear(int $year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year.
     *
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @return float
     */
    public function getTotal()
    {
        return $this->getEstimated() + $this->getCredit();
    }

    /**
     * Set credit.
     *
     * @param float $credit
     *
     * @return Invoiced
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
     * Set estimated.
     *
     * @param float $estimated
     *
     * @return Invoiced
     */
    public function setEstimated(float $estimated)
    {
        $this->estimated = $estimated;

        return $this;
    }

    /**
     * Get estimated.
     *
     * @return float
     */
    public function getEstimated()
    {
        return $this->estimated;
    }

    /**
     * Set created.
     *
     * @param \Datetime $created
     *
     * @return Invoiced
     */
    public function setCreated(\Datetime $created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Set increaseEstimated.
     *
     * @param float $credit
     *
     * @return Invoiced
     */
    public function increaseEstimated(float $credit)
    {
        $this->estimated += $credit;
        return $this;
    }

    /**
     * Set increaseCredit.
     *
     * @param float $credit
     *
     * @return Invoiced
     */
    public function increaseCredit(float $credit)
    {
        $this->credit += $credit;
        return $this;
    }

    /**
     * Set decreaseEstimated.
     *
     * @param float $credit
     *
     * @return Invoiced
     */
    public function decreaseEstimated(float $credit)
    {
        $this->estimated -= $credit;
        return $this;
    }

    /**
     * Set decreaseCredit.
     *
     * @param float $credit
     *
     * @return Invoiced
     */
    public function decreaseCredit(float $credit)
    {
        $this->credit -= $credit;
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
     * @return Invoiced
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
}
