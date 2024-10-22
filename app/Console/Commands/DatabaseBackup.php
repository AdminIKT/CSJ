<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Process\Pipe;
use Illuminate\Support\Facades\Process,
    Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

abstract class DatabaseBackup extends Command
{
}
