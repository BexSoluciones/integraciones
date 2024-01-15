<?php
namespace App\Traits;

use Exception;

use GuzzleHttp\Client;

use App\Models\Tbl_Log;
use App\Models\Connection;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait ApiTrait {
    
    public function loginToApi($db)
    {
        $url = 'http://corsanapi.loca.lt/rest/v1.0/api-corsan-pd/login';

        $credentials = [
            'email' => 'conexion.principal@corsan.com.co',
            'password' => 'bEX360 2663192#+!1',
        ];

        $client = new Client();

        try {
            $response = $client->post($url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $credentials,
            ]);

            // Verificar si la solicitud fue exitosa
            if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                // Obtener el token del encabezado "Authorization"
                $token = $response->getHeader('Authorization')[0];
                return $token;
            } else {
                return 'Error en la solicitud '.$response->getStatusCode();
            }
        } catch (\Exception $e) {
            DB::connection('mysql')->table('tbl_log')->insert([
                'descripcion' => 'Traits::ConnectionTrait[connectionDB()] => Error al configurar la conexión: ' . $e->getMessage(),
                'created_at'  => now(),
                'updated_at'  => now()
            ]);
            return 1;
        }
    }
}