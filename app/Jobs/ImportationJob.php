<?php

namespace App\Jobs;

use Carbon\Carbon;

use App\Models\Tbl_Log;
use App\Models\Command;
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

    protected $importationId;

    public function __construct($importationId)
    {
        $this->importationId = $importationId;
    }

    public function handle(): void
    {
        $currentTime = Carbon::now();
        $dataImport  = Importation_Demand::forId($this->importationId)->first();

        if (!$dataImport) {
            Tbl_Log::create([
                'descripcion' => 'ImportationJob => No existe el Id '.$this->importationId.' en la tabla importation_demand'
            ]);
        }

        try {
            $importation = Command::forNameBD($dataImport->name_db, $dataImport->area)->first();

            if($importation->state == '1'){
                /* Colocamos en state 0 cualquier importacion programada para que no se cruce con
                   la que esta a punto de ejecutarse */
                $importation->updateOrInsert(['name_db' => $dataImport->name_db], ['state' => '0']);

                // [Estado:2] => Significa que la importación esta en ejecución
                Importation_Demand::updateOrInsert(
                    ['id' => $this->importationId], ['state' => 2, 'updated_at' => $currentTime]
                );

                Artisan::call($dataImport->command, ['database' => $dataImport->name_db]);
                $commandOutput = Artisan::output();
                
                if ($commandOutput) {
                    $parameters = Command::forNameBD($dataImport->name_db, $dataImport->area)->first();
                    Artisan::call('command:export-information', [
                        'tenantDB' => $parameters->name_db,
                        'alias' => $parameters->alias,
                        'area' => $parameters->area
                    ]);
                    $exportOutput = Artisan::output();

                    if ($exportOutput) {
                        // [Estado:3] => Significa que la importación finalizo
                        Importation_Demand::updateOrInsert(
                            ['id' => $this->importationId], ['state' => 3, 'updated_at' => $currentTime]
                        );
                    }
                }

                // Apenas termine vuelve a activar la importacion programada
                $importation->updateOrInsert(['name_db' => $parameter->name_db], ['state' => '1']);
            }else{
                // [Estado:2] => Significa que la importación esta en ejecución
                Importation_Demand::updateOrInsert(
                    ['id' => $this->importationId], ['state' => 2, 'updated_at' => $currentTime]
                );
            }
        } catch (\Exception $e) {
            Tbl_Log::create([
                'descripcion' => 'ImportationJob => '.$e->getMessage()
            ]);
            // [Estado:4] => Significa que ocurrio un error en la importación
            Importation_Demand::updateOrInsert(
                ['id' => $this->importationId], ['state' => 4, 'updated_at' => $currentTime]
            );
        }
    }
}