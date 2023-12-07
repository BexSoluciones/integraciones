<?php
namespace App\Traits;

use Exception;
use App\Models\Tbl_Log;
use App\Models\Connection;
use App\Models\Connection_Bexsoluciones;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

trait ConnectionTrait {
    
    public function connectionDB($db, $type, $system = false){

        if($system){
            $dataConnection = Connection_Bexsoluciones::getAll()->where('id', 2)->first();
        } else {
            if($type == 'local'){
                $dataConnection = Connection::getAll()->where('id', $db->id)->first(); //Verify that the database exists
            }else{
                $dataConnection = Connection_Bexsoluciones::getAll()->where('id', $db->id)->first();
            }
        }
        
        if (!$dataConnection) {
            Tbl_Log::create([
                'descripcion' => 'ConnectionTrait[connectionDB()] => La base de datos '.$db->name.' no existe en la tabla connections.'
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
            $connectionName = $type == 'local' ? $dataConnection->id : 'dynamic_connection';
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

            // DB::purge('mysql');

            return true;
        } catch (\Exception $e) {
            Tbl_Log::create([
                'descripcion' => 'ConnectionTrait[connectionDB()] => Error al configurar la conexión: ' . $e->getMessage()
            ]);
           return false;
        }
    }
}