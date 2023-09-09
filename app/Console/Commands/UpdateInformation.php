<?php

namespace App\Console\Commands;

use App\Traits\MigrateTrait;
use App\Traits\ConnectionTrait;
use App\Traits\DataImportTrait;
use Illuminate\Console\Command;

class UpdateInformation extends Command {

    use MigrateTrait, ConnectionTrait, DataImportTrait;

    protected $signature = 'command:update-information {database}';
    protected $description = 'Command to migrate, generate flat files';

    public function handle() {
        //name of the database where we are going to migrate
        $db = $this->argument('database'); 

        //Function that configures the database (ConnetionTrait).
        $this->connectionDB($db); 

        //Function to extract data through WS (DataImportTrait).
        $archivosPlanos = $this->importData($db);
        //$jsonResult = json_encode($dataWS, JSON_PRETTY_PRINT);
        //$this->info($jsonResult);

        //Function to configure and migrate tables (MigrateTrait).
        if($archivosPlanos == true){
            $this->preMigration($db);
        }
        
    }
}
