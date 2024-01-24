<?php

namespace App\Custom\bex_0006;

use App\Models\Tbl_Log;
use App\Traits\ConnectionTrait;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class InsertCustomBexTramite
{
    use ConnectionTrait;

    public function insertCarteraCustom($conectionBex, $conectionSys, $datosAInsertar, $id_importation, $type,$modelInstance)
    {
        try {
            // Tunca tabla pi_cartera
            DB::connection($conectionBex)->table('pi_cartera')->truncate();
            print '◘ Tabla pi_cartera truncada' . PHP_EOL;
            $cartera = $modelInstance::CarteraBexTramite()->get();

            $datosAInsertarJson = json_decode(json_encode($cartera,true));
            if(sizeof($datosAInsertarJson) != 0){
                foreach (array_chunk($datosAInsertarJson, 2000) as $dato) {
                    $Insert = [];
                    $count = count($dato);
                    for($i=0;$i<$count;$i++) {
                        $Insert[] = [
                            'codempresa'    =>$dato[$i]->compania,
                            'codclientealt' => $dato[$i]->codcliente,
                            'nitcliente'    => $dato[$i]->nitcliente,
                            'succliente'    => $dato[$i]->succliente,
                            'numobligacion' => $dato[$i]->documento,
                            'tipocredito'   => $dato[$i]->codtipodoc,
                            'fecven'        => $dato[$i]->fechavenci,
                            'diasmora'      => $dato[$i]->diasmora,
                            'valtotcredito' => $dato[$i]->vrpostf,
                            'valcuota'      => $dato[$i]->valor,
                            'valintereses'  => '',
                            'valacobrar'    => $dato[$i]->valor,
                            'cuotasmora'    => '',
                            'regional'      => $dato[$i]->idregion,
                            'codvendedor'   => $dato[$i]->tercvendedor,
                            'nomvendedor'   => $dato[$i]->nomvendedor,
                            'cupo'          => $dato[$i]->cupo,
                            'estadofactura' => '',
                            'tipocliente'   => '',
                            'feccrefac'   => $dato[$i]->fecmov,
                            'tipocartera'   => '',
                            'codcliente'   => null,
                            'codobligacion'   => NULL
                        ];    
                    }
                    DB::connection($conectionBex)->table('pi_cartera')->insert($Insert);
                }
                print '◘ Datos insertados la tabla pi_cartera' . PHP_EOL;
            }
        } catch (\Exception $e) {
            Tbl_Log::create([
                'id_table'    => $id_importation,
                'type'        => $type,
                'descripcion' => 'Custom::bex_0006/InsertCustomBexTramite[insertCarteraCustom()] => '.$e->getMessage()
            ]);
            return 1;
        }
    }

    public function insertClientesCustom($conectionBex, $conectionSys, $datosAInsertar, $id_importation, $type, $modelInstance, $tableName)
    {   
        try {
            
            $cliente = $modelInstance::ClientBexTramite()->get();

            DB::connection($conectionBex)->table('pi_clientes')->truncate();
            print '◘ Tabla pi_clientes truncada' . PHP_EOL;

            $datosAInsertarJson = json_decode(json_encode($cliente,true));
            
            if(sizeof($datosAInsertarJson) != 0){
                foreach (array_chunk($datosAInsertarJson, 2000) as $dato) {
                    $Insert = [];
                    $count = count($dato);
                    for($i=0;$i<$count;$i++) {
                        $Insert[] = [
                            'codclientealt'=> $dato[$i]->codcliente,
                            'nitcliente'   => $dato[$i]->codigo,
                            'succliente'   => $dato[$i]->sucursal,
                            'razcliente'   => str_replace('Ñ','N',$dato[$i]->razsoc),
                            'nomcliente'   => str_replace('Ñ','N',$dato[$i]->representante),
                            'telcliente'   => str_replace('Ñ','N',$dato[$i]->telefono),
                            'telcliente2'  => '',
                            'numcelular'   => $dato[$i]->celular,
                            'numcelular2'  => '',
                            'dircliente'   => $dato[$i]->direccion,
                            'nombarrio'    => '',
                            'codmpio'      => $dato[$i]->codmpio,
                            'nommpio'      => $dato[$i]->municipios,
                            'nomdpto'      => $dato[$i]->departamento,
                            'email'        => $dato[$i]->email,
                            'codvendedor'  => $dato[$i]->tercvendedor,
                            'actcliente'   => $dato[$i]->actcliente,
                            'idregion'     => $dato[$i]->idregion,
                            'nomregion'    => $dato[$i]->nomregion,
                            'idcanal'      => $dato[$i]->idcanal,
                            'nomcanal'     => $dato[$i]->nomcanal,
                            'codcliente'   => NULL
                        ];    
                    }
                    DB::connection($conectionBex)->table('pi_clientes')->insert($Insert);
                }
                print '◘ Datos insertados la tabla pi_clientes' . PHP_EOL;
            }
                         
            print '◘ Datos actualizados en la tabla pi_clientes' . PHP_EOL;

        } catch (\Exception $e) {
            Tbl_Log::create([
                'id_table'    => $id_importation,
                'type'        => $type,
                'descripcion' => 'Custom::Custom::bex_0006/InsertCustomBexTramite[insertClientesCustom()] => '.$e->getMessage()
            ]);
            return 1;
        }
    }

    public function insertPagosBexTramitesCustom($conectionBex, $conectionSys, $datosAInsertar, $id_importation, $type, $modelInstance, $tableName)
    {   
        try {
            
            DB::connection($conectionBex)->table('pi_pagos')->truncate();
            print '◘ Tabla pi_pagos truncada' . PHP_EOL;

            $datosAInsertarJson = json_decode(json_encode($datosAInsertar,true));
            
            if(sizeof($datosAInsertarJson) != 0){
                foreach (array_chunk($datosAInsertarJson, 2000) as $dato) {
                    $Insert = [];
                    $count = count($dato);
                    for($i=0;$i<$count;$i++) {
                        $Insert[] = [
                            'codclientealt' => $dato[$i]->codcliente,
                            'succliente'    => $dato[$i]->succliente,
                            'tipocredito'   => $dato[$i]->tipocredito,
                            'numobligacion' => $dato[$i]->numobligacion,
                            'fecpago'       => $dato[$i]->fecpago,
                            'valpago'       => $dato[$i]->valpago,
                            'codcliente'    => NULL,
                            'codobligacion' => $dato[$i]->codobligacion
                        ];    
                    }
                    DB::connection($conectionBex)->table('pi_pagos')->insert($Insert);
                }
                print '◘ Datos insertados la tabla pi_pagos' . PHP_EOL;
            }
                         
            print '◘ Datos actualizados en la tabla pi_pagos' . PHP_EOL;

        } catch (\Exception $e) {
            Tbl_Log::create([
                'id_table'    => $id_importation,
                'type'        => $type,
                'descripcion' => 'Custom::Custom::bex_0006/InsertCustomBexTramite[insertPagosCustom()] => '.$e->getMessage()
            ]);
            return 1;
        }
    }

    public function insertObligacionesBexTramitesCustom($conectionBex, $conectionSys, $datosAInsertar, $id_importation, $type, $modelInstance, $tableName)
    {   
        try {
            $cliente = $modelInstance::ObligacionesBexTramite()->get();

            DB::connection($conectionBex)->table('pi_obligaciones')->truncate();
            print '◘ Tabla pi_obligaciones truncada' . PHP_EOL;

            $datosAInsertarJson = json_decode(json_encode($cliente,true));
            
            if(sizeof($datosAInsertarJson) != 0){
                foreach (array_chunk($datosAInsertarJson, 2000) as $dato) {
                    $Insert = [];
                    $count = count($dato);
                    for($i=0;$i<$count;$i++) {
                        $Insert[] = [
                            'codempresa'    => $dato[$i]->codempresa,
                            'codclientealt' => $dato[$i]->codclientealt,
                            'nitcliente'    => $dato[$i]->nitcliente,
                            'succliente'    => $dato[$i]->sucursal,
                            'razcliente'    => $dato[$i]->razsoc,
                            'nomcliente'    => $dato[$i]->nomcliente,
                            'telcliente'    => $dato[$i]->telcliente1,
                            'telcliente2'   => $dato[$i]->telcliente2,
                            'numcelular'    => $dato[$i]->celular,
                            'numcelular2'   => '',
                            'dircliente'    => $dato[$i]->direccion,
                            'nombarrio'     => $dato[$i]->barrio,
                            'codmpio'       => $dato[$i]->codmpio,
                            'nommpio'       => $dato[$i]->nommpio,
                            'nomdpto'       => $dato[$i]->nomdpto,
                            'email'         => $dato[$i]->email,
                            'numobligacion' => $dato[$i]->numobligacion,
                            'tipocredito'   => $dato[$i]->tipocredito,
                            'fecfactura'    => $dato[$i]->fecfactura,
                            'fecven'        => $dato[$i]->fecven,
                            'diasmora'      => $dato[$i]->diasmora,
                            'valtotcredito' => $dato[$i]->valtotcredito,
                            'valcuota'      => '',
                            'valinteres'    => '',
                            'valacobrar'    => $dato[$i]->valacobrar,
                            'valenmora'     => $dato[$i]->valenmora,
                            'regional'      => $dato[$i]->regional,
                            'codvendedor'   => $dato[$i]->codvendedor,
                            'nomvendedor'   => $dato[$i]->nomvendedor,
                            'cupo'          => $dato[$i]->cupo
                        ];    
                    }
                    DB::connection($conectionBex)->table('pi_obligaciones')->insert($Insert);
                }
                print '◘ Datos insertados la tabla pi_obligaciones' . PHP_EOL;
            }
                         
            print '◘ Datos actualizados en la tabla pi_obligaciones' . PHP_EOL;

        } catch (\Exception $e) {
            Tbl_Log::create([
                'id_table'    => $id_importation,
                'type'        => $type,
                'descripcion' => 'Custom::Custom::bex_0006/InsertCustomBexTramite[insertObligacionesCustom()] => '.$e->getMessage()
            ]);
            return 1;
        }
    }

    public function insertDetalleFacturaBexTramitesCustom($conectionBex, $conectionSys, $datosAInsertar, $id_importation, $type, $modelInstance, $tableName)
    {   
        try {
            
            DB::connection($conectionBex)->table('pi_detallefactura')->truncate();
            print '◘ Tabla pi_detallefactura truncada' . PHP_EOL;

            $datosAInsertarJson = json_decode(json_encode($datosAInsertar,true));
            
            if(sizeof($datosAInsertarJson) != 0){
                foreach (array_chunk($datosAInsertarJson, 2000) as $dato) {
                    $Insert = [];
                    $count = count($dato);
                    for($i=0;$i<$count;$i++) {
                        $Insert[] = [
                            'codcliente'    => '',
                            'nitcliente'    => $dato[$i]->nitcliente,
                            'codobligacion' => '',
                            'tipodoc'       => $dato[$i]->tipodoc,
                            'tipoCredito'   => $dato[$i]->tipoCredito,
                            'codproducto'   => $dato[$i]->codproducto,
                            'nomproducto'   =>$dato[$i]->nomproducto,
                            'cantidad'      => $dato[$i]->cantidad,
                            'valorUnitario' => $dato[$i]->valorUnitario,
                            'valorTotal'    => $dato[$i]->valorTotal,
                            'numobligacion' => $dato[$i]->numeroFactura
                        ];    
                    }
                    DB::connection($conectionBex)->table('pi_detallefactura')->insert($Insert);
                }
                print '◘ Datos insertados la tabla pi_detallefactura' . PHP_EOL;
            }
                         
            print '◘ Datos actualizados en la tabla pi_detallefactura' . PHP_EOL;

        } catch (\Exception $e) {
            Tbl_Log::create([
                'id_table'    => $id_importation,
                'type'        => $type,
                'descripcion' => 'Custom::Custom::bex_0006/InsertCustomBexTramite[insertDetalleFacturaCustom()] => '.$e->getMessage()
            ]);
            return 1;
        }
    }
}