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

    public function handle(): int
    {
        try {
            $tenantDB       = $this->argument('tenantDB');
            $conectionBex   = $this->argument('connection_bs_id');
            $area           = $this->argument('area');
            $id_importation = $this->argument('id_importation') ?? null;
            $type           = $this->argument('type') ?? null;

            $configDB = $this->setupDatabaseConnection($conectionBex, 'externa', $area);
            if ($configDB !== 0) {
                $this->insertLog($id_importation, $type, "Commands::ExportInformation[handle()] => Conexion Externa: Linea " . __LINE__ . "; $configDB");
                return 1;
            }

            $custom = "App\\Custom\\$tenantDB\\InsertCustom";
            if (!$this->customClassExists($custom)) {
                $this->insertLog($id_importation, $type, 'Commands::ExportInformation[handle()] => No existe el custom ' . $custom);
                return 1;
            }

            $customInstance = app()->make($custom);

            $baseNamespace  = 'App\\Models\\' . ucfirst($tenantDB) . '\\';
            $availableModels = $this->getAvailableModels($baseNamespace, $tenantDB);

            $configDB = $this->setupDatabaseConnection($tenantDB, 'local');
            if ($configDB !== 0) {
                $this->insertLog($id_importation, $type, "Commands::ExportInformation[handle()] => Conexion Local: Linea " . __LINE__ . "; $configDB");
                return 1;
            }

            $nameTables = Custom_Migration::nameTables()->get();
            $methods    = Custom_Insert::methods()->get();

            $customMethods = $this->combineCustomMethods($nameTables, $methods);

            $conectionBex = Connection_Bexsoluciones::showConnectionBS($conectionBex, $area)->value('name');
            $conectionSys = null;

            foreach ($availableModels as $modelClass => $tableName) {
                $modelInstance = new $modelClass;
                $datosAInsertar = $modelInstance::get();

                if ($tableName == 't16_bex_inventarios') {
                    $conectionSys = 2;
                    $configDB = $this->setupDatabaseConnection($conectionSys, 'externa', $area);
                    if ($configDB !== 0) {
                        $this->insertLog($id_importation, $type, "Commands::ExportInformation[handle()] => Conexion Externa: Linea " . __LINE__ . "; $configDB");
                    }
                    $conectionSys = Connection_Bexsoluciones::showConnectionBS($conectionSys, $area)->value('name');
                }

                foreach ($customMethods as $method) {
                    $methodName = $method->method;
                    if ($tableName == $method->name_table) {
                        $this->performCustomInsert($custom, $methodName, $conectionBex, $conectionSys, $datosAInsertar, $id_importation, $type, $modelInstance, $tableName);
                        $this->info("◘ Proceso $methodName Finalizado");
                    }
                }
            }

            $this->info("◘ Información Base de Datos $tenantDB Exportada.");
            return 0;
        } catch (\Exception $e) {
            $this->insertLog($id_importation, $type, 'Commands::ExportInformation[handle()] => ' . $e->getMessage());
            return 1;
        }
    }

    private function setupDatabaseConnection($connection, $type, $area = null)
    {
        return $this->connectionDB($connection, $type, $area);
    }

    private function insertLog($id_importation, $type, $description)
    {
        Tbl_Log::create([
            'id_table'    => $id_importation,
            'type'        => $type,
            'descripcion' => $description,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }

    private function customClassExists($custom)
    {
        return class_exists($custom);
    }

    private function getAvailableModels($baseNamespace, $tenantDB)
    {
        $modelFiles = File::files(app_path('Models/' . ucfirst($tenantDB)));
        $availableModels = [];

        foreach ($modelFiles as $modelFile) {
            $routeName = $baseNamespace . pathinfo($modelFile->getFilename(), PATHINFO_FILENAME);
            if (class_exists($routeName)) {
                $availableModels[$routeName] = (new $routeName())->getTable();
            }
        }

        return $availableModels;
    }

    private function combineCustomMethods($nameTables, $methods)
    {
        return $nameTables->map(function ($nameTableItem) use ($methods) {
            $matchingMethod = $methods->where('id', $nameTableItem->custom_inserts_id)->first();
            return (object) [
                'name_table' => $nameTableItem->name_table,
                'method' => optional($matchingMethod)->method,
            ];
        });
    }

    private function performCustomInsert($custom, $methodName, $conectionBex, $conectionSys, $datosAInsertar, $id_importation, $type, $modelInstance, $tableName)
    {
        $insert = new $custom();
        $insert->$methodName($conectionBex, $conectionSys, $datosAInsertar, $id_importation, $type, $modelInstance, $tableName);
    }
}

