<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Process,
    Illuminate\Support\Facades\Storage;
use App\Entities\Settings,
    App\Entities\Backup\DriveDB,
    App\Services\CSJSettingsService; 
use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;

class DatabaseRemoveBackup extends DatabaseBackup 
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old backup files';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $em = app('em');
        $period      = $em->getRepository(Settings::class)->findOneBy(['type' => Settings::TYPE_BACKUP_RM_PERIOD]);
        $periodCount = $em->getRepository(Settings::class)->findOneBy(['type' => Settings::TYPE_BACKUP_RM_PERIOD_COUNT]);

        $expires = CSJSettingsService::getExpirationDate(new DriveDB,  $periodCount, $period, true);
        $backups = $em->getRepository('App\Entities\Backup\DriveDB')
                      ->createQueryBuilder('d')
                      ->where('d.status = :status')
                      ->setParameter('status', DriveDB::STATUS_UPLOADED)
                      ->andWhere('d.created < :datetime')
                      ->setParameter('datetime', $expires)
                      ->orderBy('d.created', 'DESC')
                      ->getQuery()
                      ->getResult();

        $local    = Storage::disk('backups');
        $remote   = Storage::disk('google');
        $service  = $remote->getAdapter()->getService();
        $hydrator = new DoctrineHydrator($em);
        foreach ($backups as $db) {
            if ($local->fileExists($db->getName())) {
                if ($service->files->delete($db->getFileId())) {
                    $local->delete($db->getName());
                    $em->remove($db);
                }
            }
        }

        $em->flush();

        /*

        $minutes  = 5;

            -mmin n
                File's data was last modified n minutes ago.

            -mtime n
                File's data was last modified n*24 hours ago.  

            {}  
                is the pathname of the current file

            \;  
                is the semicolon that terminates the command (rm). 
                It must be escaped with the backslash because otherwise, shell will interpret it is the end of the whole find command.
        $command = "find {$path}/* -mmin +{$minutes} -exec rm {} \;";

        //find /home/user/* -mmin +30 -exec ls -l {} \;
        exec($command);
        */
    }
}
