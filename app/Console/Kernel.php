<?php

namespace App\Console;

use App\Models\Command;
use App\Models\Connection;

use App\Console\Commands\UpdateInformation;

use Illuminate\Support\Stringable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void {
        try {
         
            $parameters = Command::getAll()->get();
      
            foreach ($parameters as $parameter) {
                $event = $schedule->command($parameter->command, [
                    $parameter->name_db
                ])->cron($parameter->cron_expression)->onSuccess(function (Stringable $output) {
                    Artisan::call('command:export-information', [
                        $parameter->name_db,
                        $parameter->alias,
                        $parameter->area
                    ]);
                })
                ->onFailure(function (Stringable $output) {
                    echo $output;
                });
            }
           
        } catch (\Exception $e) {
            Log::error('Error schedule: ' . $e->getMessage());
        }
    }

    protected function commands(): void {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
