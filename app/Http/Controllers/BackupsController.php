<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\Settings,
    App\Entities\Backup\DriveDB;

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
        $period      = app('em')->getRepository(Settings::class)
                                ->findOneBy(['type' => Settings::TYPE_BACKUP_RM_PERIOD])
                                ->setValue($request->input('period'));
        $periodCount = app('em')->getRepository(Settings::class)
                                ->findOneBy(['type' => Settings::TYPE_BACKUP_RM_PERIOD_COUNT])
                                ->setValue($request->input('period_count'));

        app('em')->flush();

        return redirect()->to(route('backups.index'))->with('success', __('Successfully updated'));
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $period      = app('em')->getRepository(Settings::class)->findOneBy(['type' => Settings::TYPE_BACKUP_RM_PERIOD]);
        $periodCount = app('em')->getRepository(Settings::class)->findOneBy(['type' => Settings::TYPE_BACKUP_RM_PERIOD_COUNT]);

        return view('backups.index', [
            'route'       => route('backups.store'),
            'method'      => 'POST',
            'period'      => $period,
            'periodCount' => $periodCount,
            'collection'  => $this->em->getRepository(DriveDB::class)->findBy([], ['created' => 'DESC']),
        ]); 
    }
}
