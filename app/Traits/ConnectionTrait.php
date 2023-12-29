<?php
namespace App\Traits;

use Exception;
use App\Models\Tbl_Log;
use App\Models\Connection;
use App\Models\Connection_Bexsoluciones;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

trait ConnectionTrait {
    
    public function connectionDB($db, $type, $area = null){

        if($type == 'local'){
            $dataConnection = Connection::getAll()->where('name', $db)->first();
            if (!$dataConnection) {
                Tbl_Log::create([
                    'descripcion' => 'Traits::ConnectionTrait[connectionDB()] => El name '.$db.' no existe en la tabla connections.'
                ]);
                return 1;
            }
            
            if($dataConnection->active != 1){
                Tbl_Log::create([
                    'descripcion' => 'Traits::ConnectionTrait[connectionDB()] => El usuario '.$db.' esta inactivo en la tabla connections.'
                ]);
                return 1; 
            }
        }else{
            $dataConnection = Connection_Bexsoluciones::showConnectionBS($db, $area)->first();
            if (!$dataConnection) {
                Tbl_Log::create([
                    'descripcion' => 'Traits::ConnectionTrait[connectionDB()] => El id '.$db.' del area '.$area.' no existe en la tabla connection_bexsoluciones.'
                ]);
                return 1;
            }
            
            if($dataConnection->active != 1){
                Tbl_Log::create([
                    'descripcion' => 'Traits::ConnectionTrait[connectionDB()] => El id '.$db.' del area '.$area.' esta inactivo en la tabla connection_bexsoluciones.'
                ]);
                return 1; 
            }
        }
       
        try {
            $connectionName = $type != 'local' ? $dataConnection->name : 'dynamic_connection';
            Config::set('database.connections.' . $connectionName,  [
                    'driver'    => 'mysql',
                    'host'      => $dataConnection->host,
                    'database'  => $dataConnection->name,
                    'username'  => $dataConnection->username,
                    'password'  => $dataConnection->password,
                    'charset'   => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'prefix'    => '',
                ],
            );
            return 0;
        } catch (\Exception $e) {
            Tbl_Log::create([
                'descripcion' => 'Traits::ConnectionTrait[connectionDB()] => Error al configurar la conexión: ' . $e->getMessage()
            ]);
            return 1;
        }
    }
}