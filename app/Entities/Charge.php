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
