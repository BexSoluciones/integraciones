<?php
namespace App\Traits;

use Exception;

use App\Jobs\ProcessOrderUploadERP;
use App\Models\Tbl_Log;
use App\Models\OrderDetail;
use App\Models\OrderHeader;
use App\Models\Ws_Unoee_Config;
use App\Models\Connection_Bexsoluciones;
use App\Traits\ConnectionTrait;
use App\Models\Custom_Sql;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

trait ConsultOrderTrait {
    use ConnectionTrait;

    public function createXmlOrder($lines, $nummov, $config){
        try {
            $xmlPedido = "<?xml version='1.0' encoding='utf-8'?>
            <Importar>
            <NombreConexion>" . $config->NombreConexion . "</NombreConexion>
            <IdCia>" . $config->IdCia . "</IdCia>
            <Usuario>" . $config->Usuario . "</Usuario>
            <Clave>" . $config->Clave . "</Clave>
            <Datos>\n";
            $datos = "";
            foreach ($lines as $key => $linea) {
                $xmlPedido .= "        <Linea>" . $linea . "</Linea>\n";
                $datos .= "        <Linea>" . $linea . "</Linea>\n";

            }
            $xmlPedido .= "        </Datos>
            </Importar>";
            print_r($xmlPedido);
            return $xmlPedido;
            
        }catch (\Exception $e) {
            Tbl_Log::create([
                'descripcion' => 'Trait::ConsultOrderTrait[createXmlOrder()] => '.$e->getMessage()
            ]);
        }
    }

    
}