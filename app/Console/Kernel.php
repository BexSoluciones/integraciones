<?php

namespace App\Console;

use App\Models\Tbl_Log;
use App\Models\Command;
use App\Models\Connection;
use App\Models\Importation_Demand;

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
                // Se cambia el state a 2 para saber que se esta ejecutando
                $parameter->updateOrInsert(['name_db' => $parameter->name_db], ['state' => '2']);
                // Ejecuta el comando
                $schedule->command($parameter->command, [
                    $parameter->name_db
                ])
                // Si todo sale bien ejecuta el siguiente comando
                ->cron($parameter->cron_expression)->onSuccess(function (Stringable $output) use ($parameter) {
                    // Llamar a otro comando si es necesario
                    Artisan::call('command:export-information', [
                        'tenantDB' => $parameter->name_db,
                        'alias' => $parameter->alias,
                        'area' => $parameter->area
                    ]);
                    //Si finaliza correctamente se cambio a state 1 para que pueda volver a ejecutarse
                    $parameter->updateOrInsert(['name_db' => $parameter->name_db], ['state' => '1']);

                    $importationInCurse = Importation_Demand::importationInCurse($parameter->name_db, $parameter->area);
                    if($importationInCurse){
                        Importation_Demand::updateOrInsert(
                            ['id' => $this->importationId], ['state' => 3, 'updated_at' => $currentTime]
                        );
                    }
                })
                //si ocurre un error se se guarda y se cambia astate 1 para que vuelva aquedar activo 
                ->onFailure(function (Stringable $output) use ($parameter) {
                    $parameter->updateOrInsert(['name_db' => $parameter->name_db], ['state' => '1']);
                    Tbl_Log::create([
                        'descripcion' => 'Kernel[schedule()] => '.$output
                    ]);
                })
                // Sirve para que un schedule no se ejecute encima de otro y espere 10 mn
                ->withoutOverlapping(10);
            }
        } catch (\Exception $e) {
            Tbl_Log::create([
                'descripcion' => 'Kernel[schedule()] => '.$e->getMessage()
            ]);
        }
    }

    protected function commands(): void {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
