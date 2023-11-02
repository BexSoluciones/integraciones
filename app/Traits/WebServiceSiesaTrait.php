<?php
namespace App\Traits;

use Exception;
use SoapClient;
use App\Models\Tbl_log;

trait WebServiceSiesaTrait {

    public static function structureXML($NombreConexion, $IdCia, $IdProveedor, $Usuario, $Clave, $sentencia, $IdConsulta, $printError, $cacheWSDL) {
        try {
            $parameters = [
                'printTipoError'     => $printError,
                'cache_wsdl'         => $cacheWSDL,
                'pvstrxmlParametros' => "<Consulta>
                                            <NombreConexion>{$NombreConexion}</NombreConexion>
                                            <IdCia>{$IdCia}</IdCia>
                                            <IdProveedor>{$IdProveedor}</IdProveedor>
                                            <IdConsulta>{$IdConsulta}</IdConsulta>
                                            <Usuario>{$Usuario}</Usuario>
                                            <Clave>{$Clave}</Clave>
                                            <Parametros>
                                                <Sql>{$sentencia}</Sql>
                                            </Parametros>
                                        </Consulta>"
            ];

            return $parameters;
        } catch (\Exception $e) {
            return ('Error al generar XML: '.$e->getMessage());
        }
    }
    
    public static function SOAP($url, $parameters, $IdConsulta, $timeout = 5.0) {
        $finish = 1;
        do {
            $startTime = microtime(true); // Registrar el tiempo de inicio
    
            try {
                $client = new \SoapClient($url, $parameters);
                $result = $client->EjecutarConsultaXML($parameters)->EjecutarConsultaXMLResult->any;
                $any = simplexml_load_string($result);
                if (@is_object($any->NewDataSet->Resultado)) {
                    return self::convertirObjetosArrays($any->NewDataSet->Resultado);
                } else {
                    $finish = 0;
                }
                if (@$any->NewDataSet->Table) {
                    foreach ($any->NewDataSet->Table as $key => $value) {
                        echo ("\n");
                        echo ("\n Error Linea:\t " . $value->F_NRO_LINEA);
                        echo ("\n Error Value:\t " . $value->F_VALOR);
                        echo ("\n Error Desc:\t " . $value->F_DETALLE);
                    }
                }
            } catch (\Exception $e) {
                $error = self::errorSOAP($e->getMessage());
                if ($error == true) {
                    $reg = new Tbl_log;
                    $reg->descripcion =  'CONSULTA => '.$e->getMessage();
                    if ($reg->save()) {
                        $finish = 0;
                    } else {
                        echo 'CONSULTA =>  Excepción capturada: ', $e->getMessage(), "\n";
                    }
                }
            }
    
            $endTime = microtime(true); // Registrar el tiempo de finalización
            $executionTime = $endTime - $startTime;
    
            if ($executionTime > $timeout) {
                // La consulta se ha demorado más de lo esperado
                return ('El proceso ha finalizado porque la consulta '.$IdConsulta.' expiro en tiemo de espera ('. $executionTime. ' segundos).');
            }
        } while ($finish != 0);
    }
    

    public static function convertirObjetosArrays($objetos){       
        $arrayValues = [];
        foreach ($objetos as $objeto) {
            $arrayValuesRow = [];
            foreach ($objeto as $keyb => $valores) {
                // Aplica htmlspecialchars solo si $valores no es nulo
                $value = is_null($valores) ? null : htmlspecialchars(trim($valores), ENT_QUOTES, 'UTF-8');
                $arrayValuesRow[$keyb] = $value;
            }
            $arrayValues[] = $arrayValuesRow;
        }
        return json_encode($arrayValues);
    }
    
    public static function errorSOAP($error){
        if ($error == 'Server was unable to process request. ---> Error al conectarse a la base de datos.Timeout expired.  The timeout period elapsed prior to obtaining a connection from the pool.  This may have occurred because all pooled connections were in use and max pool size was reached.' 
            || strpos($error, "SOAP-ERROR: Parsing WSDL: Couldn't load from") != false 
            || $error == 'Error Fetching http headers' 
            || $error == 'Server was unable to process request. ---> El parámetro Sql es obligatorio y no existe en la lista de parámetros.') 
            {
                return false;
            }else{ 
                return true; 
            }
    }
}