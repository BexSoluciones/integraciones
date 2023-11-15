<?php
namespace App\Traits;

use Exception;

use App\jobs\ProcessOrderUploadERP;
use App\Models\OrderDetail;
use App\Models\OrderHeader;
use App\Models\Ws_Unoee_Config;
use App\Traits\ConnectionTrait;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

trait GetOrderTrait {
    use ConnectionTrait;

    public function getOrderHeder($db,$area,$closing){
        try {
            if($db == 'fyel'){
                $order=DB::connection($db)->table('tbldmovenc')
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

                // $cia = json_decode(json_encode(Ws_Unoee_Config::getConnectionId(1)),true);
                $conectionSys = 'sys';
                $this->connectionDB($conectionSys,$area);

                $cia = DB::connection($conectionSys)
                            ->table('tblslicencias')
                            ->where('bdlicencias','platafor_pi055')
                            ->first();

                ProcessOrderUploadERP::dispatch($order,$cia);
            }else{
                return json_decode(json_encode(OrderHeader::where('estadoenviows', '0')
                    ->where('CODTIPODOC', '4')
                    ->where('NUMCIERRE', $closing)
                    ->where('AUTORIZACION', '1')->get()),true);
            }
            return true;
        }catch (\Exception $e) {
            $this->error('Error getOrderHeder: ' . $e->getMessage());
        }
    }

    public function getOrderDetail($orders){
        try {
            foreach ($orders as $order) {
                $orderDetail = json_decode(json_encode(OrderDetail::where('CODEMPRESA', $order['CODEMPRESA'])
                                                                ->where('CODTIPODOC',$order['CODTIPODOC'])
                                                                ->where('PREFMOV',$order['PREFMOV'])
                                                                ->where('NUMMOV',$order['NUMMOV'])->get()),true);

                $cia = json_decode(json_encode(Ws_Unoee_Config::getConnectionId(1)),true);
                
                ProcessOrderUploadERP::dispatch($order,$orderDetail,$cia);
            }
        }catch (\Exception $e) {
            $this->error('Error getOrderDetail: ' . $e->getMessage());
        }
    }
    
}