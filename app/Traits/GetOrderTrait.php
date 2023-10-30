<?php
namespace App\Traits;

use Exception;

use App\Models\OrderDetail;
use App\Models\OrderHeader;
use App\jobs\ProcessOrderUploadERP;
use App\Models\Ws_Unoee_Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

trait GetOrderTrait {
    
    public function getOrderHeder($estado,$codtipodoc){

        return json_decode(json_encode(OrderHeader::where('estadoenviows', $estado)
                                                    ->where('CODTIPODOC', $codtipodoc)
                                                    ->whereNotNull('NUMCIERRE')->get()),true);
       
    }

    public function getOrderDetail($orders){

        foreach ($orders as $order) {
            
            $orderDetail = json_decode(json_encode(OrderDetail::where('CODEMPRESA', $order['CODEMPRESA'])
                                                               ->where('CODTIPODOC',$order['CODTIPODOC'])
                                                               ->where('PREFMOV',$order['PREFMOV'])
                                                               ->where('NUMMOV',$order['NUMMOV'])->get()),true);

            $cia = json_decode(json_encode(Ws_Unoee_Config::getConnectionId(1)),true);
            
            ProcessOrderUploadERP::dispatch($order,$orderDetail,$cia);
        }
    }
    
}