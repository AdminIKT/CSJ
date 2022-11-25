<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Charge 
 *
 * @ORM\Entity(repositoryClass="App\Repositories\ChargeRepository")
 */
class Charge extends Movement 
{
    const TYPE_CASH             = 0;
    const TYPE_OTHER            = 1;
    const TYPE_INVOICED_ACCOUNT = 6;

    const HZ_PREFIX = 'C';

    /**
     * @var string
     *
     * @ORM\Column(name="invoice", type="string")
     */
    private $invoice;

     /**
     * @var DateTime 
     *
     * @ORM\Column(name="invoiceDate", type="datetime")
     */
    private $invoiceDate;

    /**
     * Get invoice.
     *
     * @return string
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * Set invoice.
     *
     * @param string $invoice
     *
     * @return Charge
     */
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;

        return $this;
    }

     /**
     * Get invoice date.
     *
     * @return DateTime
     */
    public function getInvoiceDate()
    {
        return $this->invoiceDate;
    }

    /**
     * Set invoice date.
     *
     * @param string $invoiceDate
     *
     * @return Charge
     */
    public function setInvoiceDate($invoiceDate)
    {
        $this->invoiceDate = $invoiceDate;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public static function typeName($type)
    {
        switch ($type) {
            case self::TYPE_CASH:
                return trans("Cobro en caja");
            case self::TYPE_INVOICED_ACCOUNT:
                return trans("Cobro a cuenta");
            case self::TYPE_OTHER:
                return trans("Other charge");
            default:
                return trans("Undefined charge");
        }
    }
}
