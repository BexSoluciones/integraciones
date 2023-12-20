<?php

namespace App\Jobs;

use Carbon\Carbon;

use App\Models\Tbl_Log;
use App\Models\Command;
use App\Models\Connection;
use App\Models\Importation_Demand;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 120;
    protected $consecutive;

    public function __construct($consecutive)
    {
        $this->consecutive = $consecutive;
    }

    public function handle(): void
    {
        $currentTime = Carbon::now();
        $dataImport  = Importation_Demand::forConsecutive($this->consecutive)->first();

        try {
            $importation = Command::forNameBD($dataImport->name_db, $dataImport->area)->first();
            if (!$importation) {
                $connections = Connection::forNameDB($dataImport->name_db)->first();
                if(!$connections){
                    Tbl_Log::create([
                        'id_table'    => $this->consecutive,
                        'name_table'  => 'commands',
                        'area'        => $dataImport->area,
                        'descripcion' => 'Jobs::ImportationJob[handle()] => No existe la BD '.$dataImport->name_db
                    ]);
                    return;
                }
  
                // [Estado:2] => Significa que la importación esta en ejecución
                Importation_Demand::updateOrInsert(
                    ['consecutive' => $this->consecutive], ['state' => 2, 'updated_at' => $currentTime]
                );
                
                $updateInformation = Artisan::call('command:update-information', [
                    'database'       => $dataImport->name_db,
                    'id_importation' => $this->consecutive,
                    'name_table'     => 'importation_demand'
                ]);
               
                if($updateInformation == 0) {
                    // [Estado:4] => Significa que la importación finalizo
                    Importation_Demand::updateOrInsert(
                        ['consecutive' => $this->consecutive], ['state' => 4, 'updated_at' => $currentTime]
                    );
                    return;
                }
             
                $exportInformation = Artisan::call('command:export-information', [
                    'tenantDB'         => $connections->name,
                    'connection_bs_id' => $connections->id,
                    'area'             => $connections->area,
                    'id_importation'   => $this->consecutive,
                    'name_table'       => 'importation_demand'
                ]);
                Log::info('paso 6');
                if($exportInformation == 1) {
                    // [Estado:3] => Significa que la importación finalizo
                    Importation_Demand::updateOrInsert(
                        ['consecutive' => $this->consecutive], ['state' => 3, 'updated_at' => $currentTime]
                    );  
                } else {
                    // [Estado:4] => Significa que la importación finalizo
                    Importation_Demand::updateOrInsert(
                        ['consecutive' => $this->consecutive], ['state' => 4, 'updated_at' => $currentTime]
                    );
                }
                Log::info('paso 7');
                return;
            }

            Log::info('por aqui paso');
            
            return;
            if($importation->state == '1'){
                /* Colocamos en state 0 cualquier importacion programada para que no se cruce con
                   la que esta a punto de ejecutarse */
                $importation->updateOrInsert(['name_db' => $dataImport->name_db], ['state' => '0']);

                // [Estado:2] => Significa que la importación esta en ejecución
                Importation_Demand::updateOrInsert(
                    ['consecutive' => $this->consecutive], ['state' => 2, 'updated_at' => $currentTime]
                );
                
                $parameters = Command::forNameBD($dataImport->name_db, $dataImport->area)->first();

                $updateInformation = Artisan::call($dataImport->command, ['database' => $dataImport->name_db]);

                if($updateInformation == 0) {
                    // [Estado:4] => Significa que la importación finalizo
                    Importation_Demand::updateOrInsert(
                        ['consecutive' => $this->consecutive], ['state' => 4, 'updated_at' => $currentTime]
                    );

                    // Apenas termine vuelve a activar la importacion programada
                    $importation->updateOrInsert(['name_db' => $parameters->name_db], ['state' => '1']);
                    return;
                }
                Log::info('finalizo');
                return;
                $value = Artisan::call('command:export-information', [
                    'tenantDB' => $parameters->name_db,
                    'connection_bs_id' => $parameters->connection_bexsoluciones_id,
                    'area' => $parameters->area
                ]);
                
                if($value != 1) {
                    // [Estado:4] => Significa que la importación finalizo
                    Importation_Demand::updateOrInsert(
                        ['consecutive' => $this->consecutive], ['state' => 4, 'updated_at' => $currentTime]
                    );
                }

                // [Estado:3] => Significa que la importación finalizo
                Importation_Demand::updateOrInsert(
                    ['consecutive' => $this->consecutive], ['state' => 3, 'updated_at' => $currentTime]
                );

                // Apenas termine vuelve a activar la importacion programada
                $importation->updateOrInsert(['name_db' => $parameters->name_db], ['state' => '1']);
                
            } else {
                // [Estado:2] => Significa que la importación esta en ejecución
                Importation_Demand::updateOrInsert(
                    ['consecutive' => $this->consecutive], ['state' => 2, 'updated_at' => $currentTime]
                );
            }
        } catch (\Exception $e) {
            Tbl_Log::create([
                'descripcion' => 'Jobs::ImportationJob[handle()] => '.$e->getMessage()
            ]);
            // [Estado:4] => Significa que ocurrio un error en la importación
            Importation_Demand::updateOrInsert(
                ['consecutive' => $this->consecutive], ['state' => 4, 'updated_at' => $currentTime]
            );
        }
    }
}
