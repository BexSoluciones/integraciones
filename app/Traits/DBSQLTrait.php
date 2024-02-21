<?php
namespace App\Traits;

use Exception;
use App\Models\Tbl_Log;
use App\Models\Connection;
use App\Traits\BackupFlatFileTrait;
use App\Traits\FlatFileTrait;
use App\Models\Ws_Consulta;
use App\Models\Connection_Bexsoluciones;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

trait DBSQLTrait {
    
    use BackupFlatFileTrait, FlatFileTrait;
    
    public function connectionDBSQL($config){
        try {
            Config::set('database.connections.' . $config->proxy_host,  [
                    'driver'    => 'mysql',
                    'host'      => $config->ipinterno,
                    'database'  => $config->proxy_host,
                    'username'  => $config->usuariointerno,
                    'password'  => $config->claveinterno,
                    'charset'   => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'prefix'    => '',
                ],
            );

            return 0;
        } catch (\Exception $e) {
            Tbl_Log::create([
                'id_table'    => $id_importation,
                'type'        => $type,
                'descripcion' => 'Traits::DBSQLTrait[connectionDBSQL()] => Error al conectar con la DB '.$config->proxy_host
            ]);
            return 1;
        }
    }

    public function sentencesDBSQL($config, $db, $id_importation, $type){
        try {
            $sentences = Ws_Consulta::getAll();
    
            //backup txt files
            $backupFlatFile = $this->backupFlatFile($db, true, $id_importation, $type);
            if($backupFlatFile != 0){
                Tbl_Log::create([
                    'id_table'    => $id_importation,
                    'type'        => $type,
                    'descripcion' => 'Traits::DBSQLTrait[sentencesDBSQL()] => '.$e->getMessage()
                ]);
                return 1;
            }
            
            foreach($sentences as $sentence){  
                $allData  = [];
                $responds = DB::connection($config->proxy_host)->select($sentence->sentencia);
                $respond  = json_decode(json_encode($responds),true);
                $allData[] = [
                    'data' => $respond,
                    'descripcion' => $sentence->descripcion,
                    'separador' => $config->separador
                ];
                $generateFlatFile = $this->generateFlatFile($allData, $db, $id_importation, $type);  
               
                if($generateFlatFile == 1){
                    return 1;
                }
            }
            $this->info('◘ Proceso archivos planos completado.');
            return true;

        } catch (\Exception $e) {
            Tbl_Log::create([
                'id_table'    => $id_importation,
                'type'        => $type,
                'descripcion' => 'Traits::DBSQLTrait[sentencesDBSQL()] => '.$e->getMessage()
            ]);
            return 1;
        }
    }
}