<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * OrderCharge
 *
 * @ORM\Entity(repositoryClass="App\Repositories\InvoiceChargeRepository")
 */
class InvoiceCharge extends Charge 
{
    const TYPE_INVOICED = 5;

    const HZ_PREFIX = 'C';

    /**
     * TODO: code must be unique within type in DB 
     * @var string
     *
     * @ORM\Column(name="hz_code", type="string")
     */
    private $hzCode;

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
     * Get hzYear.
     *
     * @return string
     */
    public function getHzYear()
    {
        $pieces = explode("-", $this->hzCode);
        return isset($pieces[0]) ? $pieces[0] : "";
    }

    /**
     * Get hzEntry.
     *
     * @return string
     */
    public function getHzEntry()
    {
        $pieces = explode("-", $this->hzCode);
        return isset($pieces[1]) ? $pieces[1] : "";
    }

    /**
     * Get hzCode.
     *
     * @return string
     */
    public function getHzCode()
    {
        return $this->hzCode;
    }

    /**
     * Set hzCode.
     *
     * @param string $hzCode
     *
     * @return Charge
     */
    public function setHzCode($hzCode)
    {
        $this->hzCode = $hzCode;

        return $this;
    }

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

        return $this;
    }
}
