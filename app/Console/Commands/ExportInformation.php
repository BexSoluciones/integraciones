<?php

namespace App\Console\Commands;

use App\Models\Tbl_Log;
use App\Models\Connection_Bexsoluciones;
use App\Traits\ConnectionTrait;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ExportInformation extends Command
{
    use ConnectionTrait;

    protected $signature   = 'command:export-information {tenantDB} {connection_bs_id} {area}';
    protected $description = 'Export information to bex solutions databases';

    public function handle()
    {
        try {
            $tenantDB     = $this->argument('tenantDB'); 
            $conectionBex = $this->argument('connection_bs_id');
            $area         = $this->argument('area');

            //Function that configures the database (ConnetionTrait).
            $configDB = $this->connectionDB($conectionBex, 'externa', $area); 
            if($configDB == false){
                return;
            }
            
            // Llamar un custom de manera dinamica
            $custom = "App\\Custom\\$tenantDB\\InsertCustom";
            
            // Verificar si el custom existe
            if (!class_exists($custom)) {
                Tbl_Log::create([
                    'descripcion' => 'Commands::ExportInformation[handle()] => No existe el custom '.$custom
                ]);
                return print '▲ Se ha producido un error en la importación' . PHP_EOL;
            }

            // Instanciamos el custom
            $customInstance = app()->make($custom);

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
            
            $this->connectionDB($tenantDB, 'local');
            $customMethods = [
                't04_bex_cartera'       => 'insertCarteraCustom',
                't05_bex_clientes'      => 'insertClientesCustom',
                't12_bex_dptos'         => 'insertDptosCustom',
                't13_bex_estadopedidos' => 'insertEstadoPedidosCustom',
                't16_bex_inventarios'   => 'insertInventarioCustom',
                't18_bex_mpios'         => 'insertMpiosCustom',
                't19_bex_paises'        => 'insertPaisCustom',
                't25_bex_precios'       => 'insertPreciosCustom',
                't29_bex_productos'     => 'insertProductsCustom',
                't34_bex_ruteros'       => 'insertRuteroCustom',
                't36_bex_vendedores'    => 'insertVendedoresCustom',
                't37_bex_amovil'        => 'InsertAmovilCustom',
            ];

            $conectionBex = Connection_Bexsoluciones::showConnectionBS($conectionBex, $area)->value('name');
            foreach ($availableModels as $modelClass => $tableName) {
                $modelInstance = new $modelClass();
                $datosAInsertar = $modelInstance::get();
            
                if($tableName == 't16_bex_inventarios'){
                    $conectionSys = 2;
                    $this->connectionDB($conectionSys, 'externa', $area);
                    $conectionSys = Connection_Bexsoluciones::showConnectionBS($conectionSys, $area)->value('name');
                }else{
                    $conectionSys = null;
                }
            
                if (array_key_exists($tableName, $customMethods)) {
                    $methodName = $customMethods[$tableName];
                    $insert = new $custom();
                    $insert->$methodName($conectionBex, $conectionSys, $datosAInsertar, $modelInstance, $tableName);
                    print "◘ Proceso $methodName Finalizado" . PHP_EOL;
                }
            }
            print '◘ Información Base de Datos '.$tenantDB.' Exportada.' . PHP_EOL;
        } catch (\Exception $e) {
            Tbl_Log::create([
                'descripcion' => 'Commands::ExportInformation[handle()] => '.$e->getMessage()
            ]);
        }
    }
}

