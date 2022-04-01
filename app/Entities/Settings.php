<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\ACL\Contracts\Role as RoleContract;
use LaravelDoctrine\ACL\Mappings as ACL;
use LaravelDoctrine\ACL\Contracts\HasPermissions as HasPermissionContract;
use LaravelDoctrine\ACL\Permissions\HasPermissions;

/**
 * @ORM\Table(name="settings")
 * @ORM\Entity()
 */
class Settings
{
    const TYPE_ESTIMATED_LIMIT = 0;
    const TYPE_INVOICED_LIMIT  = 1;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="float", unique=true)
     */
    protected $type;

    /**
     * @ORM\Column(type="integer")
     */
    protected $value;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type.
     *
     * @param float $type
     *
     * @return Settings
     */
    public function setType(float $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return float
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set value.
     *
     * @param int $value
     *
     * @return Settings
     */
    public function setValue(int $value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value.
     *
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get type name.
     *
     * @return string
     */
    public function getTypeName()
    {
        return self::typeName($this->getType());
    }

    /**
     * Get type description.
     *
     * @return string
     */
    public function getTypeDescription()
    {
        return self::typeDescription($this->getType());
    }

    /**
     * @return string
     */
    public static function typeName($type) 
    {
        switch ($type) {
            case self::TYPE_ESTIMATED_LIMIT: return trans("limite_presupuesto");
            case self::TYPE_INVOICED_LIMIT:  return trans("limite_facturado");
            return trans("Undefined");
        }
    }

    /**
     * @return string
     */
    public static function typeDescription($type) 
    {
        switch ($type) {
            case self::TYPE_ESTIMATED_LIMIT: 
                return trans("limite_presupuesto_descripcion");
            case self::TYPE_INVOICED_LIMIT:  
                return trans("limite_facturado_descripcion");
            return "Undefined";
        }
    }
}
