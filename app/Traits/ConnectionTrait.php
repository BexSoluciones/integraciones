<?php
namespace App\Traits;

use Exception;
use App\Models\Tbl_Log;
use App\Models\Connection;
use App\Models\Connection_Bexsoluciones;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

trait ConnectionTrait {
    
    public function connectionDB($db, $area = null){
        if($area != null){
            $dataConnection = Connection_Bexsoluciones::getAll()->where('alias', $db)->first();
        }else{
            $dataConnection = Connection::getAll()->where('name', $db)->first(); //Verify that the database exists
        }

        if (!$dataConnection) {
            Tbl_Log::create([
                'descripcion' => 'ConnectionTrait[connectionDB()] => La base de datos '.$db.' no existe en la tabla connections.'
            ]);
            return false;
        }

        if($dataConnection->active != 1){
            Tbl_Log::create([
                'descripcion' => 'ConnectionTrait[connectionDB()] => El cliente '.$db.' esta en estado inactivo.'
            ]);
            return false; 
        }

        try {
            $connectionName = $area != null ? $dataConnection->alias : 'dynamic_connection';


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

            $connetion = Config::get('database.connections.dynamic_connection');

            return $connetion;

            DB::purge('mysql');

            return true;
        } catch (\Exception $e) {
            Tbl_Log::create([
                'descripcion' => 'ConnectionTrait[connectionDB()] => Error al configurar la conexión: ' . $e->getMessage()
            ]);
           return false;
        }
    }
}