<?php

namespace App\Custom\bex_0006;

use App\Models\Tbl_Log;
use App\Models\Ws_Config;
use App\Models\Connection;
use App\Models\Connection_Bexsoluciones;

use App\Models\LogErrorImportacionModel;
use App\Traits\ApiTrait;
use App\Traits\ConnectionTrait;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class OrderCoreCustom
{
    use ApiTrait, ConnectionTrait;

    public function uploadOrder($orders, $cia, $closing, $connection_id)
    {
        try{
            // consultar platafor_pi287 y cambiamos el estado a [9 => alistando]
            $platafor_pi287 = Connection_Bexsoluciones::where('name', $cia->bdlicencias)->first();
            $config = $this->connectionDB($platafor_pi287->id, 'externa', $platafor_pi287->area);
            if($config != 0){
                DB::connection('mysql')->table('tbl_log')->insert([
                    'descripcion' => 'Trait::OrderCoreCustom[uploadOrder()] => Conexion Externa: Linea '.__LINE__.'; '.$config,
                    'created_at'  => now(),
                    'updated_at'  => now()
                ]);
                return 1;
            }

            foreach($orders as $order){
                DB::connection($platafor_pi287->name)
                    ->table('tbldmovenc')
                    ->where('NUMMOV', $order->nummov)
                    ->where('CODTIPODOC', '4')
                    ->where('codvendedor', $order->codvendedor)
                    ->update(['estadoenviows' => '9', 'fechamovws' => now()]);
            }

            // Generamos plano y lo guardamos
            $flatFile = $this->createFlatFile($orders, $closing, $platafor_pi287->name, $connection_id);

            if($flatFile != 0){
                DB::connection('mysql')->table('tbl_log')->insert([
                    'descripcion' => 'Commands::OrderCoreCustom[uploadOrder()] => Error al crear encabezado archivo plano  con el cierre N° '.$closing,
                    'created_at'  => now(),
                    'updated_at'  => now()
                ]);
                return 1;
            }

        } catch (\Exception $e) {
            Tbl_Log::create([
                'descripcion' => 'Custom::bex_0006/OrderCoreCustom[uploadOrder()] => '.$e->getMessage()
            ]);
            return 1;
        }
    }

    private function createFlatFile($orders, $closing, $nameDB, $connection_id)
    {
        try{
            $grupos = [];

            // Iterar sobre los elementos de cada arreglo
            foreach ($orders as $arreglo) {
                $encontrado = false;

                // Verificar si el elemento ya existe en algún grupo
                foreach ($grupos as $grupo) {
                    if ($this->sonIgualesElemento($arreglo, $grupo[0])) {
                        $encontrado = true;
                        $grupo[] = $arreglo;
                        $grupo[0]->countGroup = count($grupo);
                        break;
                    }
                }

                // Si no se encuentra en ningún grupo, crear uno nuevo
                if (!$encontrado) {
                    $grupoNuevo = [$arreglo];
                    $grupoNuevo[0]->countGroup = 1;
                    $grupos[] = $grupoNuevo;
                }
            }

            $valorTotal = 0;
            // Calcular el "valorTotal" para cada elemento
            foreach($orders as $precioTotal){
                $valorTotal += $precioTotal->cantidadmov * ($precioTotal->preciomov * (1+($precioTotal->ivamov/100)));
            }
            foreach($orders as $encabezado){

                $structureHeader[] = [
                    "bodega" => intval($encabezado->codbodega),
                    "sw" => 1,
                    "nit" => intval($encabezado->nitcliente),
                    "vendedor" => intval($encabezado->codvendedor),
                    "fecha" =>  substr($encabezado->fecmov,8,2).'/'.substr($encabezado->fecmov,5,2).'/'.substr($encabezado->fecmov,0,4).' '.substr($encabezado->fecmov,11,8),
                    "condicion" => $encabezado->codfpagovta,
                    "diasValidez" => 120,
                    "descuentoPie" => intval($encabezado->dctopiefacaut),
                    "valorTotal" => floatval(number_format($valorTotal, 2, '.', '')),
                    "fechaHora" => date('d/m/Y H:i:s'),
                    "anulado" => 0,
                    "notas" => $encabezado->mensajemov,
                    "usuario" => $encabezado->codvendedor,
                    "pc" => 'Bex',
                    "duracion" => 120,
                    "concepto" => null,
                    "moneda" => null,
                    "despacho" => null,
                    "fechaHoraEntrega" =>  date('d/m/Y H:i:s', strtotime('+1 day')),
                    "nitDestino" => null,
                    "codigoDireccion" => 0,
                    "abono" => null,
                    "telefono" => null,
                    "emergencia" => null,
                    "documento" => "",
                    "autorizacion" => "S",
                    "autorizTexto" => "Bex Movil",
                    "nit2" => null,
                    "mrp" => null,
                    "notas5" => null,
                    "listaPrecios" => $encabezado->codprecio,
                    "fletes" => null,
                    "ivaFletes" => null,
                    "ordenEnv" => null,
                    "notasAut" => null,
                    "descuentoDesIva" => null,
                    "codDireccionDest" => null,
                    "fechaIngreso" => null,
                    "notaVta" => null,
                    "usuarioAut" => null,
                    "notaCompro" => null,
                    "prg" => null,
                    "multiplesOC" => null,
                    "tipoOrden" => null,
                    "usuarioAutorizo" => null,
                    "idTablaAmortizacion" => null,
                    "pedidoMovil" => null,
                    "moduloCreacion" => null,
                    "notaProd" => $encabezado->mensajeadic,
                    "nota1" => null,
                    "nota2" => $encabezado->ordendecompra,
                    "problema" => null,
                    "perf" => null,
                    "idTallEncabezaOrden" => null,
                    "idMovil" => $encabezado->nummov,
                    "codigoClasificacion" => null,
                    "contacto" => null,
                    "tipoCompra" => null,
                    "modeloCompra" => null,
                    "fechaEntrega" => null,
                    "fechaEntregaMax" => null,
                    "idUsuarioFinal" => null,
                    "centroCostos" => null,
                    "utilizacion" => null,
                    "destinatario" => null,
                    "idTiempoGarantia" => null,
                    "numItems" => null,
                    "valorTotalInicial" => null,
                    "corOrdenCompra" => null,
                    "debitoNiif" => null,
                    "creditoNiif" => null,
                    "lugarEntrega" => null,
                    "certificadoCalidad" => null
                ];

                $saveFlatFile = $this->saveFlatFile($structureHeader, $closing, $encabezado->nummov);
                if($saveFlatFile == 0){
                    // Cambiamos el estado a 1 que es preparado
                    DB::connection($nameDB)
                        ->table('tbldmovenc')
                        ->where('NUMMOV', $encabezado->nummov)
                        ->where('CODTIPODOC', '4')
                        ->where('codvendedor', $encabezado->codvendedor)
                        ->update(['estadoenviows' => '1', 'fechamovws' => now()]);

                    // Enviamos el encabezado por API
                    $sendHeaderApi = $this->sendHeaderApi($connection_id, $structureHeader, $orders, $encabezado->nummov, $closing, $nameDB, $encabezado);

                    if($sendHeaderApi == 0){
                        // Si todo se realizo de manera exitosa cambiamos el estado a 2
                        DB::connection($nameDB)
                        ->table('tbldmovenc')
                        ->where('NUMMOV',  $encabezado->nummov)
                        ->where('CODTIPODOC', '4')
                        ->where('codvendedor', $encabezado->codvendedor)
                        ->update(['estadoenviows' => '2', 'fechamovws' => now()]);

                        return 0;
                    }elseif($sendHeaderApi == 1){
                        DB::connection($nameDB)
                        ->table('tbldmovenc')
                        ->where('NUMMOV',  $encabezado->nummov)
                        ->where('CODTIPODOC', '4')
                        ->where('codvendedor', $encabezado->codvendedor)
                        ->update(['estadoenviows' => '3', 'fechamovws' => now(), 'msmovws' => 'Error API: Acceso prohibido (403)']);

                        return 1;
                    }else {
                        DB::connection($nameDB)
                        ->table('tbldmovenc')
                        ->where('NUMMOV',  $encabezado->nummov)
                        ->where('CODTIPODOC', '4')
                        ->where('codvendedor', $encabezado->codvendedor)
                        ->update(['estadoenviows' => '3', 'fechamovws' => now(), 'msmovws' => 'Error API: page not found (404)']);

                        return 1;
                    }
                }
            }

        } catch (\Exception $e) {
            Tbl_Log::create([
                'descripcion' => 'Custom::bex_0006/OrderCoreCustom[createFlatFile()] => '.$e->getMessage()
            ]);
            return 1;
        }
    }

    private function sonIgualesElemento($elemento1, $elemento2) {
        return (
            $elemento1->nitcliente == $elemento2->nitcliente &&
            $elemento1->codbodega == $elemento2->codbodega &&
            $elemento1->fecmov == $elemento2->fecmov &&
            $elemento1->codfpagovta == $elemento2->codfpagovta &&
            $elemento1->codvendedor == $elemento2->codvendedor &&
            $elemento1->dctopiefacaut == $elemento2->dctopiefacaut &&
            $elemento1->fechorfinvisita == $elemento2->fechorfinvisita &&
            $elemento1->mensajemov == $elemento2->mensajemov &&
            $elemento1->codprecio == $elemento2->codprecio &&
            $elemento1->fechorentregacli == $elemento2->fechorentregacli
        );
    }

    private function saveFlatFile($json, $closing, $nummov){
        try{
            // Convertir el array a formato JSON
            $contenidoJSON = json_encode($json, JSON_PRETTY_PRINT);

            // Ruta donde se guardará el archivo
            $rutaArchivo = storage_path('app/tu_archivo.txt');

            // Guardar el contenido en el archivo
            $namefile= $closing.'_'.$nummov.'.json';
            Storage::disk('public')->put('export/bex_0006/bexmovil/pedidos_txt/' . $namefile, $contenidoJSON);
            return 0;
        } catch (\Exception $e) {
            Tbl_Log::create([
                'descripcion' => 'Custom::bex_0006/OrderCoreCustom[saveFlatFile()] => '.$e->getMessage()
            ]);
            return 1;
        }
    }

    private function sendHeaderApi($connection_id, $structureHeader, $orders, $nummov, $closing, $nameDB,$encabezado){

        $db = Connection::where('id', $connection_id)->value('name');

        // Consultar en BEXconection
        $configDB = $this->connectionDB($db, 'local');
        if($configDB != 0){
            DB::connection('mysql')->table('tbl_log')->insert([
                'descripcion' => 'Commands::OrderCoreCustom[sendHeaderApi()] => Conexion Local: Linea '.__LINE__.'; '.$configDB,
                'created_at'  => now(),
                'updated_at'  => now()
            ]);
            return 1;
        }

        try{
            $config = Ws_Config::where('estado', 1)->first();
            $token = $this->loginToApi($config);

            $urlEncabezado = $config->urlEnvio;
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json', // Encabezado
            ])->post($urlEncabezado, $structureHeader);

            if ($response->status() == 403) {
                return 1;
            }elseif($response->status() == 404){
                return 2;
            }else{
                $numero = $response['respuesta'][0]['numero'];
                DB::connection($nameDB)
                ->table('tbldmovenc')
                ->where('NUMMOV', $encabezado->nummov)
                ->where('CODTIPODOC', '4')
                ->where('codvendedor', $encabezado->codvendedor)
                ->update(['msmovws' => 'Numero del pedido en el ERP: '.$numero]);
            }

            $details = $this->sendDetailsApi($numero, $orders, $nummov, $closing, $config, $token);
            if($details == 0){
                return 0;
            }else{
                return 1;
            }
            //dd('termino');
        }catch(RequestException $e){
            if ($e->response->status() == 403) {
                DB::connection($nameDB)
                ->table('tbldmovenc')
                ->where('NUMMOV', $encabezado->nummov)
                ->where('CODTIPODOC', '4')
                ->where('codvendedor', $encabezado->codvendedor)
                ->update(['estadoenviows' => '3', 'fechamovws' => now()->format('Y-m-d H:i:s'), 'msmovws' => 'Error API: Acceso prohibido (403)']);
            }elseif($e->response->status() == 404) {
                DB::connection($nameDB)
                ->table('tbldmovenc')
                ->where('NUMMOV', $encabezado->nummov)
                ->where('CODTIPODOC', '4')
                ->where('codvendedor', $encabezado->codvendedor)
                ->update(['estadoenviows' => '3', 'fechamovws' => now()->format('Y-m-d H:i:s'), 'msmovws' => 'Error API: not found (404)']);
            }else{
                DB::connection($nameDB)
                ->table('tbldmovenc')
                ->where('NUMMOV', $encabezado->nummov)
                ->where('CODTIPODOC', '4')
                ->where('codvendedor', $encabezado->codvendedor)
                ->update(['estadoenviows' => '3', 'fechamovws' => now()->format('Y-m-d H:i:s'), 'msmovws' => "Error: " . $e->getMessage()]);
            }
        }

    }

    private function sendDetailsApi($numero, $orders, $nummov, $closing, $config, $token){
        try{
            $i = 1;

            foreach($orders as $detail){
                if($detail->nummov == $nummov){
                    $jsonDetail[] =
                        [
                            "sw" => 1,
                            "numero" => $numero,
                            "codigo" =>  $detail->codproducto,
                            "seq" => $i,
                            "bodega" => intval($detail->codbodega),
                            "cantidad" => floatval(number_format($detail->cantidadmov, 2, '.', '')),
                            "cantidadDespachada" => 0,
                            "valorUnitario" => floatval(number_format($detail->preciomov, 2, '.', '')),
                            "porcentajeIva" => floatval(number_format(($detail->ivamov), 0, '.', '')),
                            "porcentajeDescuento" => floatval(number_format($detail->dctopiefacaut, 2, '.', '')),
                            "und" => $detail->codunidademp,
                            "cantidadUnd" => floatval(number_format(1, 2, '.', '')),
                            "adicional" => "Informaci&oacute;n adicional",
                            "despachoVirtual" => floatval(number_format($detail->cantidadmov, 2, '.', '')),
                            "porcDcto2" => floatval(number_format(0.0, 2, '.', '')),
                            "porcDcto3" => floatval(number_format(0.0, 2, '.', '')),
                            "cantidadOp" => floatval(number_format(0.0, 2, '.', '')),
                            "Consignacion" => 0,
                            "fecCompromiso" =>  date('d/m/Y H:i:s', strtotime('+1 day')),
                            "producido" => 1,
                            "calidad" => 2,
                            "valorUnitarioConfirmado" => floatval(number_format($detail->preciomov, 2, '.', '')),
                            "notaProd" => "",
                            "cantidadConfirmada" => floatval(number_format($detail->cantidadmov, 2, '.', '')),
                            "colorInterno" => "",
                            "colorExterno" => "",
                            "modelo" => "",
                            "ano" => intval(date('Y', strtotime($detail->fecmov))),
                            "idAdmonPedCot" => "",
                            "descripcionItemAdicional" => $detail->nomproducto

                    ];
                    $i++;
                }

            }

                    $namefile = $closing.'_'.$nummov.'.json';
                    $jsonHeader = json_decode(Storage::disk('public')->get('export/bex_0006/bexmovil/pedidos_txt/' . $namefile), true);

                    // Unimos los resultados
                    $contenidoFinal = array_merge($jsonHeader, $jsonDetail);

                    // Convertir el contenido final a formato JSON
                    $contenidoFinalJson = json_encode($contenidoFinal, JSON_PRETTY_PRINT);

                    // Guardar el contenido en el archivo sin reemplazar
                    Storage::disk('public')->put('export/bex_0006/bexmovil/pedidos_txt/' . $namefile, $contenidoFinalJson);

                    // Enviar al Api de detalle
                    $urlDetalle = $config->urlEnvioDetalle;

                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $token,
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json', // Encabezado
                    ])->post($urlDetalle, $jsonDetail);

                    $result = $response->json();
                    Log::info($result);
                    if($response['codigo'] == 'OK'){
                        return 0;
                    }else{
                        return 1;
                    }


        } catch (\Exception $e) {
            Log::info($e);
            Tbl_Log::create([
                'descripcion' => 'Custom::bex_0006/OrderCoreCustom[sendDetailsApi()] => '.$e->getMessage()
            ]);
            return 1;
        }
    }
}
