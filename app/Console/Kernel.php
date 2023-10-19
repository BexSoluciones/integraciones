<?php

namespace App\Console;

use App\Models\Connection;
use App\Console\Commands\Migrations\DataBaseIntegraciones;
use App\Console\Commands\Migrations\DataBasePandapan;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    
    protected function schedule(Schedule $schedule): void {

        //$schedule->command('migrate:database-integraciones')->everyMinute();
        //$schedule->command('migrate:database-pandapan')->everyMinute();
    }


    protected function commands(): void {
        
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }

}
