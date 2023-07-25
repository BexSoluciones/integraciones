<?php
namespace App\Traits;

use Exception;
use App\Models\Connection;
use Illuminate\Support\Facades\DB;

trait ConnectionTrait {
    
    public function connectionDB($dataConnection){

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
        } catch (\Exception $e) {
            $this->error('Error al configurar la conexión: ' . $e->getMessage());
        }
    }
}