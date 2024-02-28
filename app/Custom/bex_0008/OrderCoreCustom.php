<?php

namespace App\Custom\bex_0008;

use App\Models\Tbl_Log;
use App\Models\Ws_Config;
use App\Models\Connection;
use App\Models\Connection_Bexsoluciones;

use Illuminate\Support\Facades\DB;
use App\Models\LogErrorImportacionModel;
use App\Traits\ConnectionTrait;
use App\Traits\ConsultOrderTrait;
use App\Traits\WebServiceSiesaTrait;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class OrderCoreCustom
{
    use ConnectionTrait, ConsultOrderTrait, WebServiceSiesaTrait;

    public function uploadOrder($orders, $cia, $closing, $connection_id)
    {
        $id_connection = Connection_Bexsoluciones::where('id', $connection_id)->value('connection_id');
        $db = Connection::where('id', $id_connection)->value('name');

        $configDB = $this->connectionDB($db, 'local'); 
        if($configDB != 0){
            DB::connection('mysql')->table('tbl_log')->insert([
                'id_table'    => null,
                'type'        => null,
                'descripcion' => 'Commands::OrderCoreCustom[uploadOrder()] => Conexion Local: Linea '.__LINE__.'; '.$configDB,
                'created_at'  => now(),
                'updated_at'  => now()
            ]);
            return 1;
        }
        
        $config = Ws_Config::where('estado', 1)->first();
       
        try{
            if (count($orders) > 0) {
                foreach($orders as $order){
                    // {PENDIENTE VENDEDORES Y CLIENTES}
                    $import = true;
                    $chain = "";
                    $chain .= str_pad(1, 7, "0", STR_PAD_LEFT) . "00000001".str_pad($config['IdCia'],3,"0",STR_PAD_LEFT)."\n"; 
                    $chain .= str_pad('2', 7, "0", STR_PAD_LEFT); //Numero de registros
                    $chain .= str_pad('430', 4, "0", STR_PAD_LEFT); //Tipo de registro [430->es un peido, 461->factura encabezado, 470->factura detail]
                    $chain .= '00'; //Subtipo de registro
                    $chain .= '02'; //version del tipo de registro
                    $chain .= str_pad($config['IdCia'],3,"0",STR_PAD_LEFT); //Compañia
                    $chain .= '1'; //Indicador para liquidar impuestos
                    $chain .= '1'; //Indica si el numero consecutivo de docto es manual o automático
                    $chain .= '1'; //Indicador de contactodd
                    $chain .= $order->co; //Centro de operación del documento
                    $chain .= $order->tipodoc; //Tipo de documento
                    $chain .= str_pad($order->nummov, 8, "0", STR_PAD_LEFT); //Numero documento
                    $chain .= substr($order->fecmov, 0, 4).substr($order->fecmov, 5, 2).substr($order->fecmov, 8, 2); //Fecha del documento
                    $chain .= '502'; //Clase interna del documento
                    $chain .= '2'; //Estado del documento
                    $chain .= '0'; //Indicador backorder del documento
                    $chain .= str_pad($order->nitcliente, 15, " ", STR_PAD_RIGHT); //Tercero cliente a facturar
                    $chain .= str_pad($order->succliente, 3, "0", STR_PAD_LEFT); //Sucursal cliente a facturar
                    $chain .= str_pad($order->nitcliente, 15, " ", STR_PAD_RIGHT); //Tercero cliente a despachar
                    $chain .= str_pad($order->succliente, 3, "0", STR_PAD_LEFT); //Sucursal cliente a despachar
                    if($order->origen == 'B2B'){
                        $chain.=str_pad("0003",4," ",STR_PAD_LEFT);
                    }else{
                        $chain.=str_pad("CL01",4," ",STR_PAD_LEFT);
                    }
                    $chain .= str_pad($order->co,3,"0",STR_PAD_LEFT); //Centro de operacion de la factura
                    if($order->fechorentregacli >= $order->fecmov){
                        $chain.=substr($order->fechorentregacli,0,4).substr($order->fechorentregacli,5,2).substr($order->fechorentregacli,8,2);
                    }else{
                        $chain.=substr($order->fecmov,0,4).substr($order->fecmov,5,2).substr($order->fecmov,8,2);
                    }
                    $chain .= '000'; //Nro. dias de entrega del documento
                    if($order->ordendecompra != ''){
                        $chain .= str_pad($order->ordendecompra, 15, "Y", STR_PAD_LEFT); //Orden de compra del Documento
                    }else{
                        $chain .= str_pad($order->nummov, 15, "Y", STR_PAD_LEFT); //Orden de compra del Documento
                    }
                    $chain .= str_pad($order->nummov, 10, "0", STR_PAD_LEFT); //Referencia del documento
                    $chain .= str_pad('', 10, " ", STR_PAD_RIGHT); //Codigo de cargue del documento
                    $chain .= 'COP'; //Codigo de moneda del documento
                    $chain .= 'USD'; //Moneda base de conversión {PENDIENTE CONVERSION}
                    $chain .= '00000001.0000'; //Tasa de conversión
                    $chain .= 'COP'; //Moneda local
                    $chain .= '00000001.0000'; //Tasa local
                    $chain .= str_pad($order->codfpagovta, 3, " ", STR_PAD_LEFT); //Condicion de pago
                    $chain .= '0'; //Estado de impresión del documento
                    $chain .= str_pad(utf8_decode($order->mensajemov), 2000, " ", STR_PAD_RIGHT); //Observaciones del documento
                    $chain .= str_pad('', 15, " ", STR_PAD_LEFT); //cliente de contado
                    $chain .= '000'; //Punto de envio
                    $chain .= str_pad($order->cedula, 15, " ", STR_PAD_RIGHT); //Vendedor del pedido --nit vendedor 
                    $chain .= str_pad('', 50, " ", STR_PAD_RIGHT); //Contacto
                    $chain .= str_pad('', 40, " ", STR_PAD_RIGHT); //Direccion 1
                    $chain .= str_pad('', 40, " ", STR_PAD_RIGHT); //Direccion 2
                    $chain .= str_pad('', 40, " ", STR_PAD_RIGHT); //Direccion 3
                    $chain .= str_pad('', 3, " ", STR_PAD_RIGHT); //Pais
                    $chain .= str_pad('', 2, " ", STR_PAD_RIGHT); //Departamento/Estado
                    $chain .= str_pad('', 3, " ", STR_PAD_RIGHT); //Ciudad
                    $chain .= str_pad('', 40, " ", STR_PAD_RIGHT); //Barrio
                    $chain .= str_pad('', 20, " ", STR_PAD_RIGHT); //Telefono
                    $chain .= str_pad('', 20, " ", STR_PAD_RIGHT); //fax
                    $chain .= str_pad('', 10, " ", STR_PAD_RIGHT); //Codigo postal
                    $chain .= str_pad('', 50, " ", STR_PAD_RIGHT); //E-mail
                    $chain .= '1'; //indicador de descuento
                    $chain .= "\n";
                    
                    //Creacion Detalle - movimientos pedido
                    $counter = 3;
                    $counterDetailOrder = 1;
                    
                    foreach ($orders as $orderDetail) {
                        //---Declarando variables
                        //$vendedor=$this->obtenerVendedor($orderDetail['CODBODEGA'],$order['TIPODOC'],$order['CO']);
                        if($orderDetail->nummov == $order->nummov)    
                            if($orderDetail->prepack =='S'){
                                #--PREPACK
                                $chain="";
                                $chain.=str_pad($counter,7,"0",STR_PAD_LEFT);
                                $chain.="0431";
                                $chain.="0003".$config['IdCia'];
                                $chain.=str_pad($order->co,3,"0",STR_PAD_LEFT);
                                $chain.=str_pad($order->tipodoc,3," ",STR_PAD_LEFT);
                                $chain.=str_pad($order->nummov,8,"0",STR_PAD_LEFT);
                                $chain.="0002432";
                                $chain.=str_pad("",50," ",STR_PAD_LEFT);
                                $chain.=str_pad("",20," ",STR_PAD_LEFT);
                                $chain.=str_pad($orderDetail->codproducto,7," ",STR_PAD_RIGHT);
                                $chain.=str_pad("",13," ",STR_PAD_LEFT);
                                $chain.=str_pad(trim($orderDetail->codbodega),5," ",STR_PAD_RIGHT);
                                $chain.="501";
                                $chain.="01";
                                $chain.=str_pad($order->co,3,"0",STR_PAD_LEFT);
                                $chain.=str_pad("99",20," ",STR_PAD_RIGHT); #Error siesa debe ser espacios
                                $chain.=str_pad("",15," ",STR_PAD_LEFT);
                                $chain.=str_pad("",15," ",STR_PAD_LEFT);
                                $chain.=substr($order->fecmov,0,4).substr($order->fecmov,5,2).substr($order->fecmov,8,2);
                                $chain.="000";
                                $chain.=str_pad($order->codprecio,3," ",STR_PAD_RIGHT);
                                $chain.=str_pad(number_format($orderDetail->cantidadmov,0,"",""),15,"0",STR_PAD_LEFT);
                                $chain.=".0000";
                                $chain.=str_pad("",255," ",STR_PAD_LEFT);
                                $chain.=str_pad("",2000," ",STR_PAD_LEFT);
                                $chain.="5";
                            }else{
                                $chain .= str_pad($counter, 7, "0", STR_PAD_LEFT); //Numero consecutivo
                                $chain .= '0431'; //Tipo registro
                                $chain .= '00'; //Subtipo registro
                                $chain .= '02'; //Version del tipo de registro
                                $chain .= str_pad($config['IdCia'],3,"0",STR_PAD_LEFT); //compañia
                                $chain .= $order->co; //Centro de operacion
                                $chain .= $order->tipodoc; //Tipo de documento
                                $chain .= str_pad($order->nummov, 8, "0", STR_PAD_LEFT); //Consecutivo de documento
                                $chain .= str_pad($counterDetailOrder, 10, "0", STR_PAD_LEFT); //Numero de registro --> hacer contador
                                $chain .= str_pad($orderDetail->codproducto, 7, "0", STR_PAD_LEFT); //Item
                                $chain .= str_pad('', 50, " ", STR_PAD_LEFT); //Referencia item
                                $chain .= str_pad('', 20, " ", STR_PAD_LEFT); //Codigo de barras
                                $chain .= str_pad('', 20, " ", STR_PAD_LEFT); //Extencion 1
                                $chain .= str_pad('', 20, " ", STR_PAD_LEFT); //Extencion 2
                                $chain .= $orderDetail->codbodega; //Bodega
                                $chain .= '501'; //Concepto
                                $chain .= '01'; //Motivo
                                $chain .= '0'; //Indicador de obsequio
                                $chain .= $order->co; //Centro de operacion movimiento
                                $chain .= str_pad('003', 20, " ", STR_PAD_RIGHT); //Unidad de negocio movimiento
                                $chain .= str_pad('', 15, " ", STR_PAD_LEFT); //Centro de costo movimiento
                                $chain .= str_pad('', 15, " ", STR_PAD_LEFT); //Proyecto
                                if($order->fechorentregacli >= $order->fecmov){
                                    $chain.=substr($order->fechorentregacli,0,4).substr($order->fechorentregacli,5,2).substr($order->fechorentregacli,8,2); //Fecha de entrega del pedido
                                }else{
                                    $chain.=substr($order->fecmov,0,4).substr($order->fecmov,5,2).substr($order->fecmov,8,2); //Fecha de entrega del pedido
                                } 
                                $chain .= '000'; //Nro. dias de entrega del documento
                                $chain .= str_pad($order->codprecio, 3, " ", STR_PAD_RIGHT); //Lista de precio-->{FIJARSE QUE HAY QUE TRAERLA DE LA TABLA MOVENC}
                                $chain .= str_pad(substr(trim($order->nomunidademp),0,4),4," ",STR_PAD_RIGHT); //Unidad de medida-->pendiente
                                $chain .= str_pad(intval($orderDetail->cantidadmov), 15, "0", STR_PAD_LEFT) . '.0000'; //Cantidad base
                                $chain .= str_pad('', 15, "0", STR_PAD_LEFT) . '.0000'; //Cantidad adicional
                                $chain .= str_pad(intval($orderDetail->preciomov), 15, "0", STR_PAD_LEFT) . '.0000'; //Precio unitario {PENDIENTE}
                                $chain .= '0'; //Impuestos asumidos
                                $chain .= str_pad('', 255, " ", STR_PAD_LEFT); //Notas
                                $chain .= str_pad('', 2000, " ", STR_PAD_LEFT); //Descripcion
                                $chain .= '5'; //Indicador backorder del movimiento
                                $chain .= '1'; //Indicador de precio
                                $chain .= "\n"; 
                                $counter++;
                                $counterDetailOrder++;
                            }
                    }

                    $chain .= str_pad($counter, 7, "0", STR_PAD_LEFT) . "99990001001";

                    $lines = explode("\n", $chain);

                    $nummov = str_pad($order->nummov, 10, "0", STR_PAD_LEFT);
                }

                $namefile = $closing.'_'.$orders[0]->nummov. '.txt';
                Storage::disk('public')->put('export/bex_0008/bexmovil/pedidos_txt/' . $namefile, $chain);// CAMBIAR EL NOMBRE DE LA COMPAÑIA
                $xmlOrder = $this->createXmlOrder($lines, $nummov, $config);

                if (!$this->existePedidoSiesa($config, $orders[0]->tipodoc, $nummov) && $import == true) {
                    $resp = $this->importarXml($xmlOrder,$config['url']);
                    if (!is_array($resp) && empty($resp)) {
                        $envio = 'Ok';
                        $estado = "2";
                        $this->estadoRegistro($envio, $estado, $orders[0]->codvendedor, $orders[0]->nummov,$cia);
                    } else {
                        if (is_array($resp)) {
                            $error=$resp['error'];
                            $estado = "3";
                            $this->estadoRegistro($error, $estado, $orders[0]->codvendedor, $orders[0]->nummov,$cia);
                        } else {
                            $mensaje = "";
                            foreach ($resp->NewDataSet->Table as $key => $errores) {
                                $error = "";
                                foreach ($errores as $key => $detalleError) {
                                    if ($key == 'f_detalle') {
                                        $error = $detalleError;
                                    }
                                }
                            }

                            if (strrpos($error, "el tercero vendedor no existe o no esta configurado como vendedor")!==false) {
                                $error.=" Nombre vendedor: ".$orders[0]->nomvendedor." Cedula vendedor: ".$orders[0]->codvendedor;
                                $estado = "3";
                                $this->estadoRegistro($error, $estado, $orders[0]->codvendedor, $orders[0]->nummov,$cia);
                            } else {
                                $estado = "3";
                                $this->estadoRegistro($error, $estado, $orders[0]->codvendedor, $orders[0]->nummov,$cia);
                            }
                        }
                    }
                } elseif ($this->existePedidoSiesa($config, $orders[0]->tipodoc, $nummov)) {
                    $error = "Este pedido ya fue registrado anteriormente, por favor verificar. Fecha de ejecucion: " . date('Y-m-d h:i:s');
                    $estado = "2";

                    $this->estadoRegistro($error, $estado, $orders[0]->codvendedor, $orders[0]->nummov,$cia);
                }
                 
            }else {
                $error = 'El pedido no tiene productos asignados';
                $estado = "3";
                $this->estadoRegistro($error, $estado, $orders[0]->codvendedor, $orders[0]->nummov,$cia);
            }
            
        } catch (\Exception $e) {
            Tbl_Log::create([
                'descripcion' => 'Custom::bex_0008/OrderCoreCustom[uploadOrder()] => '.$e->getMessage()
            ]);
            return print '▲ Error en uploadOrder';
        }
    }

    private function existePedidoSiesa($config, $tipodoc, $nummov){
        $SQLnew = '
            SET QUOTED_IDENTIFIER OFF;
            SELECT 1
            FROM t430_cm_pv_docto
            WHERE f430_id_cia = "'.$config['IdCia'].'"
            AND f430_id_tipo_docto = "'.$tipodoc.'"
            AND f430_referencia = "'.$nummov.'"; 
            SET QUOTED_IDENTIFIER ON;
            ';
        
        $consulta = $this->structureXML($config['NombreConexion'], $config['IdCia'], $config['IdProveedor'], $config['Usuario'], $config['Clave'], $SQLnew, $config['IdConsulta'], 1, 0);
        // dd($consulta);
        $result = $this->SOAP($config['url'], $consulta, $config['IdConsulta']);
        return $result;
    }

    private function estadoRegistro($envio, $estado, $vendedor, $nummov,$cia){
        $platafor_pi288 = Connection_Bexsoluciones::where('name', $cia->bdlicencias)->first();
                    $config = $this->connectionDB($platafor_pi288->id, 'externa', $platafor_pi288->area);
                    if($config != 0){
                        DB::connection('mysql')->table('tbl_log')->insert([
                            'descripcion' => 'Trait::OrderCoreCustom[uploadOrder()] => Conexion Externa: '.$config,
                            'created_at'  => now(),
                            'updated_at'  => now()
                        ]);
                        return 1;
                    }

                    DB::connection($platafor_pi288->name)
                        ->table('tbldmovenc')
                        ->where('NUMMOV',  $nummov)
                        ->where('CODTIPODOC', '4')
                        ->where('codvendedor', $vendedor)
                        ->update(['estadoenviows' => $estado, 'fechamovws' => now(), 'msmovws' => $envio]);
    }
}
