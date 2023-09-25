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
            $config = Ws_Config::getConnectionForId(1);
            
            //backup txt files
            $backupFlatFile = $this->backupFlatFile($db, true);
            if($backupFlatFile != true){
                dd($info->error('Error copia de seguridad archivos panos'));
            }
          
            $allData = [];
            foreach($sentences as $sentence){
                
                // Convertion of sentence defined in the ConversionSentencesSqlTrait
                $sentenceSQL = $this->convertionSentenceSql($sentence->f2_sentencia, 
                                                            $config->f1_IdCia, 
                                                            $sentence->f2_desde, 
                                                            $sentence->f2_cuantos,
                                                            $sentence->f2_IdConsulta);

                //StructureXML function defined in the WebServiceSiesaTrait
                $xml = $this->structureXML($config->f1_NombreConexion, 
                                            $config->f1_IdCia, 
                                            $config->f1_IdProveedor,
                                            $config->f1_Usuario, 
                                            $config->f1_Clave, 
                                            $sentenceSQL, 
                                            $config->f1_IdConsulta, 1, 0);
                if($xml){
                    $this->info('◘ Archivo XML '.$sentence->f2_IdConsulta.' generado');
                }
                               
                // SOAP function defined in the WebServiceSiesaTrait
                $results = $this->SOAP($config->f1_url, $xml, $sentence->f2_IdConsulta); 
                if($results == null){
                    //Devuelve la copia de seguridad a la carpeta principal
                    $this->backupFlatFile($db, false);
                    $this->error('Proceso detenido, la extraccion de datos no puede ser nula');
                    dd('------------ ERROR SOAP '.$sentence->f2_IdConsulta.' ------------');
                }

                if($results){
                    $this->info('◘ Importacion datos '.$sentence->f2_IdConsulta.' exitosa');
                }
                
                $data = json_decode($results, true);
                
                $allData[] = [
                    'data' => $data,
                    'descripcion' => $sentence->f2_descripcion,
                    'separador' => $config->f1_separador
                ];

                //Fuction to generate flat file (FlatFileTrait)
                $this->generateFlatFile($allData, $db);
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