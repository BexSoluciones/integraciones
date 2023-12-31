<?php

namespace App\Console\Commands;

use App\Models\Tbl_Log;
use App\Models\Custom_Insert;
use App\Models\Custom_Migration;
use App\Models\Connection_Bexsoluciones;
use App\Traits\ConnectionTrait;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class ExportInformation extends Command
{
    use ConnectionTrait;

    protected $signature   = 'command:export-information {tenantDB} {connection_bs_id} {area} {id_importation?} {type?}';
    protected $description = 'Export information to bex solutions databases';

    public function handle() : int
    {
        try {
            $tenantDB       = $this->argument('tenantDB'); 
            $conectionBex   = $this->argument('connection_bs_id');
            $area           = $this->argument('area');
            $id_importation = $this->input->hasArgument('id_importation') ? $this->argument('id_importation') : null;
            $type           = $this->input->hasArgument('type') ? $this->argument('type') : null;

            //Function that configures the database (ConnetionTrait).
            $configDB = $this->connectionDB($conectionBex, 'externa', $area); 
            if($configDB != 0){
                DB::connection('mysql')->table('tbl_log')->insert([
                    'id_table'    => $id_importation,
                    'type'        => $type,
                    'descripcion' => 'Commands::ExportInformation[handle()] => Conexion Externa: Linea '.__LINE__.'; '.$configDB,
                    'created_at'  => now(),
                    'updated_at'  => now()
                ]);
                return 1;
            }
         
            // Llamar un custom de manera dinamica
            $custom = "App\\Custom\\$tenantDB\\InsertCustom";
            
            // Verificar si el custom existe
            if (!class_exists($custom)) {
                Tbl_Log::create([
                    'id_table'    => $id_importation,
                    'type'        => $type,
                    'descripcion' => 'Commands::ExportInformation[handle()] => No existe el custom '.$custom
                ]);
                return 1;
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
            
            $configDB = $this->connectionDB($tenantDB, 'local');
            if($configDB != 0){
                DB::connection('mysql')->table('tbl_log')->insert([
                    'id_table'    => $id_importation,
                    'type'        => $type,
                    'descripcion' => 'Commands::ExportInformation[handle()] => Conexion Local: Linea '.__LINE__.'; '.$configDB,
                    'created_at'  => now(),
                    'updated_at'  => now()
                ]);
                return 1;
            }

            $nameTables = Custom_Migration::nameTables()->get();
            $methods    = Custom_Insert::methods()->get();
            
            // Unir los resultados por custom_inserts_id e id
            $customMethods = $nameTables->map(function ($nameTableItem) use ($methods) {
                // Encontrar el método correspondiente por id
                $matchingMethod = $methods->where('id', $nameTableItem->custom_inserts_id)->first();

                // Combinar las columnas name_table y method
                return (object) [
                    'name_table' => $nameTableItem->name_table,
                    'method' => optional($matchingMethod)->method, // Usar optional para manejar el caso en que no hay coincidencia
                ];
            });

            $conectionBex = Connection_Bexsoluciones::showConnectionBS($conectionBex, $area)->value('name');
            $conectionSys = null;

            foreach ($availableModels as $modelClass => $tableName) {
                $modelInstance = new $modelClass();
                $datosAInsertar = $modelInstance::get();

                if ($tableName == 't16_bex_inventarios') {
                    $conectionSys = 2;
                    $configDB = $this->connectionDB($conectionSys, 'externa', $area);
                    if ($configDB != 0) {
                        DB::connection('mysql')->table('tbl_log')->insert([
                            'id_table'    => $id_importation,
                            'type'        => $type,
                            'descripcion' => 'Commands::ExportInformation[handle()] => Conexion Externa: Linea '.__LINE__.'; '.$configDB,
                            'created_at'  => now(),
                            'updated_at'  => now()
                        ]);
                    }
                    $conectionSys = Connection_Bexsoluciones::showConnectionBS($conectionSys, $area)->value('name');
                }

                foreach ($customMethods as $method) {
                    $methodName = $method->method;
                    if ($tableName == $method->name_table) {
                        $insert = new $custom();
                        $insert->$methodName(
                            $conectionBex,
                            $conectionSys,
                            $datosAInsertar,
                            $id_importation,
                            $type,
                            $modelInstance,
                            $tableName
                        );
                        print "◘ Proceso $methodName Finalizado" . PHP_EOL;
                    }
                }
            }
            print '◘ Información Base de Datos '.$tenantDB.' Exportada.' . PHP_EOL;
            return 0;
        } catch (\Exception $e) {
            Tbl_Log::create([
                'id_table'    => $id_importation,
                'type'        => $type,
                'descripcion' => 'Commands::ExportInformation[handle()] => '.$e->getMessage()
            ]);
            return 1;
        }
    }
}

