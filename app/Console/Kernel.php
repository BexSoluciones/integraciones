<?php

namespace App\Console;

use Carbon\Carbon;

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
            $currentTime = Carbon::now();
            $parameters  = Command::getAll()->get();
            
            foreach ($parameters as $parameter) {
                // Ejecuta el comando
                $schedule->command($parameter->command, [$parameter->name_db, '0', $parameter->id, 1])
                ->before(function () use ($parameter) {
                    Log::info('paso 1');
                    // Se cambia el state a 2 para saber que se esta ejecutando
                    $parameter->updateOrInsert(['name_db' => $parameter->name_db], ['state' => '2']);
                    Log::info('paso 2');
                })
                // Si todo sale bien ejecuta el siguiente comando
                ->cron($parameter->cron_expression)
                ->after(function (Stringable $output) use ($parameter) {
                    Log::info('paso 3');
                    // Llamar a otro comando si es necesario
                    Artisan::call('command:export-information', [
                        'tenantDB'         => $parameter->name_db,
                        'connection_bs_id' => $parameter->connection_bexsoluciones_id,
                        'area'             => $parameter->area,
                        'id_importation'   => $parameter->id,
                        'type'             => 1
                    ]);
                    Log::info('paso 4');
                    //Si finaliza correctamente se cambio a state 1 para que pueda volver a ejecutarse
                    $parameter->updateOrInsert(['name_db' => $parameter->name_db], ['state' => '1']);
                    Log::info('paso 5');
                    $importationInCurse = Importation_Demand::importationInCurse($parameter->name_db, $parameter->area)->first();
                    if(isset($importationInCurse)){
                        Importation_Demand::updateOrInsert(
                            ['consecutive' => $importationInCurse->consecutive], ['state' => 3, 'updated_at' => $currentTime]
                        );
                    }
                    Log::info('paso 6');
                })
                /*
                //si ocurre un error se se guarda y se cambia astate 1 para que vuelva aquedar activo 
                ->onFailure(function (Stringable $output) use ($parameter) {
                    Log::info('paso 7');
                    Log::info($output);
                    $parameter->updateOrInsert(['name_db' => $parameter->name_db], ['state' => '1']);
                    /*
                    Tbl_Log::create([
                        'id_importation' => $parameter->id,
                        'type'           => 1,
                        'descripcion'    => 'Kernel[schedule()] => '.$output
                    ]);
                    Log::info('paso 8');
                })*/
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
