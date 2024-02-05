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
    
    public function connectionDBSQL($config,$db){
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

            $sentences = Ws_Consulta::getAll();

            //backup txt files
            $backupFlatFile = $this->backupFlatFile($db, true);
            if($backupFlatFile != 0){
                $this->info('Error copia de seguridad archivos panos');
                dd();
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
                $this->generateFlatFile($allData,$db);  

            }
            $this->info('◘ Proceso archivos planos completado.');
            return true;

        } catch (\Exception $e) {
            DB::connection('mysql')->table('tbl_log')->insert([
                'descripcion' => 'Traits::DBSQLTrait[connectionDBSQL()] => Error al configurar la conexión: ' . $e->getMessage(),
                'created_at'  => now(),
                'updated_at'  => now()
            ]);
            return 1;
        }
    }
}