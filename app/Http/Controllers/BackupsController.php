<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\Settings,
    App\Entities\Backup\DriveDB,
    App\Services\CSJSettingsService as SettingsService;

class BackupsController extends BaseController
{
    /**
     * @inheritDoc
     */
    protected function authorization()
    {
        $this->authorizeResource(DriveDB::class, 'backup');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $values = $request->validate([
            'cr_period'       => ['required', 'integer', "min:".SettingsService::BACKUP_RM_PERIOD_VALUE_MINUTES, 'max:' . SettingsService::BACKUP_RM_PERIOD_VALUE_MONTHS],
            'rm_period'       => ['required', 'integer', "min:".SettingsService::BACKUP_RM_PERIOD_VALUE_MINUTES, 'max:' . SettingsService::BACKUP_RM_PERIOD_VALUE_MONTHS],
            'cr_period_count' => ['required', 'integer', 'min:1'],
            'rm_period_count' => ['required', 'integer', 'min:1'],
        ]);

        $em = app('em');

        $em->getRepository(Settings::class)
           ->findOneByType(Settings::TYPE_BACKUP_CR_PERIOD)
           ->setValue($values['cr_period']);

        $em->getRepository(Settings::class)
           ->findOneByType(Settings::TYPE_BACKUP_CR_PERIOD_COUNT)
           ->setValue($values['cr_period_count']);

        $em->getRepository(Settings::class)
           ->findOneByType(Settings::TYPE_BACKUP_RM_PERIOD)
           ->setValue($values['rm_period']);

        $em->getRepository(Settings::class)
           ->findOneByType(Settings::TYPE_BACKUP_RM_PERIOD_COUNT)
           ->setValue($values['rm_period_count']);

        $em->flush();

        return redirect()->to(route('backups.index'))->with('success', __('Successfully updated'));
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rmPeriod      = app('em')->getRepository(Settings::class)->findOneByType(Settings::TYPE_BACKUP_RM_PERIOD);
        $rmPeriodCount = app('em')->getRepository(Settings::class)->findOneByType(Settings::TYPE_BACKUP_RM_PERIOD_COUNT);
        $crPeriod      = app('em')->getRepository(Settings::class)->findOneByType(Settings::TYPE_BACKUP_CR_PERIOD);
        $crPeriodCount = app('em')->getRepository(Settings::class)->findOneByType(Settings::TYPE_BACKUP_CR_PERIOD_COUNT);

        return view('backups.index', [
            'route'         => route('backups.store'),
            'method'        => 'POST',
            'crPeriod'      => $crPeriod,
            'crPeriodCount' => $crPeriodCount,
            'rmPeriod'      => $rmPeriod,
            'rmPeriodCount' => $rmPeriodCount,
            'collection'    => app('em')->getRepository(DriveDB::class)->findBy([], ['created' => 'DESC']),
        ]); 
    }
}
