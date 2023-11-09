<?php

namespace App\Console\Commands;

use App\Models\Ws_Consulta;
use App\Traits\ConnectionTrait;
use App\Custom\Insert_fyel_Custom;
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
        // $ano_act = date("Y", mktime(0, 0, 0, date("m")-25, date("d"), date("Y")));
        // echo($ano_act." Paso por aqui");
        // $ano_act = date("Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y")));
        // echo($ano_act." Paso por aqui dos");
        // dd();
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

            if($tableName == 't05_bex_clientes'){
                $this->connectionDB($conectionBex, $area);
                $insert = new Insert_fyel_Custom();
                $insert->InsertClientesCustom($conectionBex, $datosAInsertar, $modelInstance);
                $this->info('Tabla Clientes Actualizada');
                // dd('Termino el proceso de clientes');
            }

            if($tableName == 't29_bex_productos'){
                $insert = new Insert_fyel_Custom();
                $insert->insertProductsCustom($conectionBex, $datosAInsertar, $modelInstance, $tableName);
            }

            if($tableName == 't37_bex_amovil'){
                $this->connectionDB($conectionBex, $area);
                $insert = new Insert_fyel_Custom();
                $insert->InsertAmovilCustom($conectionBex, $datosAInsertar, $modelInstance);
                $this->info('Tabla amovil Actualizada');
                // dd('Termino el proceso');
            }
        }
        $this->info('Base de Datos Actualizada');
        dd('parar');
        
    }
}

