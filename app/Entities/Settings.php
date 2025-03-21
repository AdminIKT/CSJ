<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="settings")
 * @ORM\Entity()
 */
class Settings
{
    const TYPE_CURRENT_YEAR                  = 0;
    const TYPE_ORDER_ESTIMATED_LIMIT         = 1;
    const TYPE_SUPPLIER_INVOICED_LIMIT       = 2;
    const TYPE_SUPPLIER_NO_ACCEPTABLE_LIMIT  = 3;
    const TYPE_SUPPLIER_RECOMMENDABLE_LIMIT  = 4;
    const TYPE_BACKUP_CR_PERIOD              = 5; //Database Backup create
    const TYPE_BACKUP_CR_PERIOD_COUNT        = 6;
    const TYPE_BACKUP_RM_PERIOD              = 7; //Database Backup remove
    const TYPE_BACKUP_RM_PERIOD_COUNT        = 8;

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
            case self::TYPE_CURRENT_YEAR: return trans("año_seleccion");
            case self::TYPE_ORDER_ESTIMATED_LIMIT: return trans("limite_presupuesto");
            case self::TYPE_SUPPLIER_INVOICED_LIMIT:  return trans("limite_facturado");
            case self::TYPE_SUPPLIER_RECOMMENDABLE_LIMIT:
                return trans("limite_pedidos_proveedor");
            case self::TYPE_SUPPLIER_NO_ACCEPTABLE_LIMIT:
                return trans("limite_incidencias_proveedor");
            default: return trans("Undefined");
        }
    }

    /**
     * @return string
     */
    public static function typeDescription($type) 
    {
        switch ($type) {
            case self::TYPE_CURRENT_YEAR: 
                return trans("año_seleccion_descripcion");
            case self::TYPE_ORDER_ESTIMATED_LIMIT: 
                return trans("limite_presupuesto_descripcion");
            case self::TYPE_SUPPLIER_INVOICED_LIMIT:  
                return trans("limite_facturado_descripcion");
            case self::TYPE_SUPPLIER_RECOMMENDABLE_LIMIT:
                return trans("limite_pedidos_proveedor_descripcion");
            case self::TYPE_SUPPLIER_NO_ACCEPTABLE_LIMIT:
                return trans("limite_incidencias_proveedor_descripcion");
            default: return trans("Undefined");
        }
    }
}
