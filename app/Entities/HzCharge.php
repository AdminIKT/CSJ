<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * HzCharge 
 *
 * @ORM\Entity
 */
class HzCharge extends Charge 
{
    const TYPE_WITHOUT_INVOICED   = 7;

    const HZ_PREFIX = 'O';

    /**
     * TODO: code must be unique within type in DB 
     * @var string
     *
     * @ORM\Column(name="hz_code", type="string")
     */
    private $hzCode;

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
     * @inheritDoc
     */
    public static function typeName($type)
    {
        switch ($type) {
            case self::TYPE_WITHOUT_INVOICED:
                return trans("Charge without invoice");
            default:
                return parent::typeName($type);
        }
    }

    public function hydrate(array $raw)
    {
        parent::hydrate($raw);

        if (isset($raw['hzyear']) && isset($raw['hzentry'])) { 
            $this->setHzCode("{$raw['hzyear']}-{$raw['hzentry']}");
        }

        return $this;
    }
}
