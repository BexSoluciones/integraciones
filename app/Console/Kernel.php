<?php

namespace App\Console;

use Cron\CronExpression;
use Carbon\Carbon;

use App\Models\Tbl_Log;
use App\Models\Command;
use App\Models\Connection;
use App\Models\Importation_Demand;
use App\Models\Importation_Automatic;
use App\Console\Commands\UpdateInformation;

use Illuminate\Support\Stringable;
use Illuminate\Support\Facades\DB;
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
                $cron = CronExpression::factory($parameter->cron_expression);

                // Verifica si la expresión cron coincide con la fecha actual
                if ($cron->isDue()) {
                    $this->importationAutomatic = Importation_Automatic::create([
                        'id_table'  => $parameter->id,
                        'state'     => 2,
                        'date_init' => Carbon::now()
                    ]);
                }else{
                    $this->importationAutomatic = null;
                }
                
                if($parameter->command == 'command:update-information'){
                    // Ejecuta el comando
                    $schedule->command($parameter->command, [$parameter->name_db, $parameter->area,'0', $this->importationAutomatic ? $this->importationAutomatic->id : null, 1])
                    ->before(function () use ($parameter) {
                        // Se cambia el state a 2 para saber que se esta ejecutando
                        $parameter->updateOrInsert(['name_db' => $parameter->name_db, 'area' => $parameter->area], ['state' => '2']);
                    })
                    ->cron($parameter->cron_expression)
                    // Si todo sale bien ejecuta el siguiente comando
                    ->onSuccess(function (Stringable $output) use ($parameter) {
                        // Segundo comando para pasar a la exportacion de informacion
                        Artisan::call('command:export-information', [
                            'tenantDB'         => $parameter->name_db,
                            'connection_bs_id' => $parameter->connection_bexsoluciones_id,
                            'area'             => $parameter->area,
                            'id_importation'   => $this->importationAutomatic->id,
                            'type'             => 1
                        ]);
                        
                        //Si finaliza correctamente se cambio a state 1 para que pueda volver a ejecutarse
                        $parameter->updateOrInsert(['name_db' => $parameter->name_db, 'area' => $parameter->area], ['state' => '1']);
                        
                        $importationInCurse = Importation_Demand::importationInCurse($parameter->name_db, $parameter->area)->first();
                        if(isset($importationInCurse)){
                            Importation_Demand::updateOrInsert(
                                ['consecutive' => $importationInCurse->consecutive], ['state' => 3, 'updated_at' => now()]
                            );
                        }
                        
                        $importationAutomaticToUpdate = Importation_Automatic::find($this->importationAutomatic->id);
                        $importationAutomaticToUpdate->update(['state' => 3, 'date_init' => $this->importationAutomatic->date_init, 'date_end' => now()]);
                    })
                    //si ocurre un error se se guarda y se cambia a state 1 para que vuelva aquedar activo 
                    ->onFailure(function (Stringable $output) use ($parameter) {
                        $parameter->updateOrInsert(['name_db' => $parameter->name_db, 'area' => $parameter->area], ['state' => '1']);
                    
                        $importationAutomaticToUpdate = Importation_Automatic::find($this->importationAutomatic->id);
                        $importationAutomaticToUpdate->update(['state' => 4, 'date_init' => $this->importationAutomatic->date_init, 'date_end' => now()]);
                    })
                    // Sirve para que un schedule no se ejecute encima de otro y espere 1 mn
                    ->withoutOverlapping(1);
                }

                if($parameter->command == 'command:upload-order'){
                    // Ejecuta el comando
                    $schedule->command($parameter->command, [$parameter->connection_bexsoluciones_id, $parameter->area, 'null', $this->importationAutomatic ? $this->importationAutomatic->id : null, 1])
                    ->before(function () use ($parameter) {
                        // Se cambia el state a 2 para saber que se esta ejecutando
                        $parameter->updateOrInsert(['name_db' => $parameter->name_db, 'area' => $parameter->area], ['state' => '2']);
                    })
                    ->cron($parameter->cron_expression)
                    // Si todo sale bien ejecuta el siguiente comando
                    ->onSuccess(function (Stringable $output) use ($parameter) {
                        
                        //Si finaliza correctamente se cambio a state 1 para que pueda volver a ejecutarse
                        $parameter->updateOrInsert(['name_db' => $parameter->name_db, 'area' => $parameter->area], ['state' => '1']);
                        
                        $importationInCurse = Importation_Demand::importationInCurse($parameter->name_db, $parameter->area)->first();
                        if(isset($importationInCurse)){
                            Importation_Demand::updateOrInsert(
                                ['consecutive' => $importationInCurse->consecutive], ['state' => 3, 'updated_at' => now()]
                            );
                        }
                        
                        $importationAutomaticToUpdate = Importation_Automatic::find($this->importationAutomatic->id);
                        $importationAutomaticToUpdate->update(['state' => 3, 'date_init' => $this->importationAutomatic->date_init, 'date_end' => now()]);
                    })
                    //si ocurre un error se se guarda y se cambia a state 1 para que vuelva aquedar activo 
                    ->onFailure(function (Stringable $output) use ($parameter) {
                        $parameter->updateOrInsert(['name_db' => $parameter->name_db, 'area' => $parameter->area], ['state' => '1']);
                    
                        $importationAutomaticToUpdate = Importation_Automatic::find($this->importationAutomatic->id);
                        $importationAutomaticToUpdate->update(['state' => 4, 'date_init' => $this->importationAutomatic->date_init, 'date_end' => now()]);
                    })
                    // Sirve para que un schedule no se ejecute encima de otro y espere 1 mn
                    ->withoutOverlapping(1);
                }
            }
        } catch (\Exception $e) {
            DB::connection('mysql')->table('tbl_log')->insert([
                'descripcion' => 'Kernel[schedule()] => '.$e->getMessage(),
                'created_at'  => now(),
                'updated_at'  => now()
            ]);
        }
    }

    protected function commands(): void {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
