<?php

namespace App\Custom;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

use App\Models\LogErrorImportacionModel;
use App\Traits\ConnectionTrait;

class OrderCoreFyelCustom
{


    public function __construct()
    {
    }

    public function uploadOrder($orders,$cia)
    {
        $fechaActual = date('Ymd');

        $count=count($orders);
        if ($count > 0) {

            $dcto = '';
            $pvsiesa='';
            $cadena="";
            
            foreach ($orders as $order)
            {
                $cadena.=str_pad($order->nummov,10," ",STR_PAD_RIGHT);
                $cadena.="2";
                $cadena.=str_pad("",20," ",STR_PAD_LEFT);
                $cadena.=str_pad($order->nitcliente,13," ",STR_PAD_RIGHT);
                $cadena.=str_pad($order->succliente,2,"0",STR_PAD_LEFT);
                $cadena.=substr($order->fecmov,0,4).substr($order->fecmov,5,2).substr($order->fecmov,8,2);
                $cadena.=str_pad($order->co,3,"0",STR_PAD_LEFT);
    
                if(trim($cia->centroopesiesa)!=""){
                    $cadena.=str_pad($cia->centroopesiesa,2,"0",STR_PAD_RIGHT);
                }else{
                    $cadena.=str_pad(substr(trim($order->codbodega),3,2),2," ",STR_PAD_RIGHT);
                    $cadena.=$cia->inditemsiesa;    
                }  
                $cadena.=str_pad("",15," ",STR_PAD_LEFT);
                if($cia->inditemsiesa == "I"){
                    $cadena.=str_pad($order->pluproducto,6," ",STR_PAD_LEFT);
                    $cadena.=str_pad("",9," ",STR_PAD_LEFT);
                }else{
                    $cadena.=str_pad($order->pluproducto,15," ",STR_PAD_RIGHT);
                }
    
                $cadena.=str_pad("",3," ",STR_PAD_LEFT);
                $cadena.=substr($order->fecmov,0,4).substr($order->fecmov,5,2).substr($order->fecmov,8,2);
                $cadena.=str_pad(substr($order->nomunidademp,0,3),3," ",STR_PAD_RIGHT);
                $cadena.=str_pad(number_format($order->cantidadmov,3,"",""),12," ",STR_PAD_LEFT)."+";
                $cadena.=str_pad("",8,"0",STR_PAD_LEFT)."0000+";
                $cadena.="1";
    
                if(trim($cia->lpsiesa) != ""){
                    $cadena.=str_pad($cia->lpsiesa,3," ",STR_PAD_RIGHT);
                }else{
                    $cadena.=str_pad($order->codprecio,3," ",STR_PAD_RIGHT);
                }
    
                if(trim($cia->ldsiesa) != ""){
                    $cadena.=str_pad($cia->ldsiesa,2," ",STR_PAD_RIGHT);
                }else{
                    $cadena.=str_pad("",2," ",STR_PAD_LEFT);
                }
    
                if($order->dctopiefacaut==""){
                    $order->dctopiefacaut=0;
                } 
    
                if($order->dctonc==""){
                    $order->dctonc=0;
                } 
                    
                $dcto=(100*(1-($order->dcto1mov/100)));
                $dcto=($dcto*(1-($order->dcto2mov/100)));
                $dcto=($dcto*(1-($order->dcto3mov/100)));
                $dcto=($dcto*(1-($order->dcto4mov/100)));
                $dcto=($dcto*(1-($order->dctopiefacaut/100)));
                            $dcto=($dcto*(1-($order->dctonc/100)));
                $dcto=100-$dcto;
                $pvsiesa=$order->preciomov/(1-($dcto/100));
                $cadena.=str_pad(number_format($pvsiesa,2,"",""),11," ",STR_PAD_LEFT)."+";
                $cadena.=str_pad(number_format($dcto,2,"",""),4,"0",STR_PAD_LEFT);
                $cadena.=str_pad("",4,"0",STR_PAD_LEFT);
                $cadena.=str_pad("",20," ",STR_PAD_LEFT);
                $cadena.=str_pad("",40," ",STR_PAD_LEFT);
                $cadena.=str_pad("",4," ",STR_PAD_LEFT);
                $cadena.=str_pad(substr($order->mensajemov,0,120),120," ",STR_PAD_RIGHT);
                $cadena.="\n";
                $dcto=0;
            }
           
            //   Log::info('=========imprimiendo datos recibidos al job=====');
            //   Log::info($cadena);
            $namefile=$fechaActual.'.txt';
            Storage::disk('public')->put('export/fyel/pedidos_txt/' . $namefile, $cadena);    
            
        } else {
            $error = 'El pedido no tiene productos asignados';
            $estado = "3";
            // $this->logErrorImportarPedido($error, $estado, $order['CO'],  $order['TIPODOC'], $order['NUMMOV']);
        }
    }


    // public function logErrorImportarPedido($mensaje, $estado, $centroOperacion, $tipoDocumento, $numeroPedido)
    // {
    //     $objErrorImpPed = new LogErrorImportacionModel();
    //     $result = $objErrorImpPed->actualizarEstadoDocumento($mensaje, $estado, $centroOperacion, $tipoDocumento, $numeroPedido);
    // }




   
}
