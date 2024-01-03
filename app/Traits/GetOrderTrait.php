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

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

trait GetOrderTrait {
    use ConnectionTrait;

    public function getOrderHeder($db, $area, $closing){
        try {
            $db = Connection_Bexsoluciones::getAll()->where('id', $db)->value('name');
            if($db){
                $order = DB::connection($db)
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
                    
                $plataforSys = 2;
                $config = $this->connectionDB($plataforSys, 'externa', $area);
                if($configDB != 0){
                    DB::connection('mysql')->table('tbl_log')->insert([
                        'descripcion' => 'Trait::GetOrderTrait[getOrderHeder()] => Conexion Externa: Linea '.__LINE__.'; '.$configDB,
                        'created_at'  => now(),
                        'updated_at'  => now()
                    ]);
                    return 1;
                }

                $plataforSys = Connection_Bexsoluciones::getAll()->where('id', $plataforSys)->value('name');
                $cia = DB::connection($plataforSys)
                        ->table('tblslicencias')
                        ->where('bdlicencias', $db)
                        ->first();

                ProcessOrderUploadERP::dispatch($order, $cia, $closing)->onQueue('pedidos')->onConnection('sync');
                return 0;
            } else {

                return 0;

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
                ), 0);

                $cia = json_decode(json_encode(Ws_Unoee_Config::getConnectionId(1)), 0);
                ProcessOrderUploadERP::dispatch($order,$orderDetail,$cia)->onQueue('orders');
            }
        }catch (\Exception $e) {
            Tbl_Log::create([
                'descripcion' => 'Trait::GetOrderTrait[getOrderDetail()] => '.$e->getMessage()
            ]);
        }
    }
    
}