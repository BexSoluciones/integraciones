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
use App\Models\Custom_Sql;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

trait GetOrderTrait {
    use ConnectionTrait;

    public function getOrderHeder($db, $area, $closing){

        $connection_id = $db;

        try {
            $codiva = Custom_Sql::join('connection_bexsoluciones', 'connection_bexsoluciones.connection_id', '=', 'custom_sql.connection_id')
                    ->where('custom_sql.category', 'pedidos')
                    ->where('custom_sql.connection_id', $db)
                    ->select('custom_sql.txt')
                    ->first();

            $iva = $codiva ? $codiva->txt : '';

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
                    ->selectRaw('tblmvendedor.co,tblmvendedor.tipodoc,tbldmovenc.nummov,nitcliente,succliente,
                                tbldmovenc.codfpagovta, tbldmovenc.codvendedor,fecmov,tbldmovdet.codproducto,
                                tbldmovdet.codbodega,cantidadmov,tbldmovenc.codprecio,preciomov,dcto1mov,dcto2mov,
                                dcto3mov,dcto4mov,pluproducto,nomunidademp,tblmproducto.codunidademp,
                                REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(mensajemov,"á", "a"),"é", "e"),"í", "i"),"ó", "o"),"ú", "u"),"Á", "A"),"É", "E"),"Í", "I"),"Ó", "O"),"Ú", "U"),"ñ","n"),"Ñ","N") AS mensajemov,
                                dctopiefacaut,dctonc,numvisita,fechorfinvisita,fechorentregacli,origen,ordendecompra,tblmvendedor.cedula,
                                tbldmovdet.prepack,ivamov,nomproducto,dctovalor,tblmvendedor.tercvendedor,tbldmovdet.bonificado,tblmproducto.codproveedor,
                                tbldmovdet.ocultoporcval,tbldmovenc.codtipodoc,tbldmovenc.prefmov,tbldmovenc.numcierre,estadoenviows,tblmproducto.ccostos,tblmvendedor.nomvendedor,tbldmovenc.mensajeadic'.$iva)
                    ->orderBy('tbldmovenc.nummov','asc')
                    ->orderBy('tbldmovdet.codmovdet','asc')
                    ->get();
                    //  print_r($order['0']);
                    //  dd();
                $plataforSys = 2;
                $config = $this->connectionDB($plataforSys, 'externa', $area);
                if($config != 0){
                    DB::connection('mysql')->table('tbl_log')->insert([
                        'descripcion' => 'Trait::GetOrderTrait[getOrderHeder()] => Conexion Externa: Linea '.__LINE__.'; '.$config,
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
                //$cia = 123;
                if(!$order->isEmpty()){
                    ProcessOrderUploadERP::dispatch($order, $cia, $closing, null, $connection_id)->onQueue('pedidos')->onConnection('sync');
                }else{
                    $order = DB::connection($db)
                    ->table('tbldmovenc')
                    ->where('numcierre', $closing)
                    ->selectRaw('tbldmovenc.nummov,tbldmovenc.codmovenc')
                    ->get();

                    DB::connection($db)
                    ->table('tbldmovenc')
                    ->where('NUMMOV', $order['0']->nummov)
                    ->where('CODMOVENC', $order['0']->codmovenc)
                    ->where('CODTIPODOC', '4')
                    ->update(['estadoenviows' => '3', 'fechamovws' => now(), 'msmovws' => 'Pedido incompleto']);

                }

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
