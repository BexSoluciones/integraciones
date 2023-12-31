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

    protected $signature = 'command:update-information {database} {status?} {id_importation?} {type?}';
    protected $description = "Extract, generate drawings, and store information in the tenant's database";

    public function handle(): int {
        try {
            // Name of the database where we are going to migrate
            $db             = $this->argument('database'); 
            $status         = $this->input->hasArgument('status') ? $this->argument('status') : false;
            $id_importation = $this->input->hasArgument('id_importation') ? $this->argument('id_importation') : null;
            $type           = $this->input->hasArgument('type') ? $this->argument('type') : null;
            
            // Function that configures the database (ConnetionTrait).
            $configDB = $this->connectionDB($db, 'local'); 
            if($configDB != 0){
                DB::connection('mysql')->table('tbl_log')->insert([
                    'id_table'    => $id_importation,
                    'type'        => $type,
                    'descripcion' => 'Commands::UpdateInformation[handle()] => Conexion Local: Linea '.__LINE__.'; '.$configDB,
                    'created_at'  => now(),
                    'updated_at'  => now()
                ]);
                return 1;
            }
 
            // Si la migracion se va a ejecutar por primer vez, se toma en cuenta primero esta condicion
            if($status == 'new'){
                $this->preMigration($db);
                print '◘ Ya puedes ejecutar el comando: php artisan command:update-information '.$db . PHP_EOL;
                return 0;
            }
            
            // Function to extract data through WS (DataImportTrait).
            $config = Ws_Config::where('estado', 1)->first();
            if(!$config){
                Tbl_Log::create([
                    'id_table'    => $id_importation,
                    'type'        => $type,
                    'descripcion' => 'Commands::UpdateInformation[handle()] => No se encontraron datos en la tabla ws_config'
                ]);
                return 1;
            }
       
            // Cuando el ws_config no tiene informacion es porque los planos se suben automaticamente sin necesidad de conexion
            if ($config->ConecctionType == 'planos') {
                $archivosPlanos = true;
            } elseif($config->ConecctionType == 'ws') {
                $archivosPlanos = $this->importData($db);
            }
          
            // Function to configure and migrate tables (MigrateTrait).
            if($archivosPlanos == true){
                $this->preMigration($db);
            }

            //Function to read and export flat file to tenant DB
            $flatFile = $this->readFlatFile($db, $id_importation, $type);
            if($flatFile == 1){
                return 1;
            }
         
            //Realizar copia de seguridad para tipo de conexion "planoa"
            if ($config->ConecctionType == 'planos') {
                //backup txt files
                $backupFlatFile = $this->backupFlatFile($db, true, $id_importation, $type);
                if($backupFlatFile == 1){
                    return 1;
                }
            }
            print '◘ La ejecucion "command:update-information '.$db.'" ha finalizado.';
            return 0;
        } catch (\Exception $e) {
            Tbl_Log::create([
                'id_table'    => $id_importation,
                'type'        => $type,
                'descripcion' => 'Commands::UpdateInformation[handle()] => '.$e->getMessage()
            ]);
            return 1;
        }
    }
}
