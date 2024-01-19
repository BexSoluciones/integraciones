<?php
namespace App\Traits;

use Exception;

use GuzzleHttp\Client;
use App\Traits\FlatFileTrait;
use App\Traits\BackupFlatFileTrait;
use Illuminate\Support\Facades\Http;

use App\Models\Tbl_Log;
use App\Models\Connection;
use App\Models\Ws_Consulta;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait ApiTrait {

    use FlatFileTrait,BackupFlatFileTrait;
    
    public function loginToApi($config,$db)
    {
        //backup txt files
        $backupFlatFile = $this->backupFlatFile($db, true);
        if($backupFlatFile != 0){
            $this->info('Error copia de seguridad archivos panos');
            dd();
        }
        $url = $config->url;
  
        if($config->IdProveedor == 'corsan'){
            $credentials = [
                'email' => $config->NombreConexion,
                'password' => $config->Clave
            ];
        }else{
            return 0;
        }
 
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
                if($token){
                    $sentence = Ws_Consulta::getAll();
                    foreach($sentence as $clave){
                        $allData = [];
                        $clie = [];
                        $url = $clave->sentencia;
                        
                        $client = new Client();
                        $response = Http::withHeaders([
                            'Authorization' => 'Bearer ' . $token,
                            'Accept' => 'application/json', 
                        ])->get($url);
                
                        $respon = $response->json();
                        $data = json_decode(json_encode($respon), true);
                        if($respon == null){
                            //Devuelve la copia de seguridad a la carpeta principal
                            $this->backupFlatFile($db, false);
                            $this->error('Proceso detenido, la extraccion de datos no puede ser nula');
                            dd('------------ ERROR API '.$clave->IdConsulta.' ------------');
                        }
                        foreach($data['respuesta'] as $key){
                            eval("\$clie[] = $clave->parametro;");                        
                        }
  
                        $allData[] = [
                            'data' => $clie,
                            'descripcion' => $clave->descripcion,
                            'separador' => $config->separador
                        ];
                        $this->generateFlatFile($allData,$db);
                        
                    }
                    $this->info('◘ Proceso archivos planos completado.');
                    return true;
                }else{
                    return 'El token no esta definido';
                }
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