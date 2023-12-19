<?php

namespace App\Console\Commands;

use App\Models\Tbl_Log;
use App\Models\Ws_Config;

use App\Traits\MigrateTrait;
use App\Traits\ConnectionTrait;
use App\Traits\DataImportTrait;
use App\Traits\ReadExportDataTrait;
use App\Traits\BackupFlatFileTrait;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateInformation extends Command {

    use MigrateTrait, ConnectionTrait, DataImportTrait, ReadExportDataTrait, BackupFlatFileTrait;

    protected $signature = 'command:update-information {database} {status?}';
    protected $description = "Extract, generate drawings, and store information in the tenant's database";

    public function handle(): int {
        try {
            //name of the database where we are going to migrate
            $db     = $this->argument('database'); 
            $status = $this->input->hasArgument('status') ? $this->argument('status') : false;

            //Function that configures the database (ConnetionTrait).
            $configDB = $this->connectionDB($db, 'local'); 
            if($configDB == false){
                return 0;
            }
            Log::info('paso 1');
            //Si la migracion se va a ejecutar por primer vez, se toma en cuenta primero esta condicion
            if($status == 'new'){
                $this->preMigration($db);
                print '◘ Ya puedes ejecutar el comando: php artisan command:update-information '.$db . PHP_EOL;
                return 0;
            }
            Log::info('paso 2');
            //Function to extract data through WS (DataImportTrait).
            $config = Ws_Config::where('estado', 1)->first();
            Log::info($config);
            //Cuando el ws_config no tiene informacion es porque los planos se suben automaticamente sin necesidad de conexion
            if ($config->ConecctionType == 'planos') {
                $archivosPlanos = true;
            } elseif($config->ConecctionType == 'ws') {
                $archivosPlanos = $this->importData($db);
            }
            Log::info('paso 3');
            //Function to configure and migrate tables (MigrateTrait).
            if($archivosPlanos == true){
                $this->preMigration($db);
            }
            Log::info('paso 4');
            //Function to read and export flat file to tenant DB
            $flatFile = $this->readFlatFile($db);
            Log::info($flatFile);
            if($flatFile == false){

                return 0;
            }
            log::info('paso 5');
            //Realizar copia de seguridad para tipo de conexion "planoa"
            if ($config->ConecctionType == 'planos') {
                //backup txt files
                $backupFlatFile = $this->backupFlatFile($db, true);
                if($backupFlatFile != true){
                    print '▲ Error copia de seguridad archivos panos ' . PHP_EOL;
                    return 0;
                }
            }
            print '◘ La ejecucion "command:update-information '.$db.'" ha finalizado.';
            return 1;
        } catch (\Exception $e) {
            Tbl_Log::create([
                'descripcion' => 'Commands::UpdateInformation[handle()] => '.$e->getMessage()
            ]);
            return 0;
        }
    }
}
