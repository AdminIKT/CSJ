<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Process\Pipe;
use Illuminate\Support\Facades\Process,
    Illuminate\Support\Facades\Storage,
    Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Entities\Settings,
    App\Entities\Backup\DriveDB,
    App\Services\CSJSettingsService; 

class DatabaseCreateBackup extends DatabaseBackup 
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup:create {--force : Force backup creation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Database complete dump';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $em = app('em');
        $lastBackup = $em->getRepository(DriveDB::class)
                         ->createQueryBuilder('d')
                         ->orderBy('d.created', 'DESC')
                         ->setMaxResults(1)
                         ->getQuery()
                         ->getOneOrNullResult();

        if ($lastBackup) {
            $period      = $em->getRepository(Settings::class)->findOneBy(['type' => Settings::TYPE_BACKUP_CR_PERIOD]);
            $periodCount = $em->getRepository(Settings::class)->findOneBy(['type' => Settings::TYPE_BACKUP_CR_PERIOD_COUNT]);
            $expires     = CSJSettingsService::getExpirationDate($lastBackup,  $periodCount, $period);
            if ($expires > now() && !$this->option('force')) {
                return;
            }
        }

        $user = env("DB_USERNAME");
        $pass = env("DB_PASSWORD");
        $host = env("DB_HOST");
        $name = env("DB_DATABASE");

        //for ($i=0; $i<1; $i++) {
            $now = new \DateTime;
            //$now = \Carbon\Carbon::today()->subDays(rand(-5, 0));
        
            $storage = Storage::disk('backups');
            $filename = "backup_{$now->format('Y-m-d_H:i:s')}.sql.gz";
            $filepath = $storage->path($filename);

            $result = Process::pipe(function (Pipe $pipe) use ($user, $pass, $host, $name, $filepath) {
                $pipe->command("mysqldump --user={$user} --password={$pass} --host={$host} $name");
                $pipe->command("gzip > {$filepath}");
            });

            if ($result->successful()) {
                $backup = new \App\Entities\Backup\DriveDB;
                $backup->setName($filename)
                       ->setCreated($now);

                $em->persist($backup);
            }
            else {
                Log::error(sprintf("Error al crear backup %s:{%s:%s} at %s", $filepath, $result->output(), $result->errorOutput(), __CLASS__));
            }
        //}
        $em->flush();

        /**
         * mysqldump returns
         * 0 for Success
         * 1 for Warning
         * 2 for Not Found
         */
        //$command  = "mysqldump --user={$user} --password={$pass} --host={$host} $name";
        //$command .= "| gzip > {$filepath}";

        //exec($command);
    }
}
