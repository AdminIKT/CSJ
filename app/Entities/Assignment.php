<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Assignment
 *
 * @ORM\Entity(repositoryClass="App\Repositories\AssignmentRepository")
 */
class Assignment extends Movement
{
    const TYPE_ANUAL         = 2;
    const TYPE_EXTRAORDINARY = 3;
    const TYPE_OTHER         = 4;

    /**
     * @inheritDoc
     */
    public static function typeName($type)
    {
        switch ($type) {
            case self::TYPE_ANUAL:
                return trans("Anual assignment");
            case self::TYPE_EXTRAORDINARY:
                return trans("Extraordinary assignment");
            case self::TYPE_OTHER:
                return trans("Other assignment");
            default:
                return trans("Undefined assignment");
        }
    }
}
