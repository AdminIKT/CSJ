<?php

namespace App\Entities\Account;

use Doctrine\ORM\Mapping as ORM;
use App\Entities\Order;

/**
 * @ORM\Entity
 */
class InvoiceDriveFile extends DriveFile
{
    /**
     * @param int $status
     * @return string
     */
    public static function getIcon($status)
    {
        //FIXME
        $path = "/img/google/icon.png";

        switch ($status) {
            case Order::STATUS_CREATED:
            case Order::STATUS_RECEIVED:
                $icon = "drive-regular";
                break;
            case Order::STATUS_CANCELLED:
            case Order::STATUS_CHECKED_NOT_AGREED:
                $icon = "drive-x";
                break;
            case Order::STATUS_CHECKED_PARTIAL_AGREED:
                $icon = "drive-check";
                break;
            case Order::STATUS_CHECKED_AGREED:
            case Order::STATUS_CHECKED_INVOICED:
            case Order::STATUS_PAID:
                $icon = "drive-double-check";
                break;
            default:
                $icon = "drive";
        }

        return str_replace('icon', $icon, $path);
    }
}
