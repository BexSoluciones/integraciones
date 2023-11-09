<?php

namespace App\Custom;

use App\Custom\WebServiceSiesa;
use App\Models\Ws_Unoee_Config;
use App\Models\Bex_0002\T29BexProductos;
use App\Traits\ConnectionTrait;

use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Schema\Blueprint;

class Insert_fyel_Custom
{
    use ConnectionTrait;

    public function __construct()
    {
    }
    public function InsertClientesCustom($conectionBex, $datosAInsertar, $modelInstance)
    {   
        $fechaActual = Carbon::now();
      
        $resultado = DB::connection($conectionBex)->table('tblmfpagovta')->get();

        foreach($resultado as $resul){
            
           
            $modelInstance::where('conpag', '=', $resul->CODFPAGOVTA)
                           ->update(['estadofpagovta' => 'C']);
        }
        // dd('PARAR');
        $codpago = $modelInstance::select('conpag','periodicidad')
                                 ->where('estadofpagovta','=','A')
                                 ->groupBy('conpag','periodicidad')
                                 ->get();

        $dataToInsert = [];
        foreach($codpago as $dato){
            // dd($dato->conpag);
            $dataToInsert[] = [
                    'codfpagovta' => $dato->conpag,
                    'nomfpagovta' => 'CONDICION '.$dato->conpag,
                    'perfpagovta' => $dato->periodicidad
                ];
        }    
        DB::connection($conectionBex)->table('tblmfpagovta')->insert($dataToInsert);

       
                
        DB::connection($conectionBex)->table('s1e_clientes')->truncate();

         $Insert = [];
        foreach ($datosAInsertar as $dato) {
            $Insert[] = [
                'codigo'        => $dato->codigo,
                'dv'            => $dato->dv,
                'sucursal'      => $dato->sucursal,
                'razsoc'        => utf8_encode($dato->razsoc),
                'representante' => utf8_encode($dato->representante),
                'direccion'     => $dato->direccion,
                'telefono'      => $dato->telefono,
                'precio'        => $dato->precio,
                'conpag'        => $dato->conpag,
                'periodicidad'  => $dato->periodicidad,
                'codvendedor'   => $dato->tercvendedor,
                'cupo'          => $dato->cupo,
                'codgrupodcto'  => $dato->codgrupodcto,
                'email'         => $dato->email,
                'codcliente'    => '0',
                'estado'        => $dato->estado,
                'estadofpagovta'=> $dato->estadofpagovta
            ];    
        }
           
        DB::connection($conectionBex)->table('s1e_clientes')->insert($Insert);
            
        DB::connection($conectionBex)->table('s1e_clientes')
                                    ->join('tblmcliente','s1e_clientes.codigo','=','tblmcliente.nitcliente')
                                    ->whereColumn('s1e_clientes.sucursal','tblmcliente.succliente')
                                    ->whereColumn('s1e_clientes.dv','tblmcliente.dvcliente')
                                    ->update(['s1e_clientes.codcliente' => DB::raw('tblmcliente.codcliente')]);
                                                                                       
        DB::connection($conectionBex)->table('s1e_clientes')
                                     ->join('tblmcliente','s1e_clientes.codcliente','=','tblmcliente.codcliente')
                                     ->whereColumn('s1e_clientes.sucursal','tblmcliente.succliente')
                                     ->whereColumn('s1e_clientes.dv','tblmcliente.dvcliente')
                                     ->update(['s1e_clientes.estado' => 'C']);

        $insertClient = DB::connection($conectionBex)->table('s1e_clientes')->where('estado','A')->get();

        if( count($insertClient) > 0){
           
                DB::connection($conectionBex)->table('tblmcliente')
                                            ->insertUsing(
                                                ['nitcliente', 'dvcliente','succliente','razcliente','nomcliente','dircliente','telcliente',
                                                'codbarrio','codtipocliente','codfpagovta','codprecio','coddescuento','email'],
                                                function ($query) {
                                                    $query->select('codigo','dv','sucursal','razsoc','representante','direccion','telefono','periodicidad','periodicidad','conpag','precio','dv','email')
                                                    ->from('s1e_clientes')
                                                    ->where('estado', 'A');
                                                }
                    );
                
                DB::connection($conectionBex)->table('s1e_clientes')
                                            ->join('tblmcliente','s1e_clientes.codigo','=','tblmcliente.nitcliente')
                                            ->whereColumn('s1e_clientes.sucursal','tblmcliente.succliente')
                                            ->whereColumn('s1e_clientes.dv','tblmcliente.dvcliente')
                                            ->where('estado', 'A')
                                            ->update(['s1e_clientes.codcliente' => DB::raw('tblmcliente.codcliente'),
                                                    'fecingcliente' => $fechaActual
                                            ]);
                
                DB::connection($conectionBex)->table('s1e_clientes')
                                            ->join('tblmcliente','s1e_clientes.codcliente','=','tblmcliente.codcliente')
                                            ->whereColumn('s1e_clientes.sucursal','tblmcliente.succliente')
                                            ->whereColumn('s1e_clientes.dv','tblmcliente.dvcliente')
                                            ->update(['s1e_clientes.estado' => 'C']);

                echo 'PASO POR LA ACTUALIZACION!!!!!!............';
        }                                          

    }

    public function InsertAmovilCustom($conectionBex, $datosAInsertar, $modelInstance)
    {
         $inset=(count($datosAInsertar));
        //  dd('PARAR');
        if($inset > 0){
 
            for($i=0;$i<$inset;$i++){

                $resultado = DB::connection($conectionBex)->table('tblmcliente')
                                                          ->where('nitcliente', $datosAInsertar[$i]->nitcliente)
                                                          ->where('succliente', $datosAInsertar[$i]->succliente)->first();
                
                $modelInstance::where('nitcliente', $resultado->NITCLIENTE)
                               ->where('succliente', $resultado->SUCCLIENTE)
                               ->update(['codcliente' => $resultado->CODCLIENTE]);
            }
                
            DB::connection($conectionBex)->table('tbldamovil')->truncate();
                

            $dataToInsert = [];
            foreach ($datosAInsertar as $dato) {
                $dataToInsert[] = [
                    'nitcliente'  => $dato->nitcliente,
                    'succliente'  => $dato->succliente,
                    'ano'         => $dato->ano,
                    'mes'         => $dato->mes,
                    'valor'       => $dato->valor,
                    'codvendedor' => $dato->tercvendedor,
                    'codcliente'  => $dato->codcliente
                ];
            }

            // Insertar los datos en lotes
            DB::connection($conectionBex)->table('tbldamovil')->insert($dataToInsert);
        }  
    }

    public function insertProductsCustom($conectionBex, $datosAInsertar, $modelInstance, $tableName){
        try {
            //Insertar datos en tblmunidademp
            $dataInsertTblmunidademp = T29BexProductos::dataInsertTblmunidademp()->get();
            DB::connection($conectionBex)
                ->table('tblmunidademp')
                ->insert($dataInsertTblmunidademp->toArray());
            print '◘ Datos insertados con exito en la tabla tblmunidademp' . PHP_EOL;

            //Insertar datos en tblmproveedor
            $dataInsertTblmproveedor = T29BexProductos::dataInsertTblmproveedor()->get();
            DB::connection($conectionBex)
                ->table('tblmproveedor')
                ->insert($dataInsertTblmproveedor->toArray());
            print '◘ Datos insertados con exito en la tabla tblmproveedor' . PHP_EOL;

            //Actualizar tabla tblmproducto
            $dataUpdateTblmproducto = T29BexProductos::dataUpdateTblmproducto()->get();
            foreach ($dataUpdateTblmproducto as $data) {
                DB::connection($conectionBex)
                    ->table('tblmproducto')
                    ->where('CODPRODUCTO', $data->PLUPRODUCTO)
                    ->update($data->toArray());
            }
            print '◘ Datos actualizados con exito en la tabla tblmproducto' . PHP_EOL;

            // Códigos de productos que necesitan actualizarse
            $dataUpdateT29BexProductos = DB::connection($conectionBex)
                                            ->table('tblmproducto')
                                            ->select('codproducto')
                                            ->get()
                                            ->pluck('codproducto');

            // Actualiza los registros en T29BexProductos
            T29BexProductos::whereIn('codigo', $dataUpdateT29BexProductos)
                            ->update(['estado' => 'C']);
            print '◘ Tabla t29_bex_productos actualizada a estado C exitosamente' . PHP_EOL;

            //Ejecutar distint
            $distintProducts = T29BexProductos::distintProducts()->get();
            DB::connection($conectionBex)
                ->table('tblmproducto')
                ->insert($distintProducts->toArray());
            print '◘ Sentencia distint ejecutada con exito' . PHP_EOL;
        } catch (\Exception $e) {
            print '¡Ocurrió un error en insertProductsCustom: ' . $e->getMessage() . PHP_EOL;
        }
    }

    
}