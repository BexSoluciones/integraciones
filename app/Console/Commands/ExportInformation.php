<?php

namespace App\Console\Commands;

use App\Traits\ConnectionTrait;
use App\Custom\Insert_fyel_Custom;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ExportInformation extends Command
{
    use ConnectionTrait;

    protected $signature   = 'command:export-information {tenantDB} {alias} {area}';
    protected $description = 'Export information to bex solutions databases';

    public function handle()
    {
        try {
            $tenantDB     = $this->argument('tenantDB'); 
            $conectionBex = $this->argument('alias');
            $area         = $this->argument('area');

            //Function that configures the database (ConnetionTrait).
            $configDB = $this->connectionDB($tenantDB); 
            if($configDB == false){
                return;
            }
            // $ano_act = date("Y", mktime(0, 0, 0, date("m")-25, date("d"), date("Y")));
            // $ano_act = date("Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y")));
            
            $baseNamespace = 'App\\Models\\'.ucfirst($tenantDB).'\\'; //Folder of Models
            $modelFiles = File::files(app_path('Models/'.ucfirst($tenantDB))); //All models
            $availableModels = [];

            foreach ($modelFiles as $modelFile) {
                //Route of model
                $routeName = $baseNamespace . pathinfo($modelFile->getFilename(), PATHINFO_FILENAME);
                if (class_exists($routeName)) {
                    //extracts the model name and stores it in the $availableModels array
                    $availableModels[$routeName] = (new $routeName())->getTable();
                }
            }  

            $this->connectionDB($conectionBex, $area);
            foreach ($availableModels as $modelClass => $tableName) {
                $modelInstance = new $modelClass();
                $datosAInsertar = $modelInstance::get();

                if($tableName == 't19_bex_paises'){
                    $insert = new Insert_fyel_Custom();
                    $insert->InsertPaisCustom($conectionBex, $datosAInsertar, $modelInstance);
                    $this->info('Tabla Paises Actualizada');
                    //  dd('Termino el proceso de PAISES');
                }

                if($tableName == 't12_bex_dptos'){
                    $insert = new Insert_fyel_Custom();
                    $insert->InsertDptosCustom($conectionBex, $datosAInsertar, $modelInstance);
                    $this->info('Tabla Departamentos Actualizada');
                    //  dd('Termino el proceso de PAISES');
                }
                
                if($tableName == 't18_bex_mpios'){
                    $insert = new Insert_fyel_Custom();
                    $insert->InsertMpiosCustom($conectionBex, $datosAInsertar, $modelInstance);
                    $this->info('Tabla Municipios Actualizada');
                    //  dd('Termino el proceso de PAISES');
                }
                
                if($tableName == 't04_bex_cartera'){
                    $insert = new Insert_fyel_Custom();
                    $insert->insertCarteraCustom($conectionBex, $datosAInsertar, $modelInstance);
                    $this->info('Tabla Cartera Actualizada');
                    // dd('Termino el proceso de clientes');
                }
                
                if($tableName == 't05_bex_clientes'){
                    $insert = new Insert_fyel_Custom();
                    $insert->InsertClientesCustom($conectionBex, $datosAInsertar, $modelInstance);
                    $this->info('Tabla Clientes Actualizada');
                    // dd('Termino el proceso de clientes');
                }
                
                if($tableName == 't13_bex_estadopedidos'){
                    $insert = new Insert_fyel_Custom();
                    $insert->insertEstadoPedidosCustom($conectionBex, $datosAInsertar, $modelInstance);
                    $this->info('◘ Tabla Estados de los Pedidos Actualizada');
                }
                
                if($tableName == 't16_bex_inventarios'){
                    $conectionSys = 'sys';
                    $this->connectionDB($conectionSys, $area);
                    $insert = new Insert_fyel_Custom();
                    $insert->insertInventarioCustom($conectionBex, $conectionSys, $modelInstance);
                    $this->info('◘ Proceso inventario finalizado');
                }
                
                if($tableName == 't25_bex_precios'){
                    $insert = new Insert_fyel_Custom();
                    $insert->insertPreciosCustom($conectionBex,$datosAInsertar,$modelInstance);
                    $this->info('◘ Proceso precios finalizado'); 
                }
                
                if($tableName == 't29_bex_productos'){
                    $insert = new Insert_fyel_Custom();
                    $insert->insertProductsCustom($conectionBex, $datosAInsertar, $modelInstance);
                    $this->info('◘ Proceso productos finalizado');
                }
                
                if($tableName == 't34_bex_ruteros'){
                    $conectionSys = 'sys';
                    $this->connectionDB($conectionSys,$area);
                    $insert = new Insert_fyel_Custom();
                    $insert->insertRuteroCustom($conectionBex, $datosAInsertar, $modelInstance,$conectionSys);
                    $this->info('Tabla Ruteros Actualizada');
                    // dd('Termino el proceso de clientes');
                } 

                if($tableName == 't36_bex_vendedores'){
                    $insert = new Insert_fyel_Custom();
                    $insert->InsertVendedoresCustom($conectionBex, $datosAInsertar, $modelInstance);
                    $this->info('Tabla Vendedores Actualizada');
                    // dd('Termino el proceso de clientes');
                }
                
                if($tableName == 't37_bex_amovil'){
                    $insert = new Insert_fyel_Custom();
                    $insert->InsertAmovilCustom($conectionBex, $datosAInsertar, $modelInstance);
                    $this->info('Tabla amovil Actualizada');
                    // dd('Termino el proceso');
                }
            }
            $this->info('◘ Base de Datos '.$conectionBex.' actualizada');
        } catch (\Exception $e) {
            $this->error("Ha ocurrido un error: " . $e->getMessage());
        }
    }
}

