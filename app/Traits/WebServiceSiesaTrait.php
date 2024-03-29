<?php
namespace App\Traits;

use Exception;
use SoapClient;
use App\Models\Tbl_Log;
use Illuminate\Support\Facades\Log;

trait WebServiceSiesaTrait {

    public static function structureXML($NombreConexion, $IdCia, $IdProveedor, $Usuario, $Clave, $sentencia, $IdConsulta, $printError, $cacheWSDL,$proxy_host = null,$proxy_port = null) {
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
            if ($proxy_host != null || $proxy_host != '') {
                $parameters['proxy_host'] = $proxy_host;
            }
            if ($proxy_port != null || $proxy_port != '') {
                $parameters['proxy_port'] = $proxy_port;
            }

            return $parameters;
        } catch (\Exception $e) {
            return ('Error al generar XML: '.$e->getMessage());
        }
    }
    
    public static function SOAP($url, $parameters, $IdConsulta, $timeout = 20.0) {
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
                    $reg = new Tbl_Log;
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
                echo 'El proceso ha finalizado porque la consulta '.$IdConsulta.' expiro en tiemo de espera ('. $executionTime. ' segundos).';
                return null;
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

    public function importarXml($xml,$url)
    {

        try
        {
            $parm = array(); //parm de la llamada
            $parm['pvstrDatos'] = $xml;
            $parm['printTipoError'] = '1';
            $parm['cache_wsdl'] = 0; //new
            $client = new SoapClient($url, $parm);
            $result = $client->ImportarXML($parm); //llamamos al métdo que nos interesa con los parámetros
            
            $schema = simplexml_load_string($result->ImportarXMLResult->schema);
            return $any = simplexml_load_string($result->ImportarXMLResult->any);

        } catch (\Exception $fault) {

            $error = $fault->getMessage();   
            Log::info('======este es mensaje de error de conexion ======'); 
            Log::error($error); 
            return [
                'conexion_exitosa'=>false,
                'error'=>$error
            ];        
        }

    }

}