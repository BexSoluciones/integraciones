<?php

namespace App\Custom\bex_0008;

use App\Models\Tbl_Log;

use App\Models\LogErrorImportacionModel;
use App\Traits\ConnectionTrait;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class OrderCoreCustom
{
    use ConnectionTrait;
    public function uploadOrder($order, $cia, $closing)
    {
        try{
            if (count($order) > 0) {
                // {PENDIENTE VENDEDORES Y CLIENTES}
                $import = true;
                $chain = "";
                $chain .= str_pad(1, 7, "0", STR_PAD_LEFT) . "00000001".str_pad($cia['IdCia'],3,"0",STR_PAD_LEFT)."\n"; 
                $chain .= str_pad('2', 7, "0", STR_PAD_LEFT); //Numero de registros
                $chain .= str_pad('430', 4, "0", STR_PAD_LEFT); //Tipo de registro
                $chain .= '00'; //Subtipo de registro
                $chain .= '02'; //version del tipo de registro
                $chain .= str_pad($cia['IdCia'],3,"0",STR_PAD_LEFT); //Compañia
                $chain .= '1'; //Indicador para liquidar impuestos
                $chain .= '1'; //Indica si el numero consecutivo de docto es manual o automático
                $chain .= '1'; //Indicador de contacto
                $chain .= $order['CO']; //Centro de operación del documento
                $chain .= $order['TIPODOC']; //Tipo de documento
                $chain .= str_pad($order['NUMVISITA'], 8, "0", STR_PAD_LEFT); //Numero documento
                $chain .= substr($order['FECMOV'], 0, 4).substr($order['FECMOV'], 5, 2).substr($order['FECMOV'], 8, 2); //Fecha del documento
                $chain .= '502'; //Clase interna del documento
                $chain .= '2'; //Estado del documento
                $chain .= '0'; //Indicador backorder del documento
                $chain .= str_pad($order['NITCLIENTE'], 15, " ", STR_PAD_RIGHT); //Tercero cliente a facturar
                $chain .= str_pad($order['SUCCLIENTE'], 3, "0", STR_PAD_LEFT); //Sucursal cliente a facturar
                $chain .= str_pad($order['NITCLIENTE'], 15, " ", STR_PAD_RIGHT); //Tercero cliente a despachar
                $chain .= str_pad($order['SUCCLIENTE'], 3, "0", STR_PAD_LEFT); //Sucursal cliente a despachar
                if($order['origen'] == 'B2B'){
                    $chain.=str_pad("0003",4," ",STR_PAD_LEFT);
                }else{
                    $chain.=str_pad("0001",4," ",STR_PAD_LEFT);
                }
                $chain .= str_pad($order['CO'],3,"0",STR_PAD_LEFT); //Centro de operacion de la factura
                if($order['fechorentregacli'] >= $order['FECMOV']){
                    $chain.=substr($order['fechorentregacli'],0,4).substr($order['fechorentregacli'],5,2).substr($order['fechorentregacli'],8,2);
                }else{
                    $chain.=substr($order['FECMOV'],0,4).substr($order['FECMOV'],5,2).substr($order['FECMOV'],8,2);
                }
                $chain .= '000'; //Nro. dias de entrega del documento
                if($order['ORDENDECOMPRA'] != ''){
                    $chain .= str_pad($order['ORDENDECOMPRA'], 15, "Y", STR_PAD_LEFT); //Orden de compra del Documento
                }else{
                    $chain .= str_pad($order['NUMMOV'], 15, "Y", STR_PAD_LEFT); //Orden de compra del Documento
                }
                $chain .= str_pad($order['NUMMOV'], 10, "0", STR_PAD_LEFT); //Referencia del documento
                $chain .= str_pad('GENERICO', 10, " ", STR_PAD_RIGHT); //Codigo de cargue del documento
                $chain .= 'COP'; //Codigo de moneda del documento
                $chain .= 'USD'; //Moneda base de conversión {PENDIENTE CONVERSION}
                $chain .= '00000001.0000'; //Tasa de conversión
                $chain .= 'COP'; //Moneda local
                $chain .= '00000001.0000'; //Tasa local
                $chain .= str_pad($order['CODFPAGOVTA'], 3, " ", STR_PAD_LEFT); //Condicion de pago
                $chain .= '0'; //Estado de impresión del documento
                $chain .= str_pad(utf8_decode($order['MENSAJEMOV']), 2000, " ", STR_PAD_RIGHT); //Observaciones del documento
                $chain .= str_pad('', 15, " ", STR_PAD_LEFT); //cliente de contado
                $chain .= '000'; //Punto de envio
                $chain .= str_pad($order['CEDULA'], 15, " ", STR_PAD_RIGHT); //Vendedor del pedido
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

                foreach ($order as $orderDetail) {
                    //---Declarando variables
                    //$vendedor=$this->obtenerVendedor($orderDetail['CODBODEGA'],$order['TIPODOC'],$order['CO']);
                    if($orderDetail['prepack']=='S'){
                        #--PREPACK
                        $chain="";
                        $chain.=str_pad($counter,7,"0",STR_PAD_LEFT);
                        $chain.="0431";
                        $chain.="0003".$cia['ciasiesa'];
                        $chain.=str_pad($order['CO'],3,"0",STR_PAD_LEFT);
                        $chain.=str_pad($order['tipodoc'],3," ",STR_PAD_LEFT);
                        $chain.=str_pad($order['numvisita'],8,"0",STR_PAD_LEFT);
                        $chain.="0002432";
                        $chain.=str_pad("",50," ",STR_PAD_LEFT);
                        $chain.=str_pad("",20," ",STR_PAD_LEFT);
                        $chain.=str_pad($order['codproducto'],7," ",STR_PAD_RIGHT);
                        $chain.=str_pad("",13," ",STR_PAD_LEFT);
                        $chain.=str_pad(trim($order['codbodega']),5," ",STR_PAD_RIGHT);
                        $chain.="501";
                        $chain.="01";
                        $chain.=str_pad($order['CO'],3,"0",STR_PAD_LEFT);
                        $chain.=str_pad("99",20," ",STR_PAD_RIGHT); #Error siesa debe ser espacios
                        $chain.=str_pad("",15," ",STR_PAD_LEFT);
                        $chain.=str_pad("",15," ",STR_PAD_LEFT);
                        $chain.=substr($order['fecmov'],0,4).substr($order['fecmov'],5,2).substr($order['fecmov'],8,2);
                        $chain.="000";
                        $chain.=str_pad($order['codprecio'],3," ",STR_PAD_RIGHT);
                        $chain.=str_pad(number_format($order['cantidadmov'],0,"",""),15,"0",STR_PAD_LEFT);
                        $chain.=".0000";
                        $chain.=str_pad("",255," ",STR_PAD_LEFT);
                        $chain.=str_pad("",2000," ",STR_PAD_LEFT);
                        $chain.="5";
                    }else{
                        $chain .= str_pad($counter, 7, "0", STR_PAD_LEFT); //Numero consecutivo
                        $chain .= '0431'; //Tipo registro
                        $chain .= '00'; //Subtipo registro
                        $chain .= '02'; //Version del tipo de registro
                        $chain .= str_pad($cia['IdCia'],3,"0",STR_PAD_LEFT); //compañia
                        $chain .= $order['CO']; //Centro de operacion
                        $chain .= $order['TIPODOC']; //Tipo de documento
                        $chain .= str_pad($order['NUMVISITA'], 8, "0", STR_PAD_LEFT); //Consecutivo de documento
                        $chain .= str_pad($counterDetailOrder, 10, "0", STR_PAD_LEFT); //Numero de registro --> hacer contador
                        $chain .= str_pad($orderDetail['CODPRODUCTO'], 7, "0", STR_PAD_LEFT); //Item
                        $chain .= str_pad('', 50, " ", STR_PAD_LEFT); //Referencia item
                        $chain .= str_pad('', 20, " ", STR_PAD_LEFT); //Codigo de barras
                        $chain .= str_pad('', 20, " ", STR_PAD_LEFT); //Extencion 1
                        $chain .= str_pad('', 20, " ", STR_PAD_LEFT); //Extencion 2
                        $chain .= $orderDetail['CODBODEGA']; //Bodega
                        $chain .= '501'; //Concepto
                        $chain .= '01'; //Motivo
                        $chain .= '0'; //Indicador de obsequio
                        $chain .= $order['CO']; //Centro de operacion movimiento
                        $chain .= str_pad('01', 20, " ", STR_PAD_RIGHT); //Unidad de negocio movimiento
                        $chain .= str_pad('', 15, " ", STR_PAD_LEFT); //Centro de costo movimiento
                        $chain .= str_pad('', 15, " ", STR_PAD_LEFT); //Proyecto
                        if($order['fechorentregacli'] >= $order['FECMOV']){
                            $chain.=substr($order['fechorentregacli'],0,4).substr($order['fechorentregacli'],5,2).substr($order['fechorentregacli'],8,2); //Fecha de entrega del pedido
                        }else{
                            $chain.=substr($order['FECMOV'],0,4).substr($order['FECMOV'],5,2).substr($order['FECMOV'],8,2); //Fecha de entrega del pedido
                        } 
                        $chain .= '000'; //Nro. dias de entrega del documento
                        $chain .= str_pad($order['CODPRECIO'], 3, " ", STR_PAD_RIGHT); //Lista de precio-->{FIJARSE QUE HAY QUE TRAERLA DE LA TABLA MOVENC}
                        $chain .= str_pad(substr(trim($order['NOMUNIDADEMP']),0,4),4," ",STR_PAD_RIGHT); //Unidad de medida-->pendiente
                        $chain .= str_pad(intval($orderDetail['CANTIDADMOV']), 15, "0", STR_PAD_LEFT) . '.0000'; //Cantidad base
                        $chain .= str_pad('', 15, "0", STR_PAD_LEFT) . '.0000'; //Cantidad adicional
                        $chain .= str_pad(intval($orderDetail['precio_unitario']), 15, "0", STR_PAD_LEFT) . '.0000'; //Precio unitario {PENDIENTE}
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

                $chain .= str_pad($counter, 7, "0", STR_PAD_LEFT) . "99990001002";

                $lines = explode("\n", $chain);

                $namefile = $closing.'_'.$order['NUMMOV']. '.txt';
                Storage::disk('public')->put('public/pandapan/pedidos_txt/' . $namefile, $chain); // CAMBIAR EL NOMBRE DE LA COMPAÑIA
                $xmlOrder = $this->crearXmlPedido($lines, $order['NUMMOV']);

                if (!$this->existePedidoSiesa($cia['IdCia'], $order['TIPODOC'], str_pad($order['NUMMOV'], 15, "Y", STR_PAD_LEFT)) && $import === true) {
                    // Log::info("ejecutando funcion ".__FUNCTION__." .Pedido = ".$order['NUMMOV']);
                    $resp = $this->getWebServiceSiesa(28)->importarXml($xmlOrder);
                    if (!is_array($resp) && empty($resp)) {
                        $error = 'Ok';
                        $estado = "2";
                        $this->logErrorImportarPedido($error, $estado, $order['CO'], $orderDetail['CODBODEGA'], $order['TIPODOC'], $order['NUMMOV']);
                    } else {
                        if (is_array($resp)) {
                            $error=$resp['error'];
                            $estado = "4";
                            $this->logErrorImportarPedido($error, $estado, $order['CO'], $orderDetail['CODBODEGA'], $order['TIPODOC'], $order['NUMMOV']);
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
                                $error.=" Nombre vendedor: ".$order['vendedor']." Cedula vendedor: ".$order['cedula_vendedor'];
                                $estado = "3";
                                $this->logErrorImportarPedido($error, $estado, $order['CO'], $orderDetail['CODBODEGA'], $order['TIPODOC'], $order['NUMMOV']);
                            } else {
                                $estado = "3";
                                $this->logErrorImportarPedido($error, $estado, $order['CO'], $orderDetail['CODBODEGA'], $order['TIPODOC'], $order['NUMMOV']);
                            }
                        }
                    }
                } elseif ($this->existePedidoSiesa($cia['IdCia'], $order['TIPODOC'], str_pad($order['NUMMOV'], 15, "Y", STR_PAD_LEFT))) {
                    $error = "Este pedido ya fue registrado anteriormente, por favor verificar. Fecha de ejecucion: " . date('Y-m-d h:i:s');
                    $estado = "2";
                    $this->logErrorImportarPedido($error, $estado, $order['CO'], $orderDetail['CODBODEGA'], $order['TIPODOC'], $order['NUMMOV']);
                }
            } else {
                $error = 'El pedido no tiene productos asignados';
                $estado = "3";
                $this->logErrorImportarPedido($error, $estado, $order['CO'],  $order['TIPODOC'], $order['NUMMOV']);
            }
            
        } catch (\Exception $e) {
            Tbl_Log::create([
                'descripcion' => 'Custom::bex_0008/OrderCoreCustom[uploadOrder()] => '.$e->getMessage()
            ]);
            return print '▲ Error en uploadOrder';
        }
    }
}
