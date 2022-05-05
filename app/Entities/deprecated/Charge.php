<?php
//
//namespace App\Entities;
//
//use Doctrine\ORM\Mapping as ORM;
//
///**
// * Charge 
// *
// * @ORM\InheritanceType("JOINED")
// * @ORM\DiscriminatorMap({
// *  "inv" = "InvoiceCharge"
// * })
// * @ORM\Entity(repositoryClass="App\Repositories\ChargeRepository")
// */
//class Charge extends Movement 
//{
//    const TYPE_CASH  = 0;
//    const TYPE_OTHER = 1;
//
//    /**
//     * @inheritDoc
//     */
//    public static function typeName($type)
//    {
//        switch ($type) {
//            case self::TYPE_CASH:
//                return trans("Cobro en caja");
//            case self::TYPE_OTHER:
//                return trans("Other charge");
//            default:
//                return trans("Undefined charge");
//        }
//    }
//}
