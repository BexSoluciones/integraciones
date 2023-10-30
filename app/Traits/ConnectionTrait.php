<?php
namespace App\Traits;

use Exception;
use App\Models\Connection;
use App\Models\Connection_Bexsoluciones;

use Illuminate\Support\Facades\DB;

trait ConnectionTrait {
    
    public function connectionDB($db, $area = null){
        if($area != null){
            $dataConnection = Connection_Bexsoluciones::getAll()->where('alias', $db)->first();
        }else{
            $dataConnection = Connection::getAll()->where('name', $db)->first(); //Verify that the database exists
        }


        if (!$dataConnection) {
            $this->error("La base de datos '$db' no existe en la tabla 'connections'.");
            return false;
        }

        if($dataConnection->active != 1){
            $this->error("El cliente '$db' esta en estado inactivo");
            return false; 
        }

        try {
            $connectionName = $area != null ? $dataConnection->alias : 'dynamic_connection';

            // Database configuration
            config([
                'database.connections.' . $connectionName => [
                    'driver'    => 'mysql',
                    'host'      => $dataConnection->host,
                    'database'  => $dataConnection->name,
                    'username'  => $dataConnection->username,
                    'password'  => $dataConnection->password,
                    'charset'   => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'prefix'    => '',
                ],
            ]);

            $this->info('◘ Conexion a Base de Datos ' . $db . ' realizada con éxito');
            return true;
        } catch (\Exception $e) {
            $this->error('Error al configurar la conexión: ' . $e->getMessage());
        }
    }
}