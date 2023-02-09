<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

class DatabaseCreateBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Database complete dump';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = env("DB_USERNAME");
        $pass = env("DB_PASSWORD");
        $host = env("DB_HOST");
        $name = env("DB_DATABASE");
        $path = env("DB_BACKUP_PATH");

        $filename = "backup-" . Carbon::now()->format('Y-m-d:H:i') . ".gz";
        $filepath = storage_path() . "/{$path}/{$filename}";

        $command  = "mysqldump --user={$user} --password={$pass} --host={$host} $name";
        $command .= "| gzip > {$filepath}";

        exec($command);
    }
}
