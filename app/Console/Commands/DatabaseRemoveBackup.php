<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DatabaseRemoveBackup extends Command
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
        $path = storage_path() . "/" . env("DB_BACKUP_PATH");

        $minutes  = 5;

        /*
            -mmin n
                File's data was last modified n minutes ago.

            -mtime n
                File's data was last modified n*24 hours ago.  

            {}  
                is the pathname of the current file

            \;  
                is the semicolon that terminates the command (rm). 
                It must be escaped with the backslash because otherwise, shell will interpret it is the end of the whole find command.
        */
        $command = "find {$path}/* -mmin +{$minutes} -exec rm {} \;";
        exec($command);
    }
}
