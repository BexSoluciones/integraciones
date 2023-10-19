<?php

namespace App\Console\Commands;

use App\Models\Ws_Config;
use App\Traits\MigrateTrait;
use App\Traits\ConnectionTrait;
use App\Traits\DataImportTrait;
use App\Traits\ReadExportDataTrait;

use Illuminate\Console\Command;

class UpdateInformation extends Command {

    use MigrateTrait, ConnectionTrait, DataImportTrait, ReadExportDataTrait;

    protected $signature = 'command:update-information {database} {status?}';
    protected $description = 'Command to migrate, generate flat files';

    public function handle() {
        //name of the database where we are going to migrate
        $db     = $this->argument('database'); 
        $status = $this->input->hasArgument('status') ? $this->argument('status') : false;

        //Function that configures the database (ConnetionTrait).
        $configDB = $this->connectionDB($db); 
        if($configDB == false){
            return;
        }

        //Si la migracion se va a ejecutar por primer vez, se toma en cuenta primero esta condicion
        if($status == 'new'){
            $this->preMigration($db);
            dd('Ya puedes ejecutar el comando: php artisan command:update-information '.$db);
        }
        
        //Function to extract data through WS (DataImportTrait).
        $config = Ws_Config::getConnectionForId(1);
        if($config->connectionType == 'ws'){
            $archivosPlanos = $this->importData($db);
        }else{
            $archivosPlanos = true;
        }
    
        //Function to configure and migrate tables (MigrateTrait).
        if($archivosPlanos == true){
            $this->preMigration($db);
        }
       
        //Function to read and export flat file to tenant DB
        $this->readFlatFile($db);
    }
}
