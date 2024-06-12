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
    
    public function loginToApi($config)
    {  
        $url = $config->url;
        $credentials ='';
        eval("\$credentials = $config->AreaTrabajo;");

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
                'descripcion' => 'Traits::ApiTrait[loginToApi()] => Error al configurar la conexión: ' . $e->getMessage(),
                'created_at'  => now(),
                'updated_at'  => now()
            ]);
            return 1;
        }
    }

    public function ConsultaApi($config,$db,$area,$token){

        try{
            $backupFlatFile = $this->backupFlatFile($db, true);
            if($backupFlatFile != 0){
                $this->info('Error copia de seguridad archivos panos');
                DB::connection('mysql')->table('tbl_log')->insert([
                    'descripcion' => 'Traits::ApiTrait[ConsultaApi()] =>  Error copia de seguridad archivos panos.',
                    'created_at'  => now(),
                    'updated_at'  => now()
                ]);
                return 1;
            }
        
            if($token){
                if($area == 'bexmovil'){
                    $sentence = Ws_Consulta::getAll();
                }elseif($area == 'bextramites'){
                    $sentence = Ws_Consulta::getAllBexTram();
                }else{
                    $this->info('◘ Por favor pasarle en el comando el area command:update-information '.$db. ' area?');
                    DB::connection('mysql')->table('tbl_log')->insert([
                        'descripcion' => 'Traits::ApiTrait[ConsultaApi()] =>  No se paso el atributo area en command:update-information '.$db. ' area?',
                        'created_at'  => now(),
                        'updated_at'  => now()
                    ]);
                    return 1;
                }
                foreach($sentence as $clave){
                    $timeoutInSeconds = 60;
                    $allData = [];
                    $clie = [];
                    $url = $clave->sentencia;
                    
                    $client = new Client();

                    $response = Http::connectTimeout(60)->withHeaders([
                        'Authorization' => 'Bearer ' . $token,
                        'Accept' => 'application/json', 
                    ])->timeout($timeoutInSeconds)->get($url);
            
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

        } catch (\Exception $e) {
            DB::connection('mysql')->table('tbl_log')->insert([
                'descripcion' => 'Traits::ApiTrait[ConsultaApi()] =>    n: ' . $e->getMessage(),
                'created_at'  => now(),
                'updated_at'  => now()
            ]);
            return 1;
        }
    }
}
