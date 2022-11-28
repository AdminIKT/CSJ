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
    
    /**
     * @param array $values
     * @return Charge
     */
    public static function fromArray(array $values)
    {
        $charge = new static;
        if (isset($values['credit'])) 
            $charge->setCredit($values['credit']);
        if (isset($values['type'])) 
            $charge->setType($values['type']);
        if (isset($values['detail'])) 
            $charge->setDetail($values['detail']);
        if (isset($values['hzyear']) && isset($values['hzentry'])) 
            $charge->setHzCode("{$values['hzyear']}-{$values['hzentry']}");

        return $charge;
    }
}
