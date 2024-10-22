<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Process\Pipe;
use Illuminate\Support\Facades\Process,
    Illuminate\Support\Facades\Storage,
    Illuminate\Support\Facades\Log;
use Illuminate\Http\File;
use Carbon\Carbon;
use App\Entities\Backup\DriveDB,
    App\Services\CSJDriveService;
use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;

class DatabaseUploadBackup extends DatabaseBackup 
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup:upload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Database dump upload';

    /**
     * @var CSJDriveService
     */
    protected $drive;


    /**
     * @param CSJDriveService $drive
     */
    public function __construct(CSJDriveService $drive)
    {
        $this->drive = $drive;
        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $folder = env("GOOGLE_DRIVE_BACKUPS_FOLDER_ID");

        $em = app('em');
        $backups = $em->getRepository('App\Entities\Backup\DriveDB')
                      ->findByStatus(DriveDB::STATUS_PENDING);

        $hydrator = new DoctrineHydrator($em);
        foreach ($backups as $db) {
            if (Storage::disk('backups')->fileExists($db->getName())) {
                try {
                    $response = $this->drive->uploadBackup($db);
                    $values = [
                        'status'  => DriveDB::STATUS_UPLOADED,
                        'fileId'  => $response->getId(),
                        'fileUrl' => $response->getWebViewLink(),
                    ];

                    $hydrator->hydrate($values, $db);
                } 
                catch (\Exception $e) {
                    Log::error(sprintf("Error al subir backup %s at %s", $db->getName(), __CLASS__));
                }
            }
            else {
                Log::error(sprintf("No se puede encontrar el backup %s at %s", $filepath));
            }
        }

        $em->flush();
    }
}
