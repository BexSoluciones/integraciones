<?php

namespace App\Custom\bex_0006;

use App\Models\Tbl_Log;
use App\Traits\ConnectionTrait;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class InsertCustom
{
    use ConnectionTrait;

    public function insertCarteraCustom($conectionBex, $conectionSys, $datosAInsertar, $id_importation, $type, $modelInstance)
    {
        if($datosAInsertar->count() > 0){

            try {
                $modelInstance::whereRaw('nitcliente = succliente')
                                ->whereRaw('succliente <> 0')
                                ->update(['succliente' => '0']);
                print '◘ Sucursal actualizada en la tabla de cartera' . PHP_EOL;
    
                //Tunca tabla s1e_cartera
                DB::connection($conectionBex)->table('s1e_cartera')->truncate();
                print '◘ Tabla s1e_cartera truncada' . PHP_EOL;
    
                $datosAInsertarJson = json_decode(json_encode($datosAInsertar,true));
    
                if(sizeof($datosAInsertarJson) != 0){
                    foreach (array_chunk($datosAInsertarJson, 2000) as $dato) {
                        $Insert = [];
                        $count = count($dato);
                        for($i=0;$i<$count;$i++) {
                            if ($dato[$i]->succliente == $dato[$i]->nitcliente) {
                                $dato[$i]->succliente = '0';
                            }
                            $Insert[] = [
                                'nitcliente'    => $dato[$i]->nitcliente,
                                'dv'            => $dato[$i]->dv,
                                'succliente'    => $dato[$i]->succliente,
                                'codtipodoc'    => $dato[$i]->codtipodoc,
                                'documento'     => $dato[$i]->documento,
                                'fecmov'        => $dato[$i]->fecmov,
                                'fechavenci'    => $dato[$i]->fechavenci,
                                'vrpostf'       => $dato[$i]->vrpostf,
                                'valor'         => $dato[$i]->valor,
                                'codvendedor'   => $dato[$i]->codvendedor,
                                'codcliente'    => '',
                                'estadotipodoc' => 'A'
                            ];    
                        }
                        DB::connection($conectionBex)->table('s1e_cartera')->insert($Insert);
                    }
                    print '◘ Datos insertados la tabla s1e_cartera' . PHP_EOL;
                }
    
                DB::connection($conectionBex)
                    ->table('s1e_cartera')
                    ->join('tblmtipodoc','s1e_cartera.codtipodoc','=','tblmtipodoc.codtipodoc')
                    ->update(['s1e_cartera.estadotipodoc' => 'C']);
                
                DB::connection($conectionBex)
                    ->table('tblmtipodoc')
                    ->insertUsing(['codtipodoc','nomtipodoc'],
                    function ($query) {
                        $query->select('codtipodoc', DB::raw("concat('TIPO DOCUMENTO ', codtipodoc) as nomtipodoc"))
                            ->from('s1e_cartera')
                            ->where('estadotipodoc', '=', 'A')
                            ->groupBy('s1e_cartera.codtipodoc');
                    }
                );
                print '◘ Datos insertados en la tabla tblmtipodoc' . PHP_EOL;
                
                //Actualiza codcliente en la tabla s1e_cartera
                DB::connection($conectionBex)
                    ->table('s1e_cartera')
                    ->join('tblmcliente','s1e_cartera.nitcliente','=','tblmcliente.NITCLIENTE')
                    ->whereColumn('s1e_cartera.succliente','tblmcliente.SUCCLIENTE')
                    ->update(['s1e_cartera.codcliente' => DB::raw('tblmcliente.CODCLIENTE')]);
                print '◘ Se actualizo la columna codcliente en la tabla s1e_cartera' . PHP_EOL;
            
                DB::connection($conectionBex)->table('tbldcartera')->truncate();


                print '◘ Tabla tbldcartera truncada' . PHP_EOL;

                DB::connection($conectionBex)
                    ->statement('INSERT into tbldcartera (codvendedor,codcliente,codtipodoc,nummov,fecmov,fecven,preciomov, VALTOTCREDITO)  SELECT distinct s1e_cartera.codvendedor,s1e_cartera.codcliente,s1e_cartera.codtipodoc,documento,s1e_cartera.fecmov,fechavenci,valor, vrpostf  FROM tblmvendedor,s1e_cartera,tblmcliente where s1e_cartera.codcliente=tblmcliente.codcliente AND tblmvendedor.codvendedor=s1e_cartera.codvendedor;');
    
                // DB::connection($conectionBex)
                //     ->table('tbldcartera')
                //     ->insertUsing(
                //         ['CODVENDEDOR', 'CODCLIENTE','CODTIPODOC','NUMMOV','FECMOV','FECVEN','PRECIOMOV','VALTOTCREDITO'],
                //         function ($query) {
                //             $query->select('s1e_cartera.codvendedor','s1e_cartera.codcliente','s1e_cartera.codtipodoc','documento','s1e_cartera.fecmov','fechavenci','valor','vrpostf')
                //             ->from('s1e_cartera')
                //             ->join('tblmcliente','s1e_cartera.codcliente','=','tblmcliente.CODCLIENTE')
                //             ->join('tblmvendedor','s1e_cartera.codvendedor','=','tblmvendedor.CODVENDEDOR');
                //         }
                //     );
                print '◘ Datos insertados en la tabla tbldcartera' . PHP_EOL;
                
                DB::connection($conectionBex)
                    ->table('tbldcartera')
                    ->where('preciomov','>=',0)
                    ->update(['debcre' => 'D']);
                print '◘ Se actualizo la columna debcre en la tabla tbldcartera' . PHP_EOL;
                    
                DB::connection($conectionBex)
                    ->table('tbldcartera')
                    ->where('preciomov','<',0)
                    ->update(['debcre' => 'C']);
                print '◘ Se actualizo la columna debcre en la tabla tbldcartera' . PHP_EOL;
    
                DB::connection($conectionBex)
                    ->table('tbldcartera')
                    ->where('preciomov','<',0)
                    ->update(['preciomov' => DB::raw('preciomov * (-1)')]);
                print '◘ Se actualizo la columna preciomov en la tabla tbldcartera' . PHP_EOL;
    
                /*
                DB::connection($conectionBex)
                    ->statement('CREATE TABLE IF NOT EXISTS s1e_dptos (
                        codpais varchar(5),
                        coddpto varchar(5),
                        descripcion varchar(50)                          
                    )');
    
                DB::connection($conectionBex)->statement('DROP TABLE IF EXISTS s1e_dptos');*/
            } catch (\Exception $e) {
                Tbl_Log::create([
                    'id_table'    => $id_importation,
                    'type'        => $type,
                    'descripcion' => 'Custom::bex_0006/InsertCustom[insertCarteraCustom()] => '.$e->getMessage()
                ]);
                return 1;
            }
        }else{
            print '◘ No hay datos para insertar en la tbldcartera, no se borra la tabla' . PHP_EOL;
        }
    }

    public function insertClientesCustom($conectionBex, $conectionSys, $datosAInsertar, $id_importation, $type, $modelInstance, $tableName)
    {   
        try {
            $currentDate = date('Ymd');
            $tblmfpagovta = DB::connection($conectionBex)->table('tblmfpagovta')->get();
            foreach($tblmfpagovta as $data){
                $modelInstance::where('conpag', '=', $data->CODFPAGOVTA)->update(['estadofpagovta' => 'C']);
            }
            print '◘ Se actualizo la columna estadofpagovta en la tabla '.$tableName . PHP_EOL;
            
            $decuento = DB::connection($conectionBex)->table('tblmdescuento')->get()->count();
            if($decuento == 0){
                DB::connection($conectionBex)->table('tblmdescuento')->insert([
                    'coddescuento' => '0',
                    'nomdescuento' => 'N.A'
                ]);
                print '◘ Datos insertados en la tabla tblmdescuento' . PHP_EOL;
            }

            $modelInstance::whereRaw('codigo = sucursal')
                            ->whereRaw('sucursal <> 0')
                            ->update(['sucursal' => '0']);
            print '◘ Sucursal actualizada en la tabla de clientes' . PHP_EOL;

            $codpago = $modelInstance::codPago()->get();
            if ($codpago->isNotEmpty()) {
                $dataToInsert = $codpago->map(function ($dato) {
                    return [
                        'codfpagovta' => $dato->conpag,
                        'nomfpagovta' => 'CONDICION ' . $dato->conpag,
                        'perfpagovta' => $dato->periodicidad,
                    ];
                })->toArray();
            
                DB::connection($conectionBex)->table('tblmfpagovta')->insert($dataToInsert);
                print '◘ Datos insertados en la tabla tblmfpagovta' . PHP_EOL;
            }

            DB::connection($conectionBex)->table('s1e_clientes')->truncate();
            print '◘ Tabla s1e_clientes truncada' . PHP_EOL;

            $datosAInsertarJson = json_decode(json_encode($datosAInsertar,true));
            
            if(sizeof($datosAInsertarJson) != 0){

                foreach ($datosAInsertarJson as &$dato) {
                    foreach ($dato as $key => &$value) {
                        $value = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $value);
                    }
                }
                unset($dato); // Desvincula la última referencia a $dato
                unset($value); // Desvincula la última referencia a $value
                
                foreach (array_chunk($datosAInsertarJson, 2000) as $dato) {
                    $Insert = [];
                    $count = count($dato);
                    for($i=0;$i<$count;$i++) {
                        if ($dato[$i]->sucursal == $dato[$i]->codigo) {
                            $dato[$i]->sucursal = '0';
                        }
                        $Insert[] = [
                            'codigo'        => $dato[$i]->codigo,
                            'dv'            => $dato[$i]->dv,
                            'sucursal'      => $dato[$i]->sucursal,
                            'razsoc'        => str_replace('Ñ','N',$dato[$i]->razsoc),
                            'representante' => str_replace('Ñ','N',$dato[$i]->representante),
                            'direccion'     => str_replace('Ñ','N',$dato[$i]->direccion),
                            'telefono'      => $dato[$i]->telefono,
                            'precio'        => $dato[$i]->precio,
                            'conpag'        => $dato[$i]->conpag,
                            'periodicidad'  => $dato[$i]->periodicidad,
                            'codvendedor'   => $dato[$i]->tercvendedor,
                            'cupo'          => $dato[$i]->cupo,
                            'codgrupodcto'  => $dato[$i]->codgrupodcto,
                            'email'         => $dato[$i]->email,
                            'bloqueo'       => $dato[$i]->bloqueo,
                            'infoCupoDisponible'=> $dato[$i]->infoCupoDisponible,
                            'codcliente'    => '0',
                            'estado'        => $dato[$i]->estado,
                            'estadofpagovta'=> $dato[$i]->estadofpagovta
                        ];    
                    }
                    DB::connection($conectionBex)->table('s1e_clientes')->insert($Insert);
                }
                print '◘ Datos insertados la tabla s1e_clientes' . PHP_EOL;
            }
                
            DB::connection($conectionBex)->table('s1e_clientes')
                ->join('tblmcliente','s1e_clientes.codigo','=','tblmcliente.nitcliente')
                ->whereColumn('s1e_clientes.sucursal','tblmcliente.succliente')
                ->whereColumn('s1e_clientes.dv','tblmcliente.dvcliente')
                ->update(['s1e_clientes.codcliente' => DB::raw('tblmcliente.codcliente')]);
            print '◘ Codigo en la tabla s1e_clientes actualizado' . PHP_EOL;                                                                   
            
            DB::connection($conectionBex)->table('s1e_clientes')
                ->join('tblmcliente','s1e_clientes.codcliente','=','tblmcliente.codcliente')
                ->whereColumn('s1e_clientes.sucursal','tblmcliente.succliente')
                ->whereColumn('s1e_clientes.codigo','tblmcliente.nitcliente')
                ->update(['s1e_clientes.estado' => 'C']);
            print '◘ Estado de s1e_clientes actualizado' . PHP_EOL; 

            $insertClient = DB::connection($conectionBex)
                ->table('s1e_clientes')
                ->where('estado','A')
                ->where('s1e_clientes.codcliente','0')
                ->get();   

            if($insertClient->isNotEmpty()){

                    $sql = "
                    INSERT IGNORE INTO tblmcliente (nitcliente, dvcliente, succliente, razcliente, nomcliente, dircliente, telcliente, codbarrio, codtipocliente, codfpagovta, codprecio, coddescuento, email)
                    SELECT codigo, dv, 
                            IF(LENGTH(sucursal) > 4, SUBSTR(sucursal, 2, LENGTH(sucursal)), sucursal) AS sucursal, 
                            razsoc, representante, direccion, telefono, periodicidad, periodicidad, conpag, precio, dv, email
                    FROM s1e_clientes
                    WHERE codcliente = 0 AND estado = 'A';
                ";
                
                DB::connection($conectionBex)->statement($sql);
 
                print '◘ Datos insertados en la tabla tblmcliente' . PHP_EOL; 
                
       

                DB::connection($conectionBex)
                    ->table('s1e_clientes')
                    ->join('tblmcliente','s1e_clientes.codigo','=','tblmcliente.nitcliente')
                    ->whereColumn('s1e_clientes.sucursal','tblmcliente.succliente')
                    ->whereColumn('s1e_clientes.dv','tblmcliente.dvcliente')
                    ->where('estado', 'A')
                    ->update(['s1e_clientes.codcliente' => DB::raw('tblmcliente.codcliente'),
                        'fecingcliente' => $currentDate
                    ]);
                print '◘ Datos actualizados en la tabla s1e_clientes' . PHP_EOL;

                $sql1 = "UPDATE tblmcliente, s1e_clientes
                SET s1e_clientes.estado = 'C'
                WHERE tblmcliente.nitcliente = s1e_clientes.codigo
                AND tblmcliente.succliente = IF(LENGTH(s1e_clientes.sucursal) >4, SUBSTR(s1e_clientes.sucursal, 2, LENGTH(s1e_clientes.sucursal)), s1e_clientes.sucursal)
                AND s1e_clientes.estado = 'A';";

                DB::connection($conectionBex)->statement($sql1);
                print '◘ Datos actualizados en la tabla s1e_clientes' . PHP_EOL;
  
            }      

            DB::connection($conectionBex)
                ->table('tblmcliente')
                ->join('s1e_clientes','tblmcliente.codcliente','=','s1e_clientes.codcliente')
                ->whereColumn('tblmcliente.nitcliente','s1e_clientes.codigo')
                ->update([
                    'tblmcliente.RAZCLIENTE' => DB::raw("IF(s1e_clientes.bloqueo in (1,2), CONCAT(s1e_clientes.razsoc,' ','*', s1e_clientes.bloqueo,'*'), s1e_clientes.razsoc)"),
                    'tblmcliente.NOMCLIENTE' => DB::raw('s1e_clientes.representante'),
                    'tblmcliente.DIRCLIENTE' => DB::raw('s1e_clientes.direccion'),
                    'tblmcliente.TELCLIENTE' => DB::raw('s1e_clientes.telefono'),
                    'tblmcliente.CODFPAGOVTA' => DB::raw('s1e_clientes.conpag'),
                    'tblmcliente.CODPRECIO' => DB::raw('s1e_clientes.precio'),
                    'tblmcliente.EMAIL' => DB::raw('s1e_clientes.email'),
                    'tblmcliente.CUPO' => DB::raw('s1e_clientes.cupo'),
                    'tblmcliente.bloqueo' => DB::raw("IF(s1e_clientes.bloqueo=0, 'N', 'S')") 
                ]);
            print '◘ Datos actualizados en la tabla tblmcliente' . PHP_EOL;

            DB::connection($conectionBex)
            ->table('tblmrutero')
            ->join('s1e_clientes','tblmrutero.CODCLIENTE','=','s1e_clientes.codcliente')
            ->update([
                'tblmrutero.CUPO' => DB::raw('s1e_clientes.cupo'),
                'tblmrutero.CODPRECIO' => DB::raw('s1e_clientes.precio')]);
            print '◘ Datos Actualizados en la tabla tblmrutero' . PHP_EOL;

            if($datosAInsertar->count() > 0){

                DB::connection($conectionBex)->table('tbldclienteadic')->truncate();
                print '◘ La tabla tbldclienteadic fue truncada' . PHP_EOL;
    
                DB::connection($conectionBex)
                    ->table('tbldclienteadic')
                    ->insertUsing([
                        'codcliente', 'id_campo','numreg','valor','tipocliente'
                    ],function ($query) {
                        $query->selectRaw('codcliente,"1" AS id_campo,"1" AS numreg,infoCupoDisponible,"N" as tipocliente')
                        ->from('s1e_clientes')
                        ->where('s1e_clientes.codcliente','<>','0')
                        ->where('estado', 'C')
                        ->groupBy('s1e_clientes.codcliente');
                    }
                );
                print '◘ Datos insertados en la tabla tbldclienteadic' . PHP_EOL;
            }else{
                print '◘ No hay datos para insertar en la tabla tbldclienteadic, no se trunca la tabla' . PHP_EOL;
            }


        } catch (\Exception $e) {
            Tbl_Log::create([
                'id_table'    => $id_importation,
                'type'        => $type,
                'descripcion' => 'Custom::Custom::bex_0006/InsertCustom[insertClientesCustom()] => '.$e->getMessage()
            ]);
            return 1;
        }
    }

    public function insertDptosCustom($conectionBex, $conectionSys, $datosAInsertar, $id_importation, $type)
    {
        try {
            $countPais = DB::connection($conectionBex)->table('tblmdpto')->get()->count();
            if($countPais == 0){
                DB::connection($conectionBex)->table('tblmdpto')->insert([
                    'CODDPTO' => '0',
                    'NOMDPTO' => 'ANTIOQUIA',
                    'CODPAIS' => '0'
                ]);
                print '◘ Datos insertados en la tabla tblmdpto' . PHP_EOL;
            }

            if($datosAInsertar->count() > 0){
                if ($datosAInsertar->isNotEmpty()){
                    if(!Schema::connection($conectionBex)->hasTable('s1e_dptos')) {
                        DB::connection($conectionBex)->statement('
                            CREATE TABLE IF NOT EXISTS s1e_dptos (
                                codpais varchar(5),
                                coddpto varchar(5),
                                descripcion varchar(50)
                            )'
                        );
                        print '◘ Tabla s1e_dptos creada' . PHP_EOL;
                    }

                    DB::connection($conectionBex)->table('s1e_dptos')->truncate();
                    print '◘ Tabla s1e_dptos truncada' . PHP_EOL;

                    $dataToInsert = $datosAInsertar->map(function ($dato) {
                        return [
                            'codpais'     => $dato->codpais,
                            'coddpto'     => $dato->coddpto,
                            'descripcion' => $dato->descripcion,
                        ];
                    })->toArray();
                    DB::connection($conectionBex)->table('s1e_dptos')->insert($dataToInsert);
                    print '◘ Datos insertados en la tabla s1e_dptos' . PHP_EOL;

                    $insertDptos = DB::connection($conectionBex)
                                    ->table('s1e_dptos')
                                    ->leftJoin('tblmdpto', 's1e_dptos.coddpto', '=', 'tblmdpto.CODDPTO')
                                    ->select('s1e_dptos.coddpto', 's1e_dptos.descripcion','s1e_dptos.codpais')
                                    ->whereNull('tblmdpto.coddpto')
                                    ->get();

                    if (!$insertDptos->isEmpty()) {
                        $dataToInsert = $insertDptos->map(function ($dato) {
                            return [
                                'CODDPTO' => $dato->coddpto,
                                'NOMDPTO' => $dato->descripcion,
                                'CODPAIS' => $dato->codpais,
                            ];
                        })->toArray();
                    
                        DB::connection($conectionBex)->table('tblmdpto')->insert($dataToInsert);
                        print '◘ Datos insertados en la tabla tblmdpto' . PHP_EOL;
                    }

                    DB::connection($conectionBex)
                        ->table('tblmdpto')
                        ->join('s1e_dptos','tblmdpto.CODDPTO','=','s1e_dptos.coddpto')
                        ->whereColumn('tblmdpto.CODPAIS','s1e_dptos.codpais')
                        ->update(['tblmdpto.NOMDPTO' => DB::raw('s1e_dptos.descripcion')]);
                }
            }else{
                print '◘ No hay datos para insertar en la tabla tblmdpto' . PHP_EOL;
            }
        } catch (\Exception $e) {
            Tbl_Log::create([
                'id_table'    => $id_importation,
                'type'        => $type,
                'descripcion' => 'Custom::Custom::bex_0006/InsertCustom[insertDptosCustom()] => '.$e->getMessage()
            ]);
            return 1;
        }
    }

    public function insertEstadoPedidosCustom($conectionBex, $conectionSys, $datosAInsertar, $id_importation, $type)
    {
        try {
            $insertCount = count($datosAInsertar);

            if ($insertCount > 0) {
                // Borrar datos de la tabla s1e_estadopedidos
                DB::connection($conectionBex)->table('s1e_estadopedidos')->truncate();
                print '◘ Tabla s1e_estadopedidos truncada' . PHP_EOL;
                
                // Insertar datos en la tabla s1e_estadopedidos
                $datosAInsertarArray = $datosAInsertar->toArray();
                $chunks = array_chunk($datosAInsertarArray, 2000); 
                foreach ($chunks as $chunk) {
                    $dataToInsert = [];
                    foreach ($chunk as $data) {
                        $dataToInsert[] = [
                            'codemp'     => $data['codemp'],
                            'codvend'    => $data['codvend'],
                            'tipoped'    => $data['tipoped'],
                            'numped'     => $data['numped'],
                            'nitcli'     => $data['nitcli'],
                            'succli'     => $data['succli'],
                            'fecped'     => $data['fecped'],
                            'ordenped'   => $data['ordenped'],
                            'codpro'     => $data['codpro'],
                            'refer'      => $data['refer'],
                            'descrip'    => $data['descrip'],
                            'cantped'    => $data['cantped'],
                            'vlrbruped'  => $data['vlrbruped'],
                            'ivabruped'  => $data['ivabruped'],
                            'vlrnetoped' => $data['vlrnetoped'],
                            'cantfacped' => $data['cantfacped'],
                            'estado'     => $data['estado'],
                            'tipo'       => $data['tipo'],
                            'tipofac'    => $data['tipofac'],
                            'factura'    => $data['factura'],
                            'ordenfac'   => $data['ordenfac'],
                            'cantfac'    => $data['cantfac'],
                            'vlrbrufac'  => $data['vlrbrufac'],
                            'ivabrufac'  => $data['ivabrufac'],
                            'vlrnetofac' => $data['vlrnetofac'],
                            'obsped'     => $data['obsped'],
                            'ws_id'      => $data['ws_id'],
                            'codcliente' => null,
                            'codvendedor'=> $data['codvend']
                        ];
                    }
                    DB::connection($conectionBex)->table('s1e_estadopedidos')->insert($dataToInsert);
                }
                print '◘ Datos insertados en la tabla s1e_estadopedidos' . PHP_EOL;

                // Actualizar columna codcliente
                $updateCodcliente = DB::connection($conectionBex)
                    ->table('s1e_estadopedidos')
                    ->join('tblmcliente','s1e_estadopedidos.nitcli','=','tblmcliente.NITCLIENTE')
                    ->whereColumn('tblmcliente.SUCCLIENTE','s1e_estadopedidos.succli')
                    ->update(['s1e_estadopedidos.codcliente' => DB::raw('tblmcliente.CODCLIENTE')]);
                if($updateCodcliente > 0){
                    print '◘ Se actualizo la columna codcliente' . PHP_EOL;
                }else{
                    print '◘ Sin datos por actualizar en la columna codcliente' . PHP_EOL;
                }

                DB::connection($conectionBex)->table('gm_MOB_CAB_PEDIDOS')->truncate();
                print '◘ Tabla gm_MOB_CAB_PEDIDOS truncada' . PHP_EOL;
                DB::connection($conectionBex)->table('gm_MOB_DETPEDIDOS')->truncate();
                print '◘ Tabla gm_MOB_DETPEDIDOS truncada' . PHP_EOL;
                DB::connection($conectionBex)->table('gm_MOB_DETALLEFACT')->truncate();
                print '◘ Tabla gm_MOB_DETALLEFACT truncada' . PHP_EOL;

                // Desactivar only_full_group_by
                DB::connection($conectionBex)->statement("SET SESSION sql_mode = ''");
                print '◘ Only_full_group_by desactivado' . PHP_EOL;

                DB::connection($conectionBex)
                    ->table('gm_MOB_CAB_PEDIDOS')
                    ->insertUsing(['numpedido', 'feccrepedido', 'fecpromesa', 'dircliente', 'vendedor', 
                        'tipopedido', 'estadopedido', 'ordendecompra', 'ordenpedido', 'valtotpedidosiniva', 
                        'iva', 'observaciones', 'retenciones', 'UnidadOperativa'
                    ], function ($query) {
                        $query->selectRaw('numped, SUBSTR(fecped,1,10) AS fecped, "N.A." AS fecpromesa, codcliente,
                        codvendedor, tipoped, estado, ordenped,  "" AS ordenpedido, SUM(vlrbruped) AS valtotpedidosiniva, 
                        SUM(ivabruped) AS iva, obsped AS observaciones, " " AS retenciones," " AS UnidadOperativa')
                        ->from('s1e_estadopedidos')
                        ->groupBy('numped')
                        ->get();
                    });
                print '◘ Datos insertados en la tabla gm_MOB_CAB_PEDIDOS' . PHP_EOL;

                // Restaurar only_full_group_by
                DB::statement("SET SESSION sql_mode = 'ONLY_FULL_GROUP_BY'");
                print '◘ Only_full_group_by restaurado' . PHP_EOL;

                DB::connection($conectionBex)
                    ->table('gm_MOB_DETPEDIDOS')
                    ->insertUsing([
                        'numpedido', 'linea', 'codigoebs', 'descripcion', 'qtypedidooriginal', 'qtyasignada',
                        'qtyenviada', 'qtycancelada', 'precioconiva', 'UnidadOperativa'
                        ], function ($query) {
                            $query->selectRaw('numped, ordenped AS linea, refer, descrip, cantped, cantped AS qtyasignada,
                            cantfacped AS qtyenviada, "0" AS qtycancelada, vlrnetoped AS precioconiva, "" AS UnidadOperativa')
                            ->from('s1e_estadopedidos')
                            ->get();
                        }
                    );
                print '◘ Datos insertados en la tabla gm_MOB_DETPEDIDOS' . PHP_EOL;

                DB::connection($conectionBex)
                    ->table('gm_MOB_DETALLEFACT')
                    ->insertUsing([
                        'numpedido', 'numfactura', 'linea', 'codigoebs', 'descripcion', 'qtyfacturada', 
                        'preciosiniva', 'precioextendido', 'iva', 'valorconiva','UnidadOperativa'
                    ], function ($query) {
                        $query->selectRaw('numped, factura, ordenfac AS linea, refer, descrip, 
                            cantfac AS qtyfacturada,vlrbrufac AS preciosiniva, "0" AS precioextendido, 
                            ivabrufac AS iva, vlrnetofac AS valorconiva, "" AS UnidadOperativa')
                            ->from('s1e_estadopedidos')
                            ->where('factura','>',0)
                            ->get();
                        }
                    );
                print '◘ Datos insertados en la tabla gm_MOB_DETALLEFACT' . PHP_EOL;
            }else{
                print '◘ Sin datos para insertar en estado pedidos.' . PHP_EOL;
            }
        } catch (\Exception $e) {
            Tbl_Log::create([
                'id_table'    => $id_importation,
                'type'        => $type,
                'descripcion' => 'Custom::Custom::bex_0006/InsertCustom[insertEstadoPedidosCustom()] => '.$e->getMessage()
            ]);
            return 1;
        }
    }

    public function insertEntregasCustom($conectionBex, $conectionSys, $datosAInsertar, $id_importation, $type)
    {
        try {
            $insertCount = count($datosAInsertar);
            if ($insertCount > 0) {
                DB::connection($conectionBex)->table('gm_MOB_ENTREGAS')->truncate();
                print '◘ Tabla gm_MOB_ENTREGAS truncada' . PHP_EOL;

                // Insertar datos en la tabla s1e_estadopedidos
                $datosAInsertarArray = $datosAInsertar->toArray();
                $chunks = array_chunk($datosAInsertarArray, 1000); 
                foreach ($chunks as $chunk) {
                    $dataToInsert = [];
                    foreach ($chunk as $data) {
                        $dataToInsert[] = [
                            'idgm_MOB_ENTREGAS'=> $data['id_entregas'],
                            'tipopedido'       => $data['tipopedido'],
                            'numpedido'        => $data['numpedido'],
                            'numentrega'       => $data['numentrega'],
                            'placa'            => $data['placa'],
                            'conductor'        => $data['conductor'],
                            'fecsalida'        => $data['fecsalida'],
                            'UnidadOperativa'  => $data['UnidadOperativa'],
                            'estado'           => null
                        ];
                    }
                    DB::connection($conectionBex)->table('gm_MOB_ENTREGAS')->insert($dataToInsert);
                }
                print '◘ Datos insertados en la tabla gm_MOB_ENTREGAS' . PHP_EOL;
            }else{
                print '◘ Sin datos para insertar en entregas.' . PHP_EOL;
            }
        } catch (\Exception $e) {
            Tbl_Log::create([
                'id_table'    => $id_importation,
                'type'        => $type,
                'descripcion' => 'Custom::Custom::bex_0006/InsertCustom[insertEntregasCustom()] => '.$e->getMessage()
            ]);
            return 1;
        }

    }

    public function insertInventarioCustom($conectionBex, $conectionSys, $datosAInsertar, $id_importation, $type, $modelInstance)
    {
        try {
            $tblbinventario = count($datosAInsertar);
            if($tblbinventario > 10){
                //Conexion plafor_sys
                $tblslicencias = DB::connection($conectionSys)
                    ->table('tblslicencias')
                    ->select('borrardstockimportando', 'creabodegaempresa')
                    ->where('bdlicencias', 'platafor_pi287')
                    ->first();
                
                $tblmimpuesto = DB::connection($conectionBex)
                    ->table('tblmimpuesto')
                    ->select('codimpuesto')
                    ->get();
                
                $iva = $tblmimpuesto->pluck('codimpuesto')->toArray();
                $imp = count($tblmimpuesto);

                if (!empty($iva)) {
                    for($i=0; $i<$imp; $i++){
                        $t16BexInventarios = $modelInstance::where('iva', $iva[$i])->update(['estadoimpuesto' => 'C']);
                    }
                    print '◘ Columna "estadoimpuesto" actualizada en al tabla t16_bex_inventarios' . PHP_EOL;
                }

                //Inserta datos en estado A en la tabla tblmimpuesto
                $insertDataTblmimpuesto = $modelInstance::insertDataTblmimpuesto()->get();

                if(sizeof($insertDataTblmimpuesto) > 0){
                    DB::connection($conectionBex)->table('tblmimpuesto')->insert($insertDataTblmimpuesto->toArray());
                    print '◘ Datos insertados con exito en la tabla tblmimpuesto' . PHP_EOL;
                }

                //Update estadobodega tabla t16_bex_inventarios
                $tblmbodega = DB::connection($conectionBex)->table('tblmbodega')->get();
                $codBodega = $tblmbodega->pluck('CODBODEGA')->toArray();
                $bod = count($codBodega);

                if (!empty($codBodega)) {
                    for($i=0; $i<$bod; $i++){
                        $t16BexInventarios = $modelInstance::where('bodega', $codBodega[$i])->update(['estadobodega' => 'C']);
                    }
                    print '◘ Columna "estadobodega" actualizada en al tabla t16_bex_inventarios' . PHP_EOL;
                }

                //Inserta datos en estado A en la tabla tblmbodega
                $insertDataToTblmbodega = $modelInstance::insertDataToTblmbodega()->get();
                if(sizeof($insertDataToTblmbodega) > 0){
                    DB::connection($conectionBex)->table('tblmbodega')->insert($insertDataToTblmbodega->toArray());
                    print '◘ Datos insertados con exito en la tabla tblmbodega' . PHP_EOL;
                }
                
                if($tblslicencias->borrardstockimportando == "S"){
                    DB::connection($conectionBex)->table('tbldstock')->truncate();
                    print '◘ Datos eliminados con exito en la tabla tbldstock' . PHP_EOL;
                }

                //Insertar datos en la tabla tbldstock
                $inset=(count($datosAInsertar));
                if($inset > 0){
                    $datosAInsert = json_decode(json_encode($datosAInsertar,true));
                    // Insertar los datos en lotes
                    if(sizeof($datosAInsertar) > 0){
                        foreach (array_chunk($datosAInsert,3000) as $dato) {
                            $Insert = [];
                            $count = count($dato);
                            for($i=0;$i<$count;$i++) {
                                $Insert[] = [
                                    'CODPRODUCTO' => $dato[$i]->producto,
                                    'CODBODEGA'  => $dato[$i]->bodega,
                                    'CODIMPUESTO' => $dato[$i]->iva,
                                    'EXISTENCIA_STOCK' => $dato[$i]->inventario
                                ];    
                            }  
                            $stock = DB::connection($conectionBex)->table('tbldstock')->insertOrIgnore($Insert);
                        }
                        print '◘ Datos insertados en la tabla tbldstock' . PHP_EOL;
                    }
                    
                    if($stock>0){

                        DB::connection($conectionBex)->table('s1e_inventarios')->truncate();
                        print '◘ Datos eliminados con exito en la tabla s1e_inventarios' . PHP_EOL;
                        
                        foreach (array_chunk($datosAInsert,3000) as $dato) {
                            $Insert = [];
                            $count = count($dato);
                            for($i=0;$i<$count;$i++) {
                                $Insert[] = [
                                    'bodega'         => $dato[$i]->bodega,
                                    'iva'            => $dato[$i]->iva,
                                    'producto'       => $dato[$i]->producto,
                                    'inventario'     => $dato[$i]->inventario,
                                    'estadoimpuesto' => $dato[$i]->estadoimpuesto,
                                    'estadobodega'   => $dato[$i]->estadobodega
                                ];    
                            }
                            DB::connection($conectionBex)->table('s1e_inventarios')->insertOrIgnore($Insert);
                        }
                        print '◘ Datos insertados en la tabla inventarios' . PHP_EOL;
                    }
                    print '◘ Datos Actualizados en la tabla tbldstock' . PHP_EOL;
                }else{
                    print '◘ No hay datos para Actualizar en la tabla tbldstock' . PHP_EOL;
                } 
                print '◘ Datos insertados en la tabla tbldstock' . PHP_EOL;
            }else{
                Tbl_Log::create([
                    'id_table'    => $id_importation,
                    'type'  => $type,
                    'descripcion' => 'Custom::bex_0006/InsertCustom[insertInventarioCustom()] => Tabla inventario se encuentra vacia.'
                ]);
                return 1;
            }
            $configDB = $this->connectionDB('bex_0006', 'local'); 
        } catch (\Exception $e) {
            Tbl_Log::create([
                'id_table'    => $id_importation,
                'type'        => $type,
                'descripcion' => 'Custom::Custom::bex_0006/InsertCustom[insertInventarioCustom()] => '.$e->getMessage()
            ]);
            return 1;
        }
    }

    public function insertMpiosCustom($conectionBex, $conectionSys, $datosAInsertar, $id_importation, $type)
    {
        try {
            $countMpios = DB::connection($conectionBex)->table('tblmmpio')->count();
            if ($countMpios === 0) {
                $dataToInsert = [
                    [
                        'CODDPTO' => '0',
                        'CODMPIO' => '0',
                        'NOMMPIO' => 'N.A.',
                    ],
                ];
                DB::connection($conectionBex)->table('tblmmpio')->insert($dataToInsert);
                print '◘ Datos insertados en la tabla tblmmpio' . PHP_EOL;
            }

            if ($datosAInsertar->isNotEmpty()) {
                if(!Schema::connection($conectionBex)->hasTable('s1e_mpios')) {
                    DB::connection($conectionBex)->statement('
                        CREATE TABLE IF NOT EXISTS s1e_mpios (
                            codpais varchar(5),
                            coddpto varchar(5),
                            codmpio varchar(5),
                            descripcion varchar(50),
                            indicador varchar(50)
                        )'
                    );
                    print '◘ Tabla s1e_mpios creada' . PHP_EOL;
                }
                
                DB::connection($conectionBex)->table('s1e_mpios')->truncate();
                print '◘ Tabla s1e_mpios truncada' . PHP_EOL;

                if ($datosAInsertar->isNotEmpty()) {
                    $dataToInsert = $datosAInsertar->map(function ($dato) {
                        return [
                            'codpais'     => $dato->codpais,
                            'coddpto'     => $dato->coddpto,
                            'codmpio'     => $dato->codmpio,
                            'descripcion' => $dato->descripcion,
                            'indicador'   => $dato->indicador,
                        ];
                    })->toArray();
                    DB::connection($conectionBex)->table('s1e_mpios')->insert($dataToInsert);
                    print '◘ Datos insertado en la tbala s1e_mpios' . PHP_EOL;
                }
                
                $insertMpios = DB::connection($conectionBex)
                                ->table('s1e_mpios')
                                ->leftJoin('tblmmpio', 's1e_mpios.coddpto', '=', 'tblmmpio.CODDPTO')
                                ->select('s1e_mpios.codpais', 's1e_mpios.coddpto','s1e_mpios.codmpio','s1e_mpios.descripcion')
                                ->whereNull('tblmmpio.codmpio')
                                ->get();

                if (!$insertMpios->isEmpty()) {
                    $dataToInsert = $insertMpios->map(function ($dato) {
                        return [
                            'CODDPTO' => $dato->coddpto,
                            'CODMPIO' => $dato->coddpto . $dato->codmpio,
                            'NOMMPIO' => $dato->descripcion,
                        ];
                    })->toArray();
                    DB::connection($conectionBex)->table('tblmmpio')->insert($dataToInsert);
                    print '◘ Datos insertado en la tbala tblmmpio' . PHP_EOL;
                }

                /* DB::connection($conectionBex)
                    ->table('tblmmpio')
                    ->join('s1e_mpios','tblmmpio.CODMPIO','=','CONCAT(s1e_mpios.coddpto,s1e_mpios.codmpio)')
                    ->whereColumn('tblmmpio.CODDPTO','s1e_mpios.coddpto')
                    ->update(['tblmmpio.NOMMPIO' => DB::raw('s1e_mpios.descripcion')]);
                */
            }else{
                print '◘ No hay datos para insertar en la tabla tblmmpio' . PHP_EOL;
            }
        } catch (\Exception $e) {
            Tbl_Log::create([
                'id_table'    => $id_importation,
                'type'        => $type,
                'descripcion' => 'Custom::Custom::bex_0006/InsertCustom[insertMpiosCustom()] => '.$e->getMessage()
            ]);
            return 1;
        }
    }

    public function insertPaisCustom($conectionBex,  $conectionSys, $datosAInsertar, $id_importation, $type)
    {
        try {
            $countPais = DB::connection($conectionBex)->table('tblmpais')->count();
            if ($countPais === 0) {
                $dataToInsert = [
                    [
                        'CODPAIS' => '0',
                        'NOMPAIS' => 'COLOMBIA',
                    ],
                ];
                DB::connection($conectionBex)->table('tblmpais')->insert($dataToInsert);
                print '◘ Datos insertados en la tabla tblmpais' . PHP_EOL;
            }

            if ($datosAInsertar->isNotEmpty()) {
                if (!Schema::connection($conectionBex)->hasTable('s1e_paises')) {
                    DB::connection($conectionBex)->statement('
                        CREATE TABLE IF NOT EXISTS s1e_paises (
                            codpais varchar(5),
                            descripcion varchar(50)
                        )'
                    );
                    print '◘ Tabla s1e_paises creada' . PHP_EOL;
                }

                DB::connection($conectionBex)->table('s1e_paises')->truncate();
                print '◘ Tabla s1e_paises truncada' . PHP_EOL;

                if ($datosAInsertar->isNotEmpty()) {
                    $dataToInsert = $datosAInsertar->map(function ($dato) {
                        return [
                            'codpais'     => $dato->codpais,
                            'descripcion' => $dato->descripcion,
                        ];
                    })->toArray();
                    DB::connection($conectionBex)->table('s1e_paises')->insert($dataToInsert);
                    print '◘ Datos insertados en la tabla s1e_paises' . PHP_EOL;
                }
                
                $insertPais = DB::connection($conectionBex)
                                ->table('s1e_paises')
                                ->leftJoin('tblmpais', 's1e_paises.codpais', '=', 'tblmpais.CODPAIS')
                                ->select('s1e_paises.codpais', 's1e_paises.descripcion')
                                ->whereNull('tblmpais.codpais')
                                ->get();

                if (!$insertPais->isEmpty()) {
                    $dataToInsert = $insertPais->map(function ($dato) {
                        return [
                            'CODPAIS' => $dato->codpais,
                            'NOMPAIS' => $dato->descripcion,
                        ];
                    })->toArray();
                    DB::connection($conectionBex)->table('tblmpais')->insert($dataToInsert);
                    print '◘ Datos insertados en la tabla tblmpais' . PHP_EOL;
                }

                DB::connection($conectionBex)
                    ->table('tblmpais')
                    ->join('s1e_paises','tblmpais.CODPAIS','=','s1e_paises.codpais')
                    ->update(['tblmpais.NOMPAIS' => DB::raw('s1e_paises.descripcion')]);
            }else{
                print '◘ No hay datos para insertar en la tabla tblmpais' . PHP_EOL;
            }
        } catch (\Exception $e) {
            Tbl_Log::create([
                'id_table'    => $id_importation,
                'type'        => $type,
                'descripcion' => 'Custom::Custom::bex_0006/InsertCustom[insertPaisCustom()] => '.$e->getMessage()
            ]);
            return 1;
        }
    }

    public function insertPreciosCustom($conectionBex, $conectionSys, $datosAInsertar, $id_importation, $type)
    {
        try {
            DB::connection($conectionBex)->table('s1e_precios')->truncate();
            print '◘ Tabla s1e_precios truncada' . PHP_EOL;

            $datosAInsert = json_decode(json_encode($datosAInsertar, true));
            if (!empty($datosAInsertar)) {
                foreach (array_chunk($datosAInsert, 3000) as $chunk) {
                    $dataToInsert = [];
                    foreach ($chunk as $dato) {
                        $dataToInsert[] = [
                            'lista'        => $dato->lista,
                            'producto'     => $dato->producto,
                            'precio'       => $dato->precio,
                            'estadoprecio' => $dato->estadoprecio
                        ];
                    }
                    DB::connection($conectionBex)->table('s1e_precios')->insert($dataToInsert);
                }
                print '◘ Datos insertados en la tabla s1e_precios' . PHP_EOL;
            }

            //Actualizar estado t25_bex_precios
            DB::connection($conectionBex)
                ->table('s1e_precios')
                ->join('tblmprecio','s1e_precios.lista','=','tblmprecio.codprecio')
                ->update(['s1e_precios.estadoprecio' => 'C']);
            print '◘ estadoprecio actualizado en la tabla s1e_precios' . PHP_EOL;
            
            // Insertar datos en la tabla tblmprecio
            $insertPrecio = DB::connection($conectionBex)
                            ->table('s1e_precios')
                            ->where('estadoprecio','A')
                            ->groupBy('lista')
                            ->get();   

            if (count($insertPrecio) > 0) {
                $dataToInsert = $insertPrecio->map(function ($dato) {
                    return [
                        'codprecio' => $dato->lista,
                        'nomprecio' => 'LISTA PRECIO ' . $dato->lista
                    ];
                })->toArray();
                DB::connection($conectionBex)->table('tblmprecio')->insert($dataToInsert);
                print '◘ Datos insertados en la tabla tblmprecio' . PHP_EOL;
            }

            //Delete a la tabla tbldproductoprecio
            if(sizeof($datosAInsertar) > 0){
                DB::connection($conectionBex)->table('tbldproductoprecio')->truncate();
                print '◘ Tabla tbldproductoprecio truncada' . PHP_EOL;

                DB::connection($conectionBex)
                    ->table('tbldproductoprecio')
                    ->insertUsing([
                        'CODPRODUCTO', 'codprecio','precioproductoprecio'
                    ],function ($query) {
                        $query->selectRaw('producto,lista,precio')
                        ->from('s1e_precios')
                        ->join('tblmproducto','s1e_precios.producto','=','tblmproducto.CODPRODUCTO')
                        ->distinct()
                        ->get();
                    }
                );
                print '◘ Datos insertados en la tabla tbldproductoprecio' . PHP_EOL;
            }
        } catch (\Exception $e) {
            Tbl_Log::create([
                'id_table'    => $id_importation,
                'type'        => $type,
                'descripcion' => 'Custom::Custom::bex_0006/InsertCustom[insertPreciosCustom()] => '.$e->getMessage()
            ]);
            return 1;
        }
    }

    public function insertProductsCustom($conectionBex, $conectionSys, $datosAInsertar, $id_importation, $type)
    {
        try {
            DB::connection($conectionBex)->table('s1e_productos')->truncate();
            print '◘ Tabla s1e_productos truncada' . PHP_EOL;

            $datosAInsert = json_decode(json_encode($datosAInsertar), true);

            foreach ($datosAInsert as &$dato) {
                foreach ($dato as $key => &$value) {
                    $value = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $value);
                }
            }
            unset($dato); // Desvincula la última referencia a $dato
            unset($value); // Desvincula la última referencia a $value

            if (!empty($datosAInsert)) {
                $chunkedData = array_chunk($datosAInsert, 3000);
                foreach ($chunkedData as $chunk) {
                    $formattedData = array_map(function ($dato) {
                        return [
                            'codigo'          => $dato['codigo'],
                            'descripcion'     => $dato['descripcion'],
                            'codunidademp'    => $dato['codunidademp'],
                            'peso'            => $dato['peso'],
                            'codproveedor'    => $dato['codproveedor'],
                            'nomproveedor'    => $dato['nomproveedor'],
                            'unidadventa'     => $dato['unidadventa'],
                            'estado'          => $dato['estado'],
                            'estadounidademp' => $dato['estado_unidademp'],
                            'estadoproveedor' => $dato['estadoproveedor']
                        ];
                    }, $chunk);

                    DB::connection($conectionBex)->table('s1e_productos')->insert($formattedData);
                    print '◘ Datos insertados en la tabla s1e_productos' . PHP_EOL;
                }
            }

            // DB::connection($conectionBex)
            //     ->table('s1e_productos')
            //     ->update([
            //         'codunidademp' => DB::raw("CASE 
            //             WHEN codunidademp = 'Kilogramo' THEN 'KIL'
            //             WHEN codunidademp = 'Millar' THEN 'MIL'
            //             WHEN codunidademp = 'Rollo' THEN 'ROL'
            //             WHEN codunidademp = 'Caja' THEN 'CAJ'
            //             WHEN codunidademp = 'Libra' THEN 'LIB'
            //             WHEN codunidademp = 'Gruesa' THEN 'GRU'
            //             WHEN codunidademp = 'Cubeta' THEN 'CUB'
            //             WHEN codunidademp = 'Unidad' THEN 'UND'
            //             ELSE ''
            //         END")
            //     ]);
             // Update estado_unidademp tabla s1e_productos
            DB::connection($conectionBex)
                ->table('s1e_productos')
                ->join('tblmunidademp','s1e_productos.codunidademp','=','tblmunidademp.codunidademp')
                ->update(['s1e_productos.estadounidademp' => 'C']);
            print '◘ Estados actualizados en la columna "estadounidademp"' . PHP_EOL;
                            
            //Inserta datos en estado A en la tabla tblmunidademp
            $insertUndemp = DB::connection($conectionBex)
                            ->table('s1e_productos')
                            ->where('estadounidademp','A')
                            ->where('codigo','<>','')
                            ->get();   

            if( count($insertUndemp) > 0){
                DB::connection($conectionBex)
                    ->table('tblmunidademp')
                    ->insertUsing([
                        'codunidademp', 'NOMUNIDADEMP'
                    ],function ($query) {
                        $query->select('codunidademp', DB::raw('CONCAT(codunidademp) AS NOMUNIDADEMP'))
                        ->from('s1e_productos')
                        ->where('estadounidademp', 'A')
                        ->where('codigo','<>','')
                        ->groupBy('codunidademp');
                    }
                );
                print '◘ Datos Actualizados en la tabla tblmunidademp' . PHP_EOL;
            }
            
            DB::connection($conectionBex)->table('s1e_productos')
                ->join('tblmproveedor','s1e_productos.codproveedor','=','tblmproveedor.CODPROVEEDOR')
                ->update(['s1e_productos.estadoproveedor' => 'C']);
            print '◘ Estados actualizados  en la columna "estadoproveedor"' . PHP_EOL;
            
            //Inserta datos en estado A en la tabla tblmunidademp
            $insertProveed = DB::connection($conectionBex)
                ->table('s1e_productos')
                ->where('estadoproveedor','A')
                ->where('codigo','<>','')
                ->get();   
            
            $grupoproducto = DB::connection($conectionBex)->table('tblmgrupoproducto')->get()->count();
            if($grupoproducto == 0){
                DB::connection($conectionBex)->table('tblmgrupoproducto')->insert([
                    'CODGRUPOPRODUCTO' => '0',
                    'NOMGRUPOPRODUCTO' => 'N.A'
                ]);
                print '◘ Datos insertados en la tabla tblmgrupoproducto' . PHP_EOL;
            }

            if( count($insertProveed) > 0){
                DB::connection($conectionBex)
                    ->table('tblmproveedor')
                    ->insertUsing([
                        'CODPROVEEDOR', 'NOMPROVEEDOR'
                    ],function ($query) {
                        $query->select('codproveedor', DB::raw('CONCAT(nomproveedor) as NOMPROVEEDOR'))
                        ->from('s1e_productos')
                        ->where('estadoproveedor', 'A')
                        ->where('codigo','<>','')
                        ->groupBy('codproveedor');
                    }
                );
                print '◘ Datos Actualizados en la tabla tblmproveedor' . PHP_EOL;
            }
            
            DB::connection($conectionBex)
                ->table('tblmproducto')
                ->join('s1e_productos','tblmproducto.CODPRODUCTO','=','s1e_productos.codigo')
                ->where('s1e_productos.codigo','<>','')
                ->update([
                    'tblmproducto.NOMPRODUCTO' => DB::raw('s1e_productos.descripcion'),
                    'tblmproducto.PLUPRODUCTO' => DB::raw('s1e_productos.codigo'),
                    'tblmproducto.codunidademp' => DB::raw('s1e_productos.codunidademp'),
                    'tblmproducto.PESO' => DB::raw('s1e_productos.peso'),
                    'tblmproducto.CODPROVEEDOR' => DB::raw('s1e_productos.codproveedor'),
                    'tblmproducto.unidadventa' => DB::raw('s1e_productos.unidadventa')
                ]);
            print '◘ Datos actualizados en la tabla tblmproducto' . PHP_EOL;

            // Códigos de productos que necesitan actualizarse
            DB::connection($conectionBex)
                ->table('s1e_productos')
                ->join('tblmproducto','s1e_productos.codigo','=','tblmproducto.CODPRODUCTO')
                ->update(['s1e_productos.estado' => 'C']);
            print '◘ Estados actualizados en la columna "estado de s1e_productos"' . PHP_EOL;

            //Ejecutar distint
            $insertProduc = DB::connection($conectionBex)
                                ->table('s1e_productos')
                                ->where('estado','A')
                                ->where('codigo','<>','')
                                ->get();   

            //Inserta datos en estado A en la tabla tblmproducto
            if(count($insertProduc) > 0){
                DB::connection($conectionBex)
                    ->table('tblmproducto')
                    ->insertUsing([
                        'CODPRODUCTO', 'codempresa','pluproducto','nomproducto','codunidademp','bonentregaproducto',
                            'codgrupoproducto','estadoproducto','unidadventa'
                    ], function ($query) {
                        $query->selectRaw('codigo,"001" as codempresa,codigo,descripcion,s1e_productos.codunidademp,
                        "0" as bonentregaproducto,"0" as codgrupoproducto,s1e_productos.estado,unidadventa')
                        ->distinct()
                        ->from('s1e_productos')
                        ->where('estado', 'A')
                        ->where('codigo','<>','')
                        ->get();
                    }
                );
                print '◘ Datos Actualizados en la tabla tblmproducto' . PHP_EOL;
            }
        } catch (\Exception $e) {
            Tbl_Log::create([
                'id_table'    => $id_importation,
                'type'        => $type,
                'descripcion' => 'Custom::Custom::bex_0006/InsertCustom[insertProductsCustom()] => '.$e->getMessage()
            ]);
            return 1;
        }
    }

    public function insertRuteroCustom($conectionBex, $conectionSys, $datosAInsertar, $id_importation, $type, $modelInstance)
    {
        try {

            $modelInstance::whereRaw('cliente = sucursal')
                            ->whereRaw('sucursal <> 0')
                            ->update(['sucursal' => '0']);
             print '◘ Rutero actualizado en la columna de sucursal' . PHP_EOL;
            $inset=(count($datosAInsertar));
 
            if($inset > 0){
                DB::connection($conectionBex)->table('s1e_ruteros')->truncate();
                print '◘ Tabla s1e_ruteros truncada' . PHP_EOL;

                $datosAInsert = json_decode(json_encode($datosAInsertar,true));
                // Insertar los datos en lotes
                if(sizeof($datosAInsertar) > 0){
                    foreach (array_chunk($datosAInsert,3000) as $dato) {
                        $Insert = [];
                        $count = count($dato);
                        for($i=0;$i<$count;$i++) {
                            if($dato[$i]->sucursal == $dato[$i]->cliente){
                                $dato[$i]->sucursal = '0';
                            }
                            $Insert[] = [
                                'codvendedor'    => $dato[$i]->tercvendedor,
                                'dia'            => str_replace("-", "/", $dato[$i]->dia),
                                'dia_descrip'    => $dato[$i]->dia_descrip,
                                'cliente'        => $dato[$i]->cliente,
                                'dv'             => $dato[$i]->dv,
                                'sucursal'       => $dato[$i]->sucursal,
                                'secuencia'      => $dato[$i]->secuencia,
                                'estadodiarutero'=> $dato[$i]->estadodiarutero
                            ];    
                        }  
                        DB::connection($conectionBex)->table('s1e_ruteros')->insert($Insert);
                    }
                    print '◘ Datos insertados en la tabla s1e_ruteros' . PHP_EOL;
                }

                DB::connection($conectionBex)
                    ->table('s1e_ruteros')
                    ->join('tblmdiarutero','s1e_ruteros.dia','=','tblmdiarutero.diarutero')
                    ->update(['s1e_ruteros.estadodiarutero' => 'C']);
                                    
                $ruteroDias = DB::connection($conectionBex)
                                ->table('s1e_ruteros')
                                ->select(['dia','dia_descrip'])
                                ->distinct()
                                ->where('estadodiarutero', 'A')
                                ->where('cliente','<>','')
                                ->get();

                if (count($ruteroDias) > 0) {
                    $insertData = array_map(function ($ruteroDia) {
                        return [
                            'DIARUTERO' => $ruteroDia->dia,
                            'NOMDIARUTERO' => $ruteroDia->dia_descrip
                        ];
                    }, $ruteroDias->toArray());
                
                    DB::connection($conectionBex)->table('tblmdiarutero')->insert($insertData);
                    print '◘ Datos insertados en la tabla tblmdiarutero' . PHP_EOL;
                }                            
                
                $tblslicencias = DB::connection($conectionSys)
                            ->table('tblslicencias')
                            ->select('borrarruteroimportando')
                            ->where('bdlicencias', 'platafor_pi287')
                            ->first();
                
                if($tblslicencias->borrarruteroimportando == "S"){
                    DB::connection($conectionBex)->table('tblmrutero')->truncate();
                    print '◘ Datos eliminados con exito en la tabla tblmrutero' . PHP_EOL;
       
                    DB::connection($conectionBex)
                    ->table('tblmrutero')
                    ->insertUsing([
                            'CODVENDEDOR', 'DIARUTERO','SECUENCIARUTERO','CODCLIENTE','CUPO','CODPRECIO','CODGRUPODCTO'
                        ], function ($query) {
                                $query->selectRaw('tblmvendedor.codvendedor,dia, secuencia,
                                codcliente, cupo, "0" as precio,"0" as codgrupodcto')
                                ->distinct()
                                ->from('s1e_ruteros')
                                ->join('tblmcliente', function ($join) {
                                    $join->on('s1e_ruteros.cliente', '=', 'tblmcliente.nitcliente')
                                        ->on('s1e_ruteros.sucursal', '=', 'tblmcliente.succliente');
                                })
                                ->join('tblmvendedor', 's1e_ruteros.codvendedor', '=', 'tblmvendedor.tercvendedor')
                                ->join('tblmdiarutero', 's1e_ruteros.dia', '=', 'tblmdiarutero.diarutero')
                                ->where('s1e_ruteros.cliente', '<>', '')
                                ->get();
                            }
                            );

                    print "◘ Datos insertados en la tabla tblmrutero." . PHP_EOL;
                }else{
                    DB::connection($conectionBex)
                    ->table('s1e_ruteros')
                    ->join('s1e_clientes','s1e_ruteros.cliente','=','s1e_clientes.codigo')
                    ->join('tblmvendedor','s1e_ruteros.codvendedor','=','tblmvendedor.CODVENDEDOR')
                    ->join('tblmdiarutero','s1e_ruteros.dia','=','tblmdiarutero.diarutero')
                    ->whereColumn('s1e_ruteros.sucursal','s1e_clientes.sucursal')
                    ->where('s1e_ruteros.cliente','<>','')
                    ->select('tblmvendedor.codvendedor','s1e_ruteros.dia','s1e_ruteros.secuencia',
                            's1e_clientes.codcliente','s1e_clientes.cupo','s1e_clientes.precio',
                            's1e_clientes.codgrupodcto')
                    ->distinct()
                    ->get();
                }

                DB::connection($conectionBex)
                    ->table('tblmrutero')
                    ->join('s1e_clientes','tblmrutero.CODCLIENTE','=','s1e_clientes.codcliente')
                    ->update([
                        'tblmrutero.CUPO' => DB::raw('s1e_clientes.cupo'),
                        'tblmrutero.CODPRECIO' => DB::raw('s1e_clientes.precio')]);
                print '◘ Datos Actualizados en la tabla tblmrutero' . PHP_EOL;
            }else{
                print '◘ No hay datos para insertar en la tabla ruteros.' . PHP_EOL;
            }
        } catch (\Exception $e) {
            Tbl_Log::create([
                'id_table'    => $id_importation,
                'type'        => $type,
                'descripcion' => 'Custom::Custom::bex_0006/InsertCustom[insertRuteroCustom()] => '.$e->getMessage()
            ]);
            return 1;
        }
    }

    public function insertVendedoresCustom($conectionBex, $conectionSys, $datosAInsertar, $id_importation, $type,$modelInstance)
    {
        try {
            $fechaActual = Carbon::now();

            $tblmsupervisor = DB::connection($conectionBex)->table('tblmsupervisor')->get();

            foreach($tblmsupervisor as $data){
                $modelInstance::where('codsupervisor', '=', $data->CODSUPERVISOR)->update(['estadosuperv' => 'C']);
            }
            print '◘ Se actualizo la columna estadoSupervisor en la tabla vendedores' . PHP_EOL;

            //Inserta datos en estado A en la tabla tblmbodega
            $InsertDataToTblmsupervisor = $modelInstance::InsertDataToTblmsupervisor()->get();

            if ($InsertDataToTblmsupervisor->isNotEmpty()) {
                $dataToInsert = $InsertDataToTblmsupervisor->map(function ($dato) {
                    return [
                        'CODSUPERVISOR' => $dato->codsupervisor,
                        'NOMSUPERVISOR' => 'SUPERVISOR ' . $dato->nomsupervisor,
                        'CLAVESUPERVISOR' => $dato->codsupervisor
                    ];
                })->toArray();
            
                DB::connection($conectionBex)->table('tblmsupervisor')->insert($dataToInsert);
                print '◘ Datos insertados en la tabla tblmsupervisor' . PHP_EOL;
            }

            $decuento = DB::connection($conectionBex)->table('tblmdescuento')->get()->count();
            if($decuento == 0){
                DB::connection($conectionBex)->table('tblmdescuento')->insert([
                    'coddescuento' => '000',
                    'nomdescuento' => 'N.A'
                ]);
                print '◘ Datos insertados en la tabla tblmdescuento' . PHP_EOL;
            }

            $grupodcto = DB::connection($conectionBex)->table('tblmgrupodcto')->get()->count();
            if($grupodcto == 0){
                DB::connection($conectionBex)->table('tblmgrupodcto')->insert([
                    'codgrupodcto' => '000',
                    'nomgrupodcto' => 'N.A'
                ]);
                print '◘ Datos insertados en la tabla tblmgrupodcto' . PHP_EOL;
            }

            $grupodcto = DB::connection($conectionBex)->table('tblmportafolio')->get()->count();
            if($grupodcto == 0){
                DB::connection($conectionBex)->table('tblmportafolio')->insert([
                    'CODPORTAFOLIO' => '0',
                    'NOMPORTAFOLIO' => 'PORTAFOLIO 0'
                ]);
                print '◘ Datos insertados en la tabla tblmportafolio' . PHP_EOL;
            }
            
            $datosAInsert = json_decode(json_encode($datosAInsertar,true));
            $inset = count($datosAInsert);

            if ($inset > 0) {
                // Trunca la tabla
                DB::connection($conectionBex)->table('s1e_vendedores')->truncate();
                print "◘ Tabla s1e_vendedores truncada" . PHP_EOL;

                foreach ($datosAInsert as &$dato) {
                    foreach ($dato as $key => &$value) {
                        $value = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $value);
                    }
                }
                unset($dato); // Desvincula la última referencia a $dato
                unset($value); // Desvincula la última referencia a $value

                $dataToInsert = [];
                foreach ($datosAInsert as $dato) {
                    $dataToInsert[] = [
                        'compania'      => $dato->compania,
                        'codvendedor'   => $dato->tercvendedor,
                        'nomvendedor'   => $dato->nomvendedor,
                        'coddescuento'  => $dato->coddescuento,
                        'codportafolio' => $dato->codportafolio,
                        'CODSUPERVISOR' => $dato->codsupervisor,
                        'nomsupervisor' => $dato->nomsupervisor,
                        'nitvendedor'   => $dato->nitvendedor,
                        'centro_ope'    => $dato->centroop,
                        'bodega'        => $dato->bodega,
                        'tipodocpedido' => $dato->tipodoc,
                        'cargue'        => $dato->cargue,
                        'estado'        => 'A'
                    ];
                }

                DB::connection($conectionBex)->table('s1e_vendedores')->insert($dataToInsert); 
                print "◘ Datos insertados en la tabla s1e_vendedores" . PHP_EOL;   
                
                DB::connection($conectionBex)
                    ->table('tblmvendedor')
                    ->join('s1e_vendedores','tblmvendedor.CODVENDEDOR','=','s1e_vendedores.codvendedor')
                    ->update([
                        'tblmvendedor.CO' => DB::raw('s1e_vendedores.centro_ope'),
                        'tblmvendedor.TERCVENDEDOR' => DB::raw('s1e_vendedores.codvendedor')]);
                print "◘ Datos actualizados en la tabla s1e_vendedores" . PHP_EOL;

                 
                DB::connection($conectionBex)
                    ->table('s1e_vendedores')
                    ->join('tblmvendedor','s1e_vendedores.codvendedor','=','tblmvendedor.CODVENDEDOR')
                    ->update(['s1e_vendedores.estado' => 'C']);

                $NuevoVend = DB::connection($conectionBex)
                                ->table('s1e_vendedores')
                                ->where('estado', 'A')
                                ->get();
                
                $dataToInsert = [];
                if (count($NuevoVend) > 0) {
                    foreach ($NuevoVend as $vendedor) {

                        $dataToInsert[] = [
                            'codvendedor'           => $vendedor->codvendedor,
                            'nomvendedor'           => $vendedor->nomvendedor,
                            'codbodega'             => $vendedor->bodega,
                            'codgrupoemp'           => '0',
                            'codsupervisor'         => $vendedor->codsupervisor,
                            'codgrupodcto'          => '000',
                            'coddescuento'          => $vendedor->coddescuento,
                            'codportafolio'         => $vendedor->codportafolio,
                            'convisvendedor'        => '1',
                            'clavevendedor'         => $vendedor->codvendedor,
                            'porclogcumpvendedor'   => '95',
                            'califlogcumpvendedor'  => '5',
                            'porcantlogcumpvendedor'=> '90',
                            'hacedctopiefacaut'     => 'S',
                            'hacedctonc'            => 'N',
                            'descuentosescala'      => 'N',
                            'tercvendedor'          => $vendedor->codvendedor,
                            'tipodoc'               => $vendedor->tipodocpedido,
                            'cedula'                => NULL,
                            'co'                    => $vendedor->centro_ope,
                            'idcampaprobadores'     => '0'
                        ];
                    }
                    // Inserta los datos
                    DB::connection($conectionBex)->table('tblmvendedor')->insert($dataToInsert);
                    print "◘ Datos insertados en la tabla tblmvendedor" . PHP_EOL;  
                }

                $usuario = DB::connection($conectionBex)
                            ->table('tblsgrupo')                     
                            ->where('CODGRUPO', '200')
                            ->first();  
                
                if($usuario == null){
                    DB::connection($conectionBex)->table('tblsgrupo')->insert([
                        'CODGRUPO'    => '200',
                        'NOMGRUPO'    => 'Vendedores',
                        'ESTADOGRUPO' => 'A',
                        'FECGRA'      => $fechaActual,
                        'CODGRA'      => 'root',
                        'dias_informes' => '0'
                    ]);
                    print "◘ Datos insertados en la tabla tblsgrupo" . PHP_EOL;
                }              
                
                $vendedores = json_decode(json_encode(
                                DB::connection($conectionBex)
                                    ->table('tblmvendedor')
                                    ->where('CODVENDEDOR', '<>', '')
                                    ->get()
                                ),true);  
                
                foreach($vendedores as $vendedor){
                    $usuario = DB::connection($conectionBex)
                                    ->table('tblsusuario')
                                    ->where('codusuario', $vendedor['CODVENDEDOR'])
                                    ->where('bdusuario', null)
                                    ->first();  

                    $dataToInsert=[];
                    if($usuario == null){
                        $dataToInsert[] = [
                            'NITUSUARIO'    => $vendedor['CODVENDEDOR'],
                            'NOMUSUARIO'    => $vendedor['NOMVENDEDOR'],
                            'CODUSUARIO'    => $vendedor['CODVENDEDOR'],
                            'CLAVEUSUARIO'  => $vendedor['CODVENDEDOR'],
                            'CODEMPRESA'    => '3',
                            'FECGRA'        => $fechaActual,
                            'CODGRA'        => 'root',
                            'modificadoc'   => 'N',
                            'EMAIL'         => $vendedor['EMAIL'],
                            'caja'          => NULL,
                            'CO'            => $vendedor['CO'],
                            'SEXO'          => 'Masculino',
                            'bdusuario'     => NULL,
                            'SKINKO'        => '#438EB9',
                            'FECULTCAMBIACLAVE'=> $fechaActual,
                            'FECNAC'        => null
                        ];
                        DB::connection($conectionBex)->table('tblsusuario')->insert($dataToInsert);
                        print "◘ Datos insertados en la tabla tblsusuario" . PHP_EOL;

                        $dataToInsertGroup=[];
                        $dataToInsertGroup[] = [
                            'CODGRUPO'      => '200',
                            'CODUSUARIO'    => $vendedor['CODVENDEDOR'],
                            'ESTADOUSUGRU'  => 'A',
                            'FECGRA'        => $fechaActual,
                            'CODGRA'        => 'root'
                        ];
                        DB::connection($conectionBex)->table('tblsusugru')->insert($dataToInsertGroup);
                        print "◘ Datos insertados en la tabla tblsusugru" . PHP_EOL;
                    }
                }

                $dataToInsert=[];
                if(count($NuevoVend)>0){
                    foreach($NuevoVend as $vendedor){
                        $dataToInsert[] = [
                            'CODUSUARIO' => $vendedor->codvendedor,
                            'CODEMPRESA' => '001'
                        ];
                    }
                    DB::connection($conectionBex)->table('tbldusuarioempresa')->insert($dataToInsert);
                    print '◘ Datos insertados en la tabla tbldusuarioempresa' . PHP_EOL;
                }
            }else{
                print '◘ No hay datos para insertar en la tabla vendedores' . PHP_EOL;
            }
        } catch (\Exception $e) {
            Tbl_Log::create([
                'id_table'    => $id_importation,
                'type'        => $type,
                'descripcion' => 'Custom::Custom::bex_0006/InsertCustom[insertVendedoresCustom()] => '.$e->getMessage()
            ]);
            return 1;
        }
    }

    public function InsertAmovilCustom($conectionBex, $conectionSys, $datosAInsertar, $id_importation, $type,$modelInstance)
    {   
        try {
            $inset=(count($datosAInsertar));
            if($inset > 0){

                $modelInstance::whereRaw('nitcliente = succliente')
                                ->whereRaw('succliente <> 0')
                                ->update(['succliente' => '0']);
                print '◘ Sucursal actualizada en la tabla Amovil' . PHP_EOL;

                DB::connection($conectionBex)->table('tbldamovil')->truncate();
                print '◘ Tabla tbldamovil truncada' . PHP_EOL;

                $datosAInsert = json_decode(json_encode($datosAInsertar,true));
                // Insertar los datos en lotes
                if(sizeof($datosAInsertar) > 0){
                    foreach (array_chunk($datosAInsert,3000) as $dato) {
                        $Insert = [];
                        $count = count($dato);
                        for($i=0;$i<$count;$i++) {
                            $Insert[] = [
                                'nitcliente'  => $dato[$i]->nitcliente,
                                'succliente'  => $dato[$i]->succliente,
                                'ano'         => $dato[$i]->ano,
                                'mes'         => $dato[$i]->mes,
                                'valor'       => $dato[$i]->valor,
                                'codvendedor' => $dato[$i]->tercvendedor,
                                'codcliente'  => NULL
                            ];    
                        }  
                        DB::connection($conectionBex)->table('tbldamovil')->insert($Insert);
                    }
                    print '◘ Datos insertados en la tabla tbldamovil' . PHP_EOL;
                }
    
                DB::connection($conectionBex)
                    ->table('tbldamovil')
                    ->join('tblmcliente','tbldamovil.nitcliente','=','tblmcliente.nitcliente')
                    ->whereColumn('tbldamovil.succliente','tblmcliente.succliente')
                    ->update(['tbldamovil.codcliente' => DB::raw('tblmcliente.codcliente')]);
                print '◘ Datos Actualizados en la tabla tbldamovil' . PHP_EOL;
            }else{
                print '◘ No hay datos para Actualizar en la tabla tbldamovil' . PHP_EOL;
            } 
        } catch (\Exception $e) {
            Tbl_Log::create([
                'id_table'    => $id_importation,
                'type'        => $type,
                'descripcion' => 'Custom::Custom::bex_0006/InsertCustom[InsertAmovilCustom()] => '.$e->getMessage()
            ]);
            return 1;
        }
    }

    public function InsertDescuentos_ofiCustom($conectionBex, $conectionSys, $datosAInsertar, $id_importation, $type)
    {
        try {
            $insertCount = count($datosAInsertar);

            DB::connection($conectionBex)->table('s1e_promo_dsctos_linea')->truncate();
            print '◘ Tabla s1e_promo_dsctos_linea truncada' . PHP_EOL;

            DB::connection($conectionBex)->table('s1e_promo_dsctos_tope')->truncate();
            print '◘ Tabla s1e_promo_dsctos_tope truncada' . PHP_EOL;

            if ($insertCount > 0) {
                
                DB::connection($conectionBex)->table('s1e_descuentos_ofi')->truncate();
                print '◘ Tabla s1e_descuentos_ofi truncada' . PHP_EOL;

                // Insertar datos en la tabla s1e_descuentos_ofi
                $datosAInsertarArray = $datosAInsertar->toArray();
                $chunks = array_chunk($datosAInsertarArray, 3000); 
                foreach ($chunks as $chunk) {
                    $dataToInsert = [];
                    foreach ($chunk as $data) {
                        $dataToInsert[] = [
                            'LISTA'=> $data['LISTA'],
                            'CODPRODUCTO' => $data['CODPRODUCTO'],
                            'CANT1'       => $data['CANT1'],
                            'DESC1'       => $data['DESC1'],
                            'CANT2'       => $data['CANT2'],
                            'DESC2'       => $data['DESC2'],
                            'CANT3'       => $data['CANT3'],
                            'DESC3'       => $data['DESC3'],
                            'CANT4'       => $data['CANT4'],
                            'DESC4'       => $data['DESC4'],
                            'CANT5'       => $data['CANT5'],
                            'DESC5'       => $data['DESC5'],
                            'DESC_TOPE'   => $data['DESC_TOPE']
                        ];
                    }
                    DB::connection($conectionBex)->table('s1e_descuentos_ofi')->insert($dataToInsert);
                }
                print '◘ Datos insertados en la tabla s1e_descuentos_ofi' . PHP_EOL;

                DB::connection($conectionBex)
                ->table('s1e_descuentos_ofi')
                ->whereNotNull('CODPRODUCTO')
                ->update([
                    'CANT2' => DB::raw('CASE WHEN CANT2 > 0 THEN CANT2 - 1 ELSE CANT2 END'),
                    'CANT3' => DB::raw('CASE WHEN CANT3 > 0 THEN CANT3 - 1 ELSE CANT3 END'),
                    'CANT4' => DB::raw('CASE WHEN CANT4 > 0 THEN CANT4 - 1 ELSE CANT4 END'),
                    'CANT5' => DB::raw('CASE WHEN CANT5 > 0 THEN CANT5 - 1 ELSE CANT5 END'),
                ]);

                print '◘ Datos Actualizados en la tabla s1e_descuentos_ofi' . PHP_EOL;

                //s1e_promo_dsctos_linea REGLA 1 -------
                $cantie=DB::connection($conectionBex)
                ->table('s1e_promo_dsctos_linea')
                ->insertUsing(['idcia','rowid','descripcion','estado','estado1','fini','ffin','co','codproducto','porcdcto','tipoinv',
                'grupodctoitem','nitcliente','succliente','puntoenvio','tipocli','grupodctocli','condpago','listaprecios','planitem1',
                'criteriomayoritem1','planitem2','criteriomayoritem2','plancli1','criteriomayorcli1','plancli2','criteriomayorcli2',
                'codigoobsequi','motivoobsequio','umobsequio','cantobsequio','cantbaseobsequio','indmaxmin','cantmin','cantmax',
                'dctoval','escalacomb','contmaxmin','plancomb','prepack','valor_min','valor_max'
                ], function ($query) {
                    $query->selectRaw('"1" as idcia,"1" as rowid,"DESCUENTOS REGLA 1" as descripcion,"1" as estado,"1" as estado1,"2000-01-01" as fini,
                    "2099-12-31" as ffin,"" as co, CODPRODUCTO AS codproducto, DESC1 AS porcdcto,"" AS tipoinv,"" AS grupodctoitem,"" AS nitcliente,
                    "" AS succliente,"" AS puntoenvio,"" AS tipocli,"" AS grupodctocli,"" AS condpago,"" AS listaprecios,"" AS planitem1,"" AS criteriomayoritem1,
                    "" AS planitem2,"" AS criteriomayoritem2,"" AS plancli1,"" AS criteriomayorcli1,"" AS plancli2,"" AS criteriomayorcli2,
                    "" AS codigoobsequi,"" AS motivoobsequio,"" AS umobsequio,"    0.0000" AS cantobsequio,"    0.0000" AS cantbaseobsequio,"0" AS indmaxmin
                    ,CANT1 AS cantmin,CANT2 AS cantmax,"               0.000" AS dctoval,"0" AS escalacomb,"0" AS contmaxmin,"" AS plancomb,"" AS prepack,
                    "               0.000" AS valor_min,"               0.000" AS valor_max')
                    ->from('s1e_descuentos_ofi')
                    ->whereColumn('s1e_descuentos_ofi.CANT2','>=','s1e_descuentos_ofi.CANT1');
                });

                print '◘ Datos Insertados en la tabla s1e_promo_dsctos_linea REGLA 1' . PHP_EOL;

                //s1e_promo_dsctos_linea REGLA 2 -------
                DB::connection($conectionBex)
                ->table('s1e_promo_dsctos_linea')
                ->insertUsing(['idcia','rowid','descripcion','estado','estado1','fini','ffin','co','codproducto','porcdcto','tipoinv',
                'grupodctoitem','nitcliente','succliente','puntoenvio','tipocli','grupodctocli','condpago','listaprecios','planitem1',
                'criteriomayoritem1','planitem2','criteriomayoritem2','plancli1','criteriomayorcli1','plancli2','criteriomayorcli2',
                'codigoobsequi','motivoobsequio','umobsequio','cantobsequio','cantbaseobsequio','indmaxmin','cantmin','cantmax',
                'dctoval','escalacomb','contmaxmin','plancomb','prepack','valor_min','valor_max'
                ], function ($query) {
                    $query->selectRaw('"1" as idcia,"1" as rowid,"DESCUENTOS REGLA 2" as descripcion,"1" as estado,"1" as estado1,"2000-01-01" as fini,
                    "2099-12-31" as ffin,"" as co, CODPRODUCTO AS codproducto, DESC2 AS porcdcto,"" AS tipoinv,"" AS grupodctoitem,"" AS nitcliente,
                    "" AS succliente,"" AS puntoenvio,"" AS tipocli,"" AS grupodctocli,"" AS condpago,"" AS listaprecios,"" AS planitem1,"" AS criteriomayoritem1,
                    "" AS planitem2,"" AS criteriomayoritem2,"" AS plancli1,"" AS criteriomayorcli1,"" AS plancli2,"" AS criteriomayorcli2,
                    "" AS codigoobsequi,"" AS motivoobsequio,"" AS umobsequio,"    0.0000" AS cantobsequio,"    0.0000" AS cantbaseobsequio,"0" AS indmaxmin
                    ,CANT2 AS cantmin,CANT3 AS cantmax,"               0.000" AS dctoval,"0" AS escalacomb,"0" AS contmaxmin,"" AS plancomb,"" AS prepack,
                    "               0.000" AS valor_min,"               0.000" AS valor_max')
                    ->from('s1e_descuentos_ofi')
                    ->whereColumn('s1e_descuentos_ofi.CANT3','>=','s1e_descuentos_ofi.CANT2');
                });

                print '◘ Datos Insertados en la tabla s1e_promo_dsctos_linea REGLA 2' . PHP_EOL;

                //s1e_promo_dsctos_linea REGLA 3 -------
                DB::connection($conectionBex)
                ->table('s1e_promo_dsctos_linea')
                ->insertUsing(['idcia','rowid','descripcion','estado','estado1','fini','ffin','co','codproducto','porcdcto','tipoinv',
                'grupodctoitem','nitcliente','succliente','puntoenvio','tipocli','grupodctocli','condpago','listaprecios','planitem1',
                'criteriomayoritem1','planitem2','criteriomayoritem2','plancli1','criteriomayorcli1','plancli2','criteriomayorcli2',
                'codigoobsequi','motivoobsequio','umobsequio','cantobsequio','cantbaseobsequio','indmaxmin','cantmin','cantmax',
                'dctoval','escalacomb','contmaxmin','plancomb','prepack','valor_min','valor_max'
                ], function ($query) {
                    $query->selectRaw('"1" as idcia,"1" as rowid,"DESCUENTOS REGLA 3" as descripcion,"1" as estado,"1" as estado1,"2000-01-01" as fini,
                    "2099-12-31" as ffin,"" as co, CODPRODUCTO AS codproducto, DESC3 AS porcdcto,"" AS tipoinv,"" AS grupodctoitem,"" AS nitcliente,
                    "" AS succliente,"" AS puntoenvio,"" AS tipocli,"" AS grupodctocli,"" AS condpago,"" AS listaprecios,"" AS planitem1,"" AS criteriomayoritem1,
                    "" AS planitem2,"" AS criteriomayoritem2,"" AS plancli1,"" AS criteriomayorcli1,"" AS plancli2,"" AS criteriomayorcli2,
                    "" AS codigoobsequi,"" AS motivoobsequio,"" AS umobsequio,"    0.0000" AS cantobsequio,"    0.0000" AS cantbaseobsequio,"0" AS indmaxmin
                    ,CANT3 AS cantmin,CANT4 AS cantmax,"               0.000" AS dctoval,"0" AS escalacomb,"0" AS contmaxmin,"" AS plancomb,"" AS prepack,
                    "               0.000" AS valor_min,"               0.000" AS valor_max')
                    ->from('s1e_descuentos_ofi')
                    ->whereColumn('s1e_descuentos_ofi.CANT4','>=','s1e_descuentos_ofi.CANT3');
                });

                print '◘ Datos Insertados en la tabla s1e_promo_dsctos_linea REGLA 3' . PHP_EOL;

                //s1e_promo_dsctos_linea REGLA 4 -------
                DB::connection($conectionBex)
                ->table('s1e_promo_dsctos_linea')
                ->insertUsing(['idcia','rowid','descripcion','estado','estado1','fini','ffin','co','codproducto','porcdcto','tipoinv',
                'grupodctoitem','nitcliente','succliente','puntoenvio','tipocli','grupodctocli','condpago','listaprecios','planitem1',
                'criteriomayoritem1','planitem2','criteriomayoritem2','plancli1','criteriomayorcli1','plancli2','criteriomayorcli2',
                'codigoobsequi','motivoobsequio','umobsequio','cantobsequio','cantbaseobsequio','indmaxmin','cantmin','cantmax',
                'dctoval','escalacomb','contmaxmin','plancomb','prepack','valor_min','valor_max'
                ], function ($query) {
                    $query->selectRaw('"1" as idcia,"1" as rowid,"DESCUENTOS REGLA 4" as descripcion,"1" as estado,"1" as estado1,"2000-01-01" as fini,
                    "2099-12-31" as ffin,"" as co, CODPRODUCTO AS codproducto, DESC4 AS porcdcto,"" AS tipoinv,"" AS grupodctoitem,"" AS nitcliente,
                    "" AS succliente,"" AS puntoenvio,"" AS tipocli,"" AS grupodctocli,"" AS condpago,"" AS listaprecios,"" AS planitem1,"" AS criteriomayoritem1,
                    "" AS planitem2,"" AS criteriomayoritem2,"" AS plancli1,"" AS criteriomayorcli1,"" AS plancli2,"" AS criteriomayorcli2,
                    "" AS codigoobsequi,"" AS motivoobsequio,"" AS umobsequio,"    0.0000" AS cantobsequio,"    0.0000" AS cantbaseobsequio,"0" AS indmaxmin
                    ,CANT4 AS cantmin,CANT5 AS cantmax,"               0.000" AS dctoval,"0" AS escalacomb,"0" AS contmaxmin,"" AS plancomb,"" AS prepack,
                    "               0.000" AS valor_min,"               0.000" AS valor_max')
                    ->from('s1e_descuentos_ofi')
                    ->whereColumn('s1e_descuentos_ofi.CANT5','>=','s1e_descuentos_ofi.CANT4');
                });

                print '◘ Datos Insertados en la tabla s1e_promo_dsctos_linea REGLA 4' . PHP_EOL;

                //s1e_promo_dsctos_linea REGLA 5 -------
                DB::connection($conectionBex)
                ->table('s1e_promo_dsctos_linea')
                ->insertUsing(['idcia','rowid','descripcion','estado','estado1','fini','ffin','co','codproducto','porcdcto','tipoinv',
                'grupodctoitem','nitcliente','succliente','puntoenvio','tipocli','grupodctocli','condpago','listaprecios','planitem1',
                'criteriomayoritem1','planitem2','criteriomayoritem2','plancli1','criteriomayorcli1','plancli2','criteriomayorcli2',
                'codigoobsequi','motivoobsequio','umobsequio','cantobsequio','cantbaseobsequio','indmaxmin','cantmin','cantmax',
                'dctoval','escalacomb','contmaxmin','plancomb','prepack','valor_min','valor_max'
                ], function ($query) {
                    $query->selectRaw('"1" as idcia,"1" as rowid,"DESCUENTOS REGLA 5" as descripcion,"1" as estado,"1" as estado1,"2000-01-01" as fini,
                    "2099-12-31" as ffin,"" as co, CODPRODUCTO AS codproducto, DESC5 AS porcdcto,"" AS tipoinv,"" AS grupodctoitem,"" AS nitcliente,
                    "" AS succliente,"" AS puntoenvio,"" AS tipocli,"" AS grupodctocli,"" AS condpago,"" AS listaprecios,"" AS planitem1,"" AS criteriomayoritem1,
                    "" AS planitem2,"" AS criteriomayoritem2,"" AS plancli1,"" AS criteriomayorcli1,"" AS plancli2,"" AS criteriomayorcli2,
                    "" AS codigoobsequi,"" AS motivoobsequio,"" AS umobsequio,"    0.0000" AS cantobsequio,"    0.0000" AS cantbaseobsequio,"0" AS indmaxmin
                    ,CANT5 AS cantmin,"99999999999999" AS cantmax,"               0.000" AS dctoval,"0" AS escalacomb,"0" AS contmaxmin,"" AS plancomb,"" AS prepack,
                    "               0.000" AS valor_min,"               0.000" AS valor_max')
                    ->from('s1e_descuentos_ofi')
                    ->whereColumn('s1e_descuentos_ofi.CANT5','>','s1e_descuentos_ofi.CANT4');
                });

                print '◘ Datos Insertados en la tabla s1e_promo_dsctos_linea REGLA 5' . PHP_EOL;

                //s1e_promo_dsctos_tope
                DB::connection($conectionBex)
                ->table('s1e_promo_dsctos_tope')
                ->insertUsing(['idcia','rowid','estado','estado1','fini','ffin','co','codproducto','porcdcto','tipoinv','grupodctoitem',
                'nitcliente','succliente','puntoenvio','tipocli','grupodctocli','condpago','listaprecios','planitem1','criteriomayoritem1',
                'planitem2','criteriomayoritem2','plancli1','criteriomayorcli1','plancli2','criteriomayorcli2','codigoobsequi','motivoobsequio',
                'umobsequio','cantobsequio','cantbaseobsequio','x'
                ], function ($query) {
                    $query->selectRaw('"1" AS idcia,"1" AS rowid,"1" AS estado,"0" AS estado1,"2000-01-01" AS fini,"2099-12-31" AS ffin,"   " AS co,
                    CODPRODUCTO AS codproducto,CONCAT(DESC_TOPE,".00") AS porcdcto,"          " AS tipoinv,"    " AS grupodctoitem,"               " AS nitcliente,
                    "    " AS succliente,"0" AS puntoenvio,"    " AS tipocli,"" AS grupodctocli,"   " AS condpago,"   " AS listaprecios,
                    "00" AS planitem1,"00" AS criteriomayoritem1,"   " AS planitem2,"    " AS criteriomayoritem2,"00" AS plancli1,LISTA AS criteriomayorcli1,
                    "   " AS plancli2,"    " AS criteriomayorcli2,"                    " AS codigoobsequi,"  " AS motivoobsequio,"    " AS umobsequio,
                    "      0.00" AS cantobsequio,"      0.00" AS cantbaseobsequio,NULL AS x')
                    ->from('s1e_descuentos_ofi')
                    ->where('s1e_descuentos_ofi.CANT5','>','0');
                });

                print '◘ Datos Insertados en la tabla s1e_promo_dsctos_tope' . PHP_EOL;

            }else{
                print '◘ Sin datos para insertar en descuentos_ofi.' . PHP_EOL;
            }
        } catch (\Exception $e) {
            Tbl_Log::create([
                'id_table'    => $id_importation,
                'type'        => $type,
                'descripcion' => 'Custom::Custom::bex_0006/InsertCustom[insertDescuentos_ofiCustom()] => '.$e->getMessage()
            ]);
            return 1;
        }
    }
}