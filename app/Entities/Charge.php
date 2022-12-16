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
    const TYPE_OTHER   = 1;

    /**
     * @inheritDoc
     */
    public static function typeName($type)
    {
        switch ($type) {
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
    public function hydrate(array $values)
    {
        if (isset($values['credit'])) 
            $this->setCredit($values['credit']);
        if (isset($values['type'])) 
            $this->setType($values['type']);
        if (isset($values['detail'])) 
            $this->setDetail($values['detail']);
        return $this;
    }
}
