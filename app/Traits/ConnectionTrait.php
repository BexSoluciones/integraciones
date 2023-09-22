<?php
namespace App\Traits;

use Exception;
use App\Models\Connection;

use Illuminate\Support\Facades\DB;

trait ConnectionTrait {
    
    public function connectionDB($db){

        $dataConnection = Connection::where('alias', $db)->first(); //Verify that the database exists

        if (!$dataConnection) {
            $this->error("La base de datos '$db' no existe en la tabla 'connections'.");
            return;
        }

        try {
            // Database configuration
            config([
                'database.connections.dynamic_connection' => [
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
            $this->info('◘ Conexion a Base de Datos '.$db.' realizada con exito');
        } catch (\Exception $e) {
            $this->error('Error al configurar la conexión: ' . $e->getMessage());
        }
    }
}