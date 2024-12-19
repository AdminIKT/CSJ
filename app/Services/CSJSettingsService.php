<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

use App\ENtities\Backup\DriveDB,
    App\Entities\Settings;

class CSJSettingsService
{
    const BACKUP_RM_PERIOD_VALUE_MINUTES    = 0;
    const BACKUP_RM_PERIOD_VALUE_HOURS      = 1;
    const BACKUP_RM_PERIOD_VALUE_DAYS       = 2;
    const BACKUP_RM_PERIOD_VALUE_MONTHS     = 3;

    /**
     * @return string
     */
    public static function backupPeriodName($value) 
    {
        switch ($value) {
            case self::BACKUP_RM_PERIOD_VALUE_MINUTES: 
                return trans("Minutes");
            case self::BACKUP_RM_PERIOD_VALUE_HOURS: 
                return trans("Hours");
            case self::BACKUP_RM_PERIOD_VALUE_DAYS: 
                return trans("Days");
            case self::BACKUP_RM_PERIOD_VALUE_MONTHS: 
                return trans("Months");
            default: return trans("Undefined");
        }
    }

    /**
     * Get expires.
     *
     * @param Settings $periodCount
     * @param Settings $period
     * @param bool $negative
     * @return \Datetime
     * @throws Exception
     */
    public static function getExpirationDate(DriveDB $db, Settings $periodCount, Settings $period, $negative = false)
    {
        switch ($period->getValue()) {
            case self::BACKUP_RM_PERIOD_VALUE_MINUTES: 
                $expr = "minutes";
                break;
            case self::BACKUP_RM_PERIOD_VALUE_HOURS: 
                $expr = "hours";
                break;
            case self::BACKUP_RM_PERIOD_VALUE_DAYS: 
                $expr = "days";
                break;
            case self::BACKUP_RM_PERIOD_VALUE_MONTHS: 
                $expr = "months";
                break;
            default: 
                throw new \Exception(sprintf("Not valid BACKUP_RM_PERIOD_VALUE given: %d", 
                    $periodCount->getValue()));
        }
        $expires = clone $db->getCreated();
        $expires->modify(implode(" ", [
            $negative ? "-": "+", 
            $periodCount->getValue(), 
            $expr
        ]));
        return $expires;
    }
}
