<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * OrderCharge
 *
 * @ORM\Entity(repositoryClass="App\Repositories\InvoiceChargeRepository")
 */
class InvoiceCharge extends HzCharge 
{
    const TYPE_CASH     = 0;
    const TYPE_INVOICED = 5;

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
            case self::TYPE_INVOICED:
                return trans("Invoice charge");
            default:
                return parent::typeName($type);
        }
    }

    /**
     * @inheritDoc
     */
    public function hydrate(array $raw)
    {
        parent::hydrate($raw);

        if (isset($raw['invoice'])) { 
            $this->setInvoice($raw['invoice']);
        }
        if (isset($raw['invoiceDate'])) {
            $date = $raw['invoiceDate'] instanceof DateTime ? 
                $raw['invoiceDate'] : new DateTime($raw['invoiceDate']);
            $this->setInvoiceDate($date);
        }
        /*if (isset($raw['hzyear']) && isset($raw['hzentry'])) { 
            $this->setHzCode("{$raw['hzyear']}-{$raw['hzentry']}");
        }*/

        return $this;
    }
}
