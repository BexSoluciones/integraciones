<?php
namespace App\Traits;

use Exception;

use App\Models\Ws_Config;
use App\Models\Connection;
use App\Models\Ws_Consulta;

use App\Traits\FlatFileTrait;
use App\Traits\ConnectionTrait;
use App\Traits\BackupFlatFileTrait;
use App\Traits\WebServiceSiesaTrait;
use App\Traits\TemporalPandapanTrait;
use App\Traits\ConversionSentencesSqlTrait;

use Illuminate\Support\Facades\DB;

trait DataImportTrait {

    use WebServiceSiesaTrait, ConversionSentencesSqlTrait, FlatFileTrait, BackupFlatFileTrait;

    public function importData($db){

        try {
            // All SQL statements
            $sentences = Ws_Consulta::getAll();

            // We pass the ID to configure the connection to the WS
            $config = Ws_Config::where('estado', 1)->first();
            
            //backup txt files
            $backupFlatFile = $this->backupFlatFile($db, true);
            if($backupFlatFile != 0){
                $this->info('Error copia de seguridad archivos panos');
                dd();
            }
          
            foreach($sentences as $sentence){
                $continuar = true;
                $desde=$sentence->desde;
                while($continuar == true){
                    $allData = [];
                    
                    // Convertion of sentence defined in the ConversionSentencesSqlTrait
                    $sentenceSQL = $this->convertionSentenceSql($sentence->sentencia, 
                                                                $config->IdCia, 
                                                                $desde, 
                                                                $sentence->cuantos,
                                                                $sentence->IdConsulta);

                    //StructureXML function defined in the WebServiceSiesaTrait
                    $xml = $this->structureXML($config->NombreConexion, 
                                                $config->IdCia, 
                                                $config->IdProveedor,
                                                $config->Usuario, 
                                                $config->Clave, 
                                                $sentenceSQL, 
                                                $config->IdConsulta, 1, 0,$config->proxy_host,$config->proxy_port);
                    if($xml){
                        $this->info('◘ Archivo XML '.$sentence->IdConsulta.' generado');
                    }

                    // SOAP function defined in the WebServiceSiesaTrait
                    $results = $this->SOAP($config->url, $xml, $sentence->IdConsulta); 
                    if($results == null){
                        //Devuelve la copia de seguridad a la carpeta principal
                        $continuar = false;
                        // $this->backupFlatFile($db, false);
                        // $this->error('Proceso detenido, la extraccion de datos no puede ser nula');
                        // dd('------------ ERROR SOAP '.$sentence->IdConsulta.' ------------');
                    }else{
                        $continuar = true;
                        $data = json_decode($results, true);
                        $allData[] = [
                            'data' => $data,
                            'descripcion' => $sentence->descripcion,
                            'separador' => $config->separador
                        ];
    
                        //Fuction to generate flat file (FlatFileTrait)
                        $this->generateFlatFile($allData, $db);
                        $desde= $desde+$sentence->cuantos;
                    }

                    if($results){
                        $this->info('◘ Importacion datos '.$sentence->IdConsulta.' exitosa');
                    }

                }
           
            }
            $this->info('◘ Proceso archivos planos completado.');
            return true;
        } catch (\Exception $e) {
            $this->error("Error DataImportTrait: " . $e->getMessage());
            //Devuelve la copia de seguridad a la carpeta principal
            $this->backupFlatFile($db, false);
        }
    }
}