<?php

namespace App\Console\Commands;

use App\Traits\MigrateTrait;
use App\Traits\ConnectionTrait;
use App\Traits\DataImportTrait;
use App\Traits\ReadExportDataTrait;

use Illuminate\Console\Command;

class UpdateInformation extends Command {

    use MigrateTrait, ConnectionTrait, DataImportTrait, ReadExportDataTrait;

    protected $signature = 'command:update-information {database}';
    protected $description = 'Command to migrate, generate flat files';

    public function handle() {
        //name of the database where we are going to migrate
        $db = $this->argument('database'); 
        
        //Function that configures the database (ConnetionTrait).
        $configDB = $this->connectionDB($db); 
        if($configDB == false){
            return;
        }
        
        //Function to extract data through WS (DataImportTrait).
        $archivosPlanos = $this->importData($db);
    
        //Function to configure and migrate tables (MigrateTrait).
        if($archivosPlanos == true){
            $this->preMigration($db);
        }
       
        //Function to read and export flat file to tenant DB
        $this->readFlatFile($db);
    }
}
