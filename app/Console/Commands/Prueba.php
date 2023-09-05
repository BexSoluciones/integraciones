<?php

namespace App\Console\Commands;

use App\Models\Ws_Config;
use App\Models\Connection;
use App\Models\Ws_Consulta;
use App\Traits\ConnectionTrait;
use App\Traits\TemporalPandapanTrait;
use App\Traits\WebServiceSiesaTrait;

use Illuminate\Console\Command;

class Prueba extends Command{

    use WebServiceSiesaTrait, ConnectionTrait, TemporalPandapanTrait;

    protected $signature = 'app:prueba {database}';
    protected $description = 'Command description';

    public function handle() {

        $db = $this->argument('database'); 

        //Function that is executed in the ConnectionTrait for connection with BD
        $this->connectionDB($db);

        //All SQLs statements
        $sentencias = Ws_Consulta::getAll();
        
        //We pass the ID to configure the connection to the WS
        $config = Ws_Config::getConnectionForId(1);

        foreach($sentencias as $sentencia){

            $sentenciaPrueba = 'SET QUOTED_IDENTIFIER OFF;
            SELECT top 1 f131_id as codbarra,
            f120_id as codproducto,
            f131_cant_unidad_medida as cantidad
            FROM t131_mc_items_barras
            INNER JOIN t121_mc_items_extensiones on f131_rowid_item_ext = f121_rowid
            INNER JOIN t120_mc_items on f121_rowid_item = f120_rowid 
            WHERE f131_id_cia = 1
            SET QUOTED_IDENTIFIER ON;';

            //structureXML function defined in the WebServiceSiesaTrait
            $xml = $this->structureXML($config->NombreConexion,
                                    $config->IdCia,
                                    $config->IdProveedor,
                                    $config->Usuario,
                                    $config->Clave,
                                    $sentenciaPrueba,//$sentencia->sentencia,
                                    $config->IdConsulta,
                                    1,0);
            //SOAP function defined in the WebServiceSiesaTrait
            $results = $this->SOAP($config->url, $xml);

            // Impression of the results
            if ($results != null) {
                foreach ($results as $result) {
                    //Function that is executed in TableTemporalTrait for insert information in tables temporalys.
                    $this->insert($sentencia->IdConsulta, $result);
                }
            } else {
                $this->info("No se encontraron resultados");
            }

            $this->info("\n" . str_repeat("=", 40) . " Ciclo Table ".$sentencia->IdConsulta." Finalizado " . str_repeat("=", 40) . "\n");
        }
    }
}
