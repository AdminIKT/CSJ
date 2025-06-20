<?php

use App\Entities\Settings;
use Doctrine\ORM\EntityManagerInterface;

if (!function_exists('createSetting')) {
    function createSetting(string $type, string $value): Settings {
        $em = app(EntityManagerInterface::class);
        
        $setting = new Settings();
        $setting->setType($type)
               ->setValue($value);
        
        $em->persist($setting);
        $em->flush();
        
        return $setting;
    }
}

if (!function_exists('createCurrentYearSetting')) {
    function createCurrentYearSetting(): Settings {
        return createSetting(Settings::TYPE_CURRENT_YEAR, date('Y'));
    }
}
