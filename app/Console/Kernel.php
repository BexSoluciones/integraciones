<?php

namespace App\Console;

use App\Models\Connection;
use App\Models\Commands;
use App\Console\Commands\Migrations\DataBaseIntegraciones;
use App\Console\Commands\Migrations\DataBasePandapan;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    
    protected function schedule(Schedule $schedule): void {

        $tasks = Commands::where('state','1')->all();

        foreach ($tasks as $task) {
            $schedule->command($task->command, [
                       $task->name_db, 
                       $task->area
                       ])->cron($task->cron_expression);
                     
        }
    }


    protected function commands(): void {
        
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }

}
