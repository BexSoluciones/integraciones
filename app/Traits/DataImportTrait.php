<?php
namespace App\Traits;

use Exception;

use App\Models\Ws_Config;
use App\Models\Connection;
use App\Models\Ws_Consulta;
use App\Traits\ConnectionTrait;
use App\Traits\WebServiceSiesaTrait;
use App\Traits\TemporalPandapanTrait;
use App\Traits\ConversionSentencesSqlTrait;

use Illuminate\Support\Facades\DB;

trait DataImportTrait {

    use WebServiceSiesaTrait, ConversionSentencesSqlTrait;

    public function importData(){

        //All SQLs statements
        $sentences = Ws_Consulta::getAll();
        
        //We pass the ID to configure the connection to the WS
        $config = Ws_Config::getConnectionForId(1);
        
        $allData = [];
        foreach($sentences as $sentence){
            //Convertion of sentence defined in the ConversionSentencesSqlTrait
            $sentenceSQL = $this->convertionSentenceSql($sentence->sentencia, $config->IdCia, $sentence->desde, $sentence->cuantos);
            
            //StructureXML function defined in the WebServiceSiesaTrait
            $xml = $this->structureXML($config->NombreConexion, $config->IdCia, $config->IdProveedor,
                                       $config->Usuario, $config->Clave, $sentenceSQL, 
                                       $config->IdConsulta, 1,0);
            
            //SOAP function defined in the WebServiceSiesaTrait
            $results = $this->SOAP($config->url, $xml);
        
            $data = json_decode($results, true);
            
            $allData[] = [
                'data' => $data,
                'consulta_id' => $sentence->IdConsulta,
                'separador' => $config->separador
            ];

            return $allData;
        }
    }
}