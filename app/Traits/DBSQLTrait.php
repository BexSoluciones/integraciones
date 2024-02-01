<?php
namespace App\Traits;

use Exception;
use App\Models\Tbl_Log;
use App\Models\Connection;
use App\Models\Connection_Bexsoluciones;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

trait DBSQLTrait {
    
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

            $x = DB::connection($config->proxy_host)->table('pdvmovca')->where('nrodoc', 9648)->first();
            dd($x);
            return 0;

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