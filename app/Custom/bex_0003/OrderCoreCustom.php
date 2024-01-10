<?php

namespace App\Custom\bex_0003;

use App\Models\Tbl_Log;

use App\Models\LogErrorImportacionModel;
use App\Traits\ConnectionTrait;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class OrderCoreCustom
{
    public function uploadOrder($orders, $cia, $closing)
    {
        try{
            $fechaActual = date('Ymd');
            $count       = count($orders);
 
            if ($count > 0) {
                
                $dcto = '';
                $pvsiesa='';
                $cadena="";
                
                foreach ($orders as $order) {
                    $cadena.=str_pad($order->nummov,10," ",STR_PAD_RIGHT);
                    $cadena.="2";
                    $cadena.=str_pad("",20," ",STR_PAD_LEFT);
                    $cadena.=str_pad($order->nitcliente,13," ",STR_PAD_RIGHT);
                    $cadena.=str_pad($order->succliente,2,"0",STR_PAD_LEFT);
                    $cadena.=substr($order->fecmov,0,4).substr($order->fecmov,5,2).substr($order->fecmov,8,2);
                    $cadena.=str_pad($order->co,3,"0",STR_PAD_LEFT);
        
                    if(trim($cia->centroopesiesa)!=""){
                        $cadena.=str_pad($cia->centroopesiesa,2,"0",STR_PAD_RIGHT);
                        $cadena.=$cia->inditemsiesa;    
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
                    $cadena.=str_pad("",173," ",STR_PAD_LEFT);
                    $cadena.=str_pad("01",2,"0",STR_PAD_LEFT);
                    // $cadena.=str_pad($order->codiva,2,"0",STR_PAD_LEFT);
                    $cadena.="\n";
                    $dcto=0;
                }
              
                $namefile=str_pad($closing,8,"0",STR_PAD_LEFT).'.PE0';
                Storage::disk('public')->put('export/bex_0003/bexmovil/pedidos_txt/' . $namefile, $cadena);    
            } else {
                $error = 'El pedido no tiene productos asignados';
                $estado = "3";
            }
        } catch (\Exception $e) {
            Tbl_Log::create([
                'descripcion' => 'Custom::bex_0003/OrderCoreCustom[uploadOrder()] => '.$e->getMessage()
            ]);
            return print '▲ Error en uploadOrder';
        }
    }
}
