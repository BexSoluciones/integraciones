<?php

namespace App\Console\Commands;

use App\Models\WsConfig;
use App\Models\Connection;
use App\Traits\ConnectionTrait;
use App\Traits\WebServiceSiesaTrait;

use Illuminate\Console\Command;

class Prueba extends Command{

    use WebServiceSiesaTrait, ConnectionTrait;

    protected $signature = 'app:prueba {database}';
    protected $description = 'Command description';

    public function handle() {

        $db = $this->argument('database'); 

        //Function that is executed in the ConnectionTrait for connection with BD
        $this->connectionDB($db);

        $sentencia = 'SET QUOTED_IDENTIFIER OFF;
        select top 100
        f200_nit "nit",
        ISNULL(f200_dv_nit, "") "dv",
        ISNULL(f201_id_sucursal, "") "suc",
        f210_id "vendedor",
        ISNULL(f207_id_plan_criterios, "") "plancri",
        ISNULL(f207_id_criterio_mayor, "") "crimay",
        ISNULL(f201_id_grupo_dscto, "") "grudcto",
        ISNULL(f201_id_tipo_cli, "") "tipocli",
        f201_rowid_tercero as codclientealt,
        f200_rowid as ws_id 
        FROM dbo.t200_mm_terceros
        INNER JOIN dbo.t201_mm_clientes
        ON f200_rowid = f201_rowid_tercero
        INNER JOIN t207_mm_criterios_clientes
        on t207_mm_criterios_clientes.f207_rowid_tercero = t201_mm_clientes.f201_rowid_tercero
        and t207_mm_criterios_clientes.f207_id_sucursal = t201_mm_clientes.f201_id_sucursal
        INNER JOIN t210_mm_vendedores
        ON t201_mm_clientes.f201_id_vendedor = t210_mm_vendedores.f210_id
        WHERE f200_id_cia = 1 AND
        f210_id_cia = 1 AND
        (f200_ind_cliente = 1 or
         f200_ind_empleado = 0)
        and f200_ind_estado = 1
        and f201_ind_estado_activo = 1
        SET QUOTED_IDENTIFIER ON;';

        //We pass the ID to configure the connection to the WS
        $config = WsConfig::getConnectionForId(1);
        
        //structureXML function defined in the WebServiceSiesaTrait
        $xml = $this->structureXML($config->NombreConexion,
                                    $config->IdCia,
                                    $config->IdProveedor,
                                    $config->Usuario,
                                    $config->Clave,
                                    $sentencia,
                                    $config->IdConsulta,
                                    1,0);

        //SOAP function defined in the WebServiceSiesaTrait
        $result = $this->SOAP($config->url, $xml);

        // Impression of the results
        if ($result != null) {
            foreach ($result as $key => $value) {
                echo "\n" . str_repeat("=", 20) . " RESULTADO : [ $key ] " . str_repeat("=", 20) . "\n";
                foreach ($value as $keyA => $valueA) {
                    echo " ------> $valueA\n";
                }
            }
        } else {
            echo "NO SE ENCONTRARON RESULTADOS\n";
        }

        echo "\n" . str_repeat("=", 40) . " DUMP " . str_repeat("=", 40) . "\n";
        print_r($result);
      
    }
}
