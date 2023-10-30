<?php

namespace App\Console\Commands;

use App\Models\Ws_Consulta;

use App\Traits\ConnectionTrait;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ExportInformation extends Command
{
    use ConnectionTrait;

    protected $signature   = 'command:export-information {tenantDB}';
    protected $description = 'Export information to bex solutions databases';

    public function handle()
    {
        $tenantDB = $this->argument('tenantDB'); 
        $conectionBex = 'fyel-local';
        $area = 'bexmovil';

        //Function that configures the database (ConnetionTrait).
        $configDB = $this->connectionDB($tenantDB); 
        if($configDB == false){
            return;
        }

        //Folder of Models
        $baseNamespace = 'App\\Models\\'.ucfirst($tenantDB).'\\';
        //All models
        $modelFiles = File::files(app_path('Models/'.ucfirst($tenantDB)));
        $availableModels = [];

        foreach ($modelFiles as $modelFile) {
            //Route of model
            $routeName = $baseNamespace . pathinfo($modelFile->getFilename(), PATHINFO_FILENAME);
            if (class_exists($routeName)) {
                //extracts the model name and stores it in the $availableModels array
                $availableModels[$routeName] = (new $routeName())->getTable();
            }
        }

        foreach ($availableModels as $modelClass => $tableName) {
            $modelInstance = new $modelClass();
            $datosAInsertar = $modelInstance::get();
            
            if($tableName == 't37_bex_amovil'){
                $configDB = $this->connectionDB($conectionBex, $area);
                // Itera sobre los datos y realiza inserciones individuales
                foreach ($datosAInsertar as $dato) {
                    $datoArray = (array) $dato;
                    DB::connection($conectionBex)->table('tbldamovil')->insert($datoArray);
                }
                $this->info('datos insetados');
                dd('parar');
            }
        }
        
    }
}
