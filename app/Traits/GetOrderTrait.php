<?php
namespace App\Traits;

use Exception;

use App\Jobs\ProcessOrderUploadERP;
use App\Models\Tbl_Log;
use App\Models\OrderDetail;
use App\Models\OrderHeader;
use App\Models\Ws_Unoee_Config;
use App\Traits\ConnectionTrait;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

trait GetOrderTrait {
    use ConnectionTrait;

    public function getOrderHeder($db, $area, $closing){
        try {
            if($db){
                $order = DB::connection('dynamic_connection')
                    ->table('tbldmovenc')
                    ->join('tbldmovdet','tbldmovenc.nummov','=','tbldmovdet.nummov')
                    ->join('tblmproducto','tbldmovdet.codproducto','=','tblmproducto.codproducto')
                    ->join('tblmunidademp','tblmproducto.codunidademp','=','tblmunidademp.codunidademp')
                    ->join('tblmcliente','tbldmovenc.codcliente','=','tblmcliente.codcliente')
                    ->join('tblmvendedor','tbldmovenc.codvendedor','=','tblmvendedor.codvendedor')
                    ->whereColumn('tbldmovenc.codempresa','tbldmovdet.codempresa')
                    ->whereColumn('tbldmovenc.prefmov','tbldmovdet.prefmov')
                    ->whereColumn('tbldmovenc.codtipodoc','tbldmovdet.codtipodoc')
                    ->where('numcierre', $closing)
                    ->where('tbldmovenc.codtipodoc', '4')
                    ->selectRaw('tblmvendedor.co,tblmvendedor.tipodoc,tbldmovenc.nummov,nitcliente,succliente,tbldmovenc.codfpagovta,
                                tbldmovenc.codvendedor,fecmov,tbldmovdet.codproducto,tbldmovdet.codbodega,cantidadmov,tbldmovenc.codprecio,
                                preciomov,dcto1mov,dcto2mov,dcto3mov,dcto4mov,pluproducto,nomunidademp,mensajemov,dctopiefacaut,dctonc')
                    ->get();

                $cia = DB::connection('platafor_sys')
                            ->table('tblslicencias')
                            ->where('bdlicencias', $db->name)
                            ->first();

                ProcessOrderUploadERP::dispatch($order, $cia, $closing)->onQueue('pedidos');

                return true;
            } else {

                return false;

                // return json_decode(json_encode(
                //     OrderHeader::where('estadoenviows', '0')
                //         ->where('CODTIPODOC', '4')
                //         ->where('NUMCIERRE', $closing)
                //         ->where('AUTORIZACION', '1')
                //         ->get()
                // ), true);
            }
            
        }catch (\Exception $e) {
            Tbl_Log::create([
                'descripcion' => 'Trait::GetOrderTrait[getOrderHeder()] => '.$e->getMessage()
            ]);
        }
    }

    public function getOrderDetail($orders){
        try {
            foreach ($orders as $order) {
                $orderDetail = json_decode(json_encode(
                    OrderDetail::where('CODEMPRESA', $order['CODEMPRESA'])
                                ->where('CODTIPODOC',$order['CODTIPODOC'])
                                ->where('PREFMOV',$order['PREFMOV'])
                                ->where('NUMMOV',$order['NUMMOV'])
                                ->get()
                ), true);

                $cia = json_decode(json_encode(Ws_Unoee_Config::getConnectionId(1)), true);
                ProcessOrderUploadERP::dispatch($order,$orderDetail,$cia)->onQueue('orders');
            }
        }catch (\Exception $e) {
            Tbl_Log::create([
                'descripcion' => 'Trait::GetOrderTrait[getOrderDetail()] => '.$e->getMessage()
            ]);
        }
    }
    
}