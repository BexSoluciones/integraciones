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
            //Verify that the database exists
            $dataConnection = Connection::getAll()
                ->where('name', $db)
                ->first();
        }else{
            $dataConnection = Connection_Bexsoluciones::getAll()->where('id', $db)->first();
        }
        
        if (!$dataConnection) {
            Tbl_Log::create([
                'descripcion' => 'Traits::ConnectionTrait[connectionDB()] => La base de datos '.$db.' no existe en la tabla connections.'
            ]);
            return false;
        }
     
        if($dataConnection->active != 1 && $type != 'local'){
            Tbl_Log::create([
                'descripcion' => 'Traits::ConnectionTrait[connectionDB()] => El cliente '.$db.' esta en estado inactivo.'
            ]);
            return false; 
        }
        
        try {
            $connectionName = $type != 'local' ? $dataConnection->name : 'dynamic_connection';
            // Database configuration
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
            return true;
        } catch (\Exception $e) {
            Tbl_Log::create([
                'descripcion' => 'Traits::ConnectionTrait[connectionDB()] => Error al configurar la conexión: ' . $e->getMessage()
            ]);
           return false;
        }
    }
}