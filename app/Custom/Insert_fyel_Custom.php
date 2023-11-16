<?php

namespace App\Custom;

use App\Models\Bex_0002\T25BexPrecios;
use App\Models\Bex_0002\T29BexProductos;
use App\Models\Bex_0002\T16BexInventarios;
use App\Models\Bex_0002\T05BexClientes;
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
        $fechaActual = date('Ymd');
        $resultado = DB::connection($conectionBex)->table('tblmfpagovta')->get();

        foreach($resultado as $resul){
            $modelInstance::where('conpag', '=', $resul->CODFPAGOVTA)
                           ->update(['estadofpagovta' => 'C']);
        }
    
        $codpago = $modelInstance::select('conpag','periodicidad')
                                 ->where('estadofpagovta','=','A')
                                 ->where('codigo','!=','')
                                 ->groupBy('conpag','periodicidad')
                                 ->get();

        if(sizeof($codpago) != 0){
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
        }

        DB::connection($conectionBex)->table('s1e_clientes')->truncate();

        $datosAInsert = json_decode(json_encode($datosAInsertar,true));
        
        if(sizeof($datosAInsert) != 0){
            foreach (array_chunk($datosAInsert,2000) as $dato)  
            {
                    $Insert = [];
                    $count = count($dato);
                    for($i=0;$i<$count;$i++) {
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
                            'codcliente'    => '0',
                            'estado'        => $dato[$i]->estado,
                            'estadofpagovta'=> $dato[$i]->estadofpagovta
                        ];    
                    }
                    
                    DB::connection($conectionBex)->table('s1e_clientes')->insert($Insert);
                    print '◘ Datos insertados con exito en la tabla s1e_clientes' . PHP_EOL;
                }
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

        $insertClient = DB::connection($conectionBex)->table('s1e_clientes')
                                                    ->where('estado','A')
                                                    ->where('s1e_clientes.codcliente','<>','')
                                                    ->get();   

        if( count($insertClient) > 0){
            DB::connection($conectionBex)->table('tblmcliente')
                                        ->insertUsing(
                                            ['nitcliente', 'dvcliente','succliente','razcliente','nomcliente','dircliente','telcliente',
                                            'codbarrio','codtipocliente','codfpagovta','codprecio','coddescuento','email'],
                                            function ($query) {
                                                $query->select('codigo','dv','sucursal','razsoc','representante','direccion','telefono',
                                                'periodicidad','periodicidad','conpag','precio','dv','email')
                                                ->from('s1e_clientes')
                                                ->where('s1e_clientes.codcliente','<>','')
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
                                        ->whereColumn('s1e_clientes.codigo','tblmcliente.nitcliente')
                                        ->update(['s1e_clientes.estado' => 'C']);

            echo 'PASO POR LA ACTUALIZACION!!!!!!............';
        }      

        DB::connection($conectionBex)->table('tblmcliente')
                                    ->join('s1e_clientes','tblmcliente.codcliente','=','s1e_clientes.codcliente')
                                    ->whereColumn('tblmcliente.nitcliente','s1e_clientes.codigo')
                                    ->update(['tblmcliente.RAZCLIENTE' => DB::raw('s1e_clientes.razsoc'),
                                              'tblmcliente.NOMCLIENTE' => DB::raw('s1e_clientes.representante'),
                                              'tblmcliente.DIRCLIENTE' => DB::raw('s1e_clientes.direccion'),
                                              'tblmcliente.TELCLIENTE' => DB::raw('s1e_clientes.telefono'),
                                              'tblmcliente.CODFPAGOVTA' => DB::raw('s1e_clientes.conpag'),
                                              'tblmcliente.CODPRECIO' => DB::raw('s1e_clientes.precio'),
                                              'tblmcliente.EMAIL' => DB::raw('s1e_clientes.email') ]);
          print '◘ Datos Actualizados con exito en la tabla tblmcliente' . PHP_EOL;
    }

    public function InsertVendedoresCustom($conectionBex, $datosAInsertar, $modelInstance)
    {
        $fechaActual = Carbon::now();

        $inset=(count($datosAInsertar));

        if($inset > 0){

            DB::connection($conectionBex)->table('s1e_vendedores')->truncate();

            $dataToInsert = [];
            foreach ($datosAInsertar as $dato) {
                $dataToInsert[] = [
                    'compania'      => $dato->compania,
                    'codvendedor'   => $dato->tercvendedor,
                    'centro_ope'    => $dato->centroop,
                    'tipodocpedido' => $dato->tipodoc,
                    'estado'        => 'A'
                ];
            }

            DB::connection($conectionBex)->table('s1e_vendedores')->insert($dataToInsert);     
            
            DB::connection($conectionBex)->table('s1e_vendedores')
                                    ->join('tblmvendedor','s1e_vendedores.codvendedor','=','tblmvendedor.CODVENDEDOR')
                                    ->update(['s1e_vendedores.estado' => 'C']);

            $NuevoVend = DB::connection($conectionBex)->table('s1e_vendedores')
                                                     ->where('estado', 'A')->get();
            
            $dataToInsert=[];
            if(count($NuevoVend)>0){

                foreach($NuevoVend as $vendedor){
                    $dataToInsert[] = [
                        'codvendedor'           => $vendedor->codvendedor,
                        'nomvendedor'           => 'Vendedor Nuevo',
                        'codbodega'             => '001B1',
                        'codgrupoemp'           => '0',
                        'codsupervisor'         => $vendedor->centro_ope,
                        'codgrupodcto'          => '000',
                        'coddescuento'          => '0',
                        'codportafolio'         => '0',
                        'convisvendedor'        => '1',
                        'clavevendedor'         => $vendedor->codvendedor,
                        'porclogcumpvendedor'   => '95',
                        'califlogcumpvendedor'  => '5',
                        'porcantlogcumpvendedor'=> '90',
                        'hacedctopiefacaut'     => 'S',
                        'hacedctonc'            => 'N',
                        'descuentosescala'      => 'N',
                        'tercvendedor'          => NULL,
                        'tipodoc'               => $vendedor->tipodocpedido,
                        'cedula'                => NULL,
                        'co'                    => $vendedor->centro_ope,
                        'idcampaprobadores'     => '0'
                    ];
                }
                DB::connection($conectionBex)->table('tblmvendedor')->insert($dataToInsert);
            }

            $usuario = DB::connection($conectionBex)->table('tblsgrupo')
                                                    ->where('CODGRUPO', '200')->first();  
            
            if($usuario == null){
                DB::connection($conectionBex)->table('tblsgrupo')->insert([
                    'CODGRUPO'    => '200',
                    'NOMGRUPO'    => 'Vendedores',
                    'ESTADOGRUPO' => 'A',
                    'FECGRA'      => $fechaActual,
                    'CODGRA'      => 'root',
                    'dias_informes' => '0'
                ]);
            }              
             
            $vendedores = json_decode(json_encode(DB::connection($conectionBex)->table('tblmvendedor')
                                                                ->where('CODVENDEDOR', '<>', '')->get()),true);  
            
            foreach($vendedores as $vendedor){
                
                $usuario = DB::connection($conectionBex)->table('tblsusuario')
                                                        ->where('codusuario', $vendedor['CODVENDEDOR'])
                                                        ->where('bdusuario', null)->first();  

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

                    $dataToInsertGroup=[];
              
                    $dataToInsertGroup[] = [
                        'CODGRUPO'      => '200',
                        'CODUSUARIO'    => $vendedor['CODVENDEDOR'],
                        'ESTADOUSUGRU'  => 'A',
                        'FECGRA'        => $fechaActual,
                        'CODGRA'        => 'root'
                    ];
                    DB::connection($conectionBex)->table('tblsusugru')->insert($dataToInsertGroup);
                    
                }
            }
            $dataToInsert=[];
            if(count($NuevoVend)>0){
                foreach($NuevoVend as $vendedor){
                    $dataToInsert[] = [
                        'CODUSUARIO'           => $vendedor->codvendedor,
                        'CODEMPRESA'           => '001'
                    ];
                }
                DB::connection($conectionBex)->table('tbldusuarioempresa')->insert($dataToInsert);
            }
            print '◘ Datos Actualizados con exito en la tabla tblmvendedor' . PHP_EOL;
            // dd('finalizo segundo dd');
        }

    }

    public function InsertRuteroCustom($conectionBex, $datosAInsertar, $modelInstance,$conectionSys){
        $inset=(count($datosAInsertar));
 
        if($inset > 0){
            DB::connection($conectionBex)->table('s1e_ruteros')->truncate();

            $Insert = [];
            foreach ($datosAInsertar as $dato) {
                $Insert[] = [
                    'codvendedor'    => $dato->tercvendedor,
                    'dia'            => $dato->dia,
                    'dia_descrip'    => $dato->dia_descrip,
                    'cliente'        => $dato->cliente,
                    'dv'             => $dato->dv,
                    'sucursal'       => $dato->sucursal,
                    'secuencia'      => $dato->secuencia,
                    'estadodiarutero'=> $dato->estadodiarutero
                ];    
        }
           
        DB::connection($conectionBex)->table('s1e_ruteros')->insert($Insert);

        DB::connection($conectionBex)->table('s1e_ruteros')
                                    ->join('tblmdiarutero','s1e_ruteros.dia','=','tblmdiarutero.diarutero')
                                    ->update(['s1e_ruteros.estadodiarutero' => 'C']);
                                
        $ruteroDias = DB::connection($conectionBex)->table('s1e_ruteros')
                                ->select(['dia','dia_descrip'])
                                ->distinct()
                                ->where('estadodiarutero', 'A')
                                ->where('cliente','<>','')
                                ->get();

        if(count($ruteroDias) > 0){

            foreach($ruteroDias as $ruteroDia){

                DB::connection($conectionBex)->table('tblmdiarutero')
                                            ->insert([
                                                'DIARUTERO' => $ruteroDia->dia,
                                                'NOMDIARUTERO' => $ruteroDia->dia_descrip
                ]);
            }
        }

        $rutero=DB::connection($conectionBex)->table('s1e_ruteros')
                                     ->join('s1e_clientes','s1e_ruteros.cliente','=','s1e_clientes.codigo')
                                     ->join('tblmvendedor','s1e_ruteros.codvendedor','=','tblmvendedor.CODVENDEDOR')
                                     ->join('tblmdiarutero','s1e_ruteros.dia','=','tblmdiarutero.diarutero')
                                     ->whereColumn('s1e_ruteros.sucursal','s1e_clientes.sucursal')
                                     ->where('s1e_ruteros.cliente','<>','')
                                     ->select('tblmvendedor.codvendedor','s1e_ruteros.dia','s1e_ruteros.secuencia',
                                              's1e_clientes.codcliente','s1e_clientes.cupo','s1e_clientes.precio','s1e_clientes.codgrupodcto')
                                     ->distinct()->get();

        DB::connection($conectionBex)->table('tblmrutero')
                                     ->join('s1e_clientes','tblmrutero.CODCLIENTE','=','s1e_clientes.codcliente')
                                     ->update(['tblmrutero.CUPO' => DB::raw('s1e_clientes.cupo')]);
            print '◘ Datos Actualizados con exito en la tabla tblmrutero' . PHP_EOL;
        }else{
            dd('No se ejecuto');
        }

    }

    public function InsertCarteraCustom($conectionBex, $datosAInsertar, $modelInstance)
    {
        DB::connection($conectionBex)->table('s1e_cartera')->truncate();

        $dataToInsert = [];
        foreach ($datosAInsertar as $dato) {
            $dataToInsert[] = [
                'nitcliente'    => $dato->nitcliente,
                'dv'            => $dato->dv,
                'succliente'    => $dato->succliente,
                'codtipodoc'    => $dato->codtipodoc,
                'documento'     => $dato->documento,
                'fecmov'        => $dato->fecmov,
                'fechavenci'    => $dato->fechavenci,
                'valor'         => $dato->valor,
                'codvendedor'   => $dato->codvendedor,
                'x'             => NULL,
                'codcliente'    => '',
                'estadotipodoc' => 'A'
            ];
        }

        // Insertar los datos en lotes
        DB::connection($conectionBex)->table('s1e_cartera')->insert($dataToInsert);

        DB::connection($conectionBex)->table('s1e_cartera')
                                     ->join('tblmcliente','s1e_cartera.nitcliente','=','tblmcliente.NITCLIENTE')
                                     ->whereColumn('s1e_cartera.succliente','tblmcliente.SUCCLIENTE')
                                     ->update(['s1e_cartera.codcliente' => DB::raw('tblmcliente.CODCLIENTE')]);
                                    
        DB::connection($conectionBex)->table('tbldcartera')->truncate();

        DB::connection($conectionBex)->table('tbldcartera')
                                    ->insertUsing(
                                        ['CODVENDEDOR', 'CODCLIENTE','CODTIPODOC','NUMMOV','FECMOV','FECVEN','PRECIOMOV'],
                                        function ($query) {
                                            $query->select('s1e_cartera.codvendedor','s1e_cartera.codcliente','s1e_cartera.codtipodoc','documento','s1e_cartera.fecmov','fechavenci','valor')
                                            ->from('s1e_cartera')
                                            ->join('tblmcliente','s1e_cartera.codcliente','=','tblmcliente.CODCLIENTE')
                                            ->join('tblmvendedor','s1e_cartera.codvendedor','=','tblmvendedor.CODVENDEDOR');
                                        });
        
        DB::connection($conectionBex)->table('tbldcartera')
                                    ->where('preciomov','>=',0)
                                    ->update(['debcre' => 'D']);
            
        DB::connection($conectionBex)->table('tbldcartera')
                                    ->where('preciomov','<',0)
                                    ->update(['debcre' => 'C']);

        DB::connection($conectionBex)->table('tbldcartera')
                                    ->where('preciomov','<',0)
                                    ->update(['preciomov' => DB::raw('preciomov * (-1)')]);

        // DB::connection($conectionBex)->statement('CREATE TABLE IF NOT EXISTS s1e_dptos (
        //                                             codpais varchar(5),
        //                                             coddpto varchar(5),
        //                                             descripcion varchar(50)                          
        //                                                 )');

        // DB::connection($conectionBex)->statement('DROP TABLE IF EXISTS s1e_dptos');
    }

    public function InsertEstadoPedidosCustom($conectionBex, $datosAInsertar, $modelInstance)
    {
        $inset=(count($datosAInsertar));
        //  dd('PARAR');
        if($inset > 0){
            DB::connection($conectionBex)->table('s1e_estadopedidos')->truncate();

            $dataToInsert = [];
            
            foreach($datosAInsertar as $data){
                $dataToInsert[] = [
                    'codemp'     => $data->codemp,
                    'codvend'    => $data->codvend,
                    'tipoped'    => $data->tipoped,
                    'numped'     => $data->numped,
                    'nitcli'     => $data->nitcli,
                    'succli'     => $data->succli,
                    'fecped'     => $data->fecped,
                    'ordenped'   => $data->ordenped,
                    'codpro'     => $data->codpro,
                    'refer'      => $data->refer,
                    'descrip'    => $data->descrip,
                    'cantped'    => $data->cantped,
                    'vlrbruped'  => $data->vlrbruped,
                    'ivabruped'  => $data->ivabruped,
                    'vlrnetoped' => $data->vlrnetoped,
                    'cantfacped' => $data->cantfacped,
                    'estado'     => $data->estado,
                    'tipo'       => $data->tipo,
                    'tipofac'    => $data->tipofac,
                    'factura'    => $data->factura,
                    'ordenfac'   => $data->ordenfac,
                    'cantfac'    => $data->cantfac,
                    'vlrbrufac'  => $data->vlrbrufac,
                    'ivabrufac'  => $data->ivabrufac,
                    'vlrnetofac' => $data->vlrnetofac,
                    'obsped'     => $data->obsped,
                    'ws_id'      => $data->ws_id,
                    'codcliente' => null,
                    'codvendedor'=> $data->codvend
                ];
            }
            DB::connection($conectionBex)->table('s1e_estadopedidos')->insert($dataToInsert);
            
            DB::connection($conectionBex)->table('s1e_estadopedidos')
                                        ->join('tblmcliente','s1e_estadopedidos.nitcli','=','tblmcliente.NITCLIENTE')
                                        ->whereColumn('tblmcliente.SUCCLIENTE','s1e_estadopedidos.succli')
                                        ->update(['s1e_estadopedidos.codcliente' => DB::raw('tblmcliente.CODCLIENTE')]);

            DB::connection($conectionBex)->table('gm_MOB_CAB_PEDIDOS')->truncate();
            DB::connection($conectionBex)->table('gm_MOB_DETPEDIDOS')->truncate();
            DB::connection($conectionBex)->table('gm_MOB_DETALLEFACT')->truncate();

            // Desactivar only_full_group_by
            DB::connection($conectionBex)->statement("SET SESSION sql_mode = ''");

            DB::connection($conectionBex)->table('gm_MOB_CAB_PEDIDOS')
                                        ->insertUsing(
                                            ['numpedido', 'feccrepedido', 'fecpromesa', 'dircliente', 
                                            'vendedor', 'tipopedido', 'estadopedido', 'ordendecompra', 'ordenpedido', 
                                            'valtotpedidosiniva', 'iva', 'observaciones', 'retenciones', 'UnidadOperativa'],
                                            function ($query) {
                                                $query->selectRaw('numped, SUBSTR(fecped,1,10) AS fecped, "N.A." AS fecpromesa, codcliente,
                                                codvendedor, tipoped, estado, ordenped,  "" AS ordenpedido, SUM(vlrbruped) AS valtotpedidosiniva, 
                                                SUM(ivabruped) AS iva, obsped AS observaciones, " " AS retenciones," " AS UnidadOperativa')
                                                ->from('s1e_estadopedidos')
                                                ->groupBy('numped')
                                                ->get();
                                            });

            // Restaurar only_full_group_by
            DB::statement("SET SESSION sql_mode = 'ONLY_FULL_GROUP_BY'");

            DB::connection($conectionBex)->table('gm_MOB_DETPEDIDOS')
                                        ->insertUsing(
                                            ['numpedido', 'linea', 'codigoebs', 'descripcion', 'qtypedidooriginal',
                                             'qtyasignada', 'qtyenviada', 'qtycancelada', 'precioconiva', 'UnidadOperativa'],                    
                                            function ($query) {
                                                $query->selectRaw('numped, ordenped AS linea, refer, descrip, cantped, cantped AS qtyasignada,
                                                cantfacped AS qtyenviada, "0" AS qtycancelada, vlrnetoped AS precioconiva, "" AS UnidadOperativa')
                                                ->from('s1e_estadopedidos')
                                                ->get();
                                            });

            DB::connection($conectionBex)->table('gm_MOB_DETALLEFACT')
                                            ->insertUsing(
                                                ['numpedido', 'numfactura', 'linea', 'codigoebs', 'descripcion', 'qtyfacturada',
                                                 'preciosiniva', 'precioextendido', 'iva', 'valorconiva','UnidadOperativa'],                    
                                                function ($query) {
                                                    $query->selectRaw('numped, factura, ordenfac AS linea, refer, descrip, cantfac AS qtyfacturada,
                                                    vlrbrufac AS preciosiniva, "0" AS precioextendido, ivabrufac AS iva, vlrnetofac AS valorconiva, "" AS UnidadOperativa')
                                                    ->from('s1e_estadopedidos')
                                                    ->where('factura','>',0)
                                                    ->get();
                                                });
        }

    }

    public function InsertAmovilCustom($conectionBex, $datosAInsertar, $modelInstance)
    {   
        print '◘ Inicia la Actualizacion en la tabla tbldamovil' . PHP_EOL;
         $inset=(count($datosAInsertar));
        //  dd('PARAR');
        if($inset > 0){

            DB::connection($conectionBex)->table('tbldamovil')->truncate();

            $datosAInsert = json_decode(json_encode($datosAInsertar,true));

            // Insertar los datos en lotes
            if(sizeof($datosAInsertar) > 0){
                foreach (array_chunk($datosAInsert,3000) as $dato)  
                {
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
                        print '◘ Datos insertados con exito en la tabla tbldamovil' . PHP_EOL;
                    }
            }
 
            DB::connection($conectionBex)->table('tbldamovil')
                            ->join('tblmcliente','tbldamovil.nitcliente','=','tblmcliente.nitcliente')
                            ->whereColumn('tbldamovil.succliente','tblmcliente.succliente')
                            ->update(['tbldamovil.codcliente' => DB::raw('tblmcliente.codcliente')]);


            print '◘ Datos Actualizados con exito en la tabla tbldamovil' . PHP_EOL;
        }else{
            print '◘ No hay datos para Actualizar en la tabla tbldamovil' . PHP_EOL;
        } 
    }

    public function insertProductsCustom($conectionBex, $datosAInsertar, $modelInstance)
    {
        try {
            DB::connection($conectionBex)->table('s1e_productos')->truncate();

            $datosAInsert = json_decode(json_encode($datosAInsertar,true));
            
            if(sizeof($datosAInsertar) > 0){
                foreach (array_chunk($datosAInsert,3000) as $dato)  
                {
                        $Insert = [];
                        $count = count($dato);
                        for($i=0;$i<$count;$i++) {
                            $Insert[] = [
                                'codigo'          => $dato[$i]->codigo,
                                'descripcion'     => $dato[$i]->descripcion,
                                'codunidademp'    => $dato[$i]->codunidademp,
                                'peso'            => $dato[$i]->peso,
                                'codproveedor'    => $dato[$i]->codproveedor,
                                'nomproveedor'    => $dato[$i]->nomproveedor,
                                'unidadventa'     => $dato[$i]->unidadventa,
                                'estado'          => $dato[$i]->estado,
                                'estadounidademp' => $dato[$i]->estado_unidademp,
                                'estadoproveedor' => $dato[$i]->estadoproveedor
                            ];    
                        }
                        
                        DB::connection($conectionBex)->table('s1e_productos')->insert($Insert);
                        print '◘ Datos insertados con exito en la tabla s1e_productos' . PHP_EOL;
                    }
            }

              //Update estado_unidademp tabla s1e_productos
            DB::connection($conectionBex)->table('s1e_productos')
                                    ->join('tblmunidademp','s1e_productos.codunidademp','=','tblmunidademp.codunidademp')
                                    ->update(['s1e_productos.estadounidademp' => 'C']);
            print '◘ Estados actualizados con exito en la columna "estadounidademp"' . PHP_EOL;
                                                                                       
            $insertUndemp = DB::connection($conectionBex)->table('s1e_productos')
                                                        ->where('estadounidademp','A')
                                                        ->where('codigo','<>','')
                                                        ->get();   

             //Inserta datos en estado A en la tabla tblmunidademp
            if( count($insertUndemp) > 0){
               
                DB::connection($conectionBex)->table('tblmunidademp')
                                            ->insertUsing(
                                                ['codunidademp', 'NOMUNIDADEMP'],
                                                function ($query) {
                                                    $query->selectRaw('codunidademp,codunidademp as NOMUNIDADEMP')
                                                    ->from('s1e_productos')
                                                    ->where('estadounidademp', 'A')
                                                    ->where('codigo','<>','')
                                                    ->get();
                                                }
                    );
            }
            print '◘ Datos Actualizados con exito en la tabla tblmunidademp' . PHP_EOL;

            DB::connection($conectionBex)->table('s1e_productos')
                                        ->join('tblmproveedor','s1e_productos.codproveedor','=','tblmproveedor.CODPROVEEDOR')
                                        ->update(['s1e_productos.estadoproveedor' => 'C']);
            print '◘ Estados actualizados con exito en la columna "estadoproveedor"' . PHP_EOL;
                                                                        
            $insertProveed = DB::connection($conectionBex)->table('s1e_productos')
                                            ->where('estadoproveedor','A')
                                            ->where('codigo','<>','')
                                            ->get();   

            //Inserta datos en estado A en la tabla tblmunidademp
            if( count($insertProveed) > 0){

                DB::connection($conectionBex)->table('tblmproveedor')
                                    ->insertUsing(
                                        ['CODPROVEEDOR', 'NOMPROVEEDOR'],
                                        function ($query) {
                                            $query->selectRaw('codunidademp,codunidademp as NOMUNIDADEMP')
                                            ->from('s1e_productos')
                                            ->where('estadoproveedor', 'A')
                                            ->where('codigo','<>','')
                                            ->get();
                                        }
                                    );
            }
            print '◘ Datos Actualizados con exito en la tabla tblmproveedor' . PHP_EOL;

            DB::connection($conectionBex)->table('tblmproducto')
                                ->join('s1e_productos','tblmproducto.CODPRODUCTO','=','s1e_productos.codigo')
                                ->where('s1e_productos.codigo','<>','')
                                ->update(['tblmproducto.NOMPRODUCTO' => DB::raw('s1e_productos.descripcion'),
                                        'tblmproducto.PLUPRODUCTO' => DB::raw('s1e_productos.codigo'),
                                        'tblmproducto.codunidademp' => DB::raw('s1e_productos.codunidademp'),
                                        'tblmproducto.PESO' => DB::raw('s1e_productos.peso'),
                                        'tblmproducto.CODPROVEEDOR' => DB::raw('s1e_productos.codproveedor'),
                                        'tblmproducto.unidadventa' => DB::raw('s1e_productos.unidadventa')]);
            print '◘ Datos actualizados con exito en la tabla tblmproducto' . PHP_EOL;

            // Códigos de productos que necesitan actualizarse
            DB::connection($conectionBex)->table('s1e_productos')
                                    ->join('tblmproducto','s1e_productos.codigo','=','tblmproducto.CODPRODUCTO')
                                    ->update(['s1e_productos.estado' => 'C']);
            print '◘ Estados actualizados con exito en la columna "estado de s1e_productos"' . PHP_EOL;

            //Ejecutar distint
            $insertProduc = DB::connection($conectionBex)->table('s1e_productos')
                                            ->where('estado','A')
                                            ->where('codigo','<>','')
                                            ->get();   

            //Inserta datos en estado A en la tabla tblmproducto
            if( count($insertProduc) > 0){

                DB::connection($conectionBex)->table('tblmproducto')
                                    ->insertUsing(
                                        ['CODPRODUCTO', 'codempresa','pluproducto','nomproducto','codunidademp','bonentregaproducto',
                                         'codgrupoproducto','estadoproducto'],
                                        function ($query) {
                                            $query->selectRaw('codigo,"001" as codbodega,codigo,descripcion,s1e_productos.codunidademp,
                                            "0" as bonentregaproducto,"0" as codgrupoproducto,s1e_productos.estado')
                                            ->distinct()
                                            ->from('s1e_productos')
                                            ->where('estado', 'A')
                                            ->where('codigo','<>','')
                                            ->get();
                                        }
                                    );
            }
            print '◘ Datos Actualizados con exito en la tabla tblmproducto' . PHP_EOL;

        } catch (\Exception $e) {
            print '¡Ocurrió un error en insertProductsCustom: ' . $e->getMessage() . PHP_EOL;
        }
    }
    
    public function insertInventarioCustom($conectionBex, $conectionSys, $modelInstance)
    {
        try {
            //Conexion plafor_sys
            $tblslicencias = DB::connection($conectionSys)
                ->table('tblslicencias')
                ->select('borrardstockimportando', 'creabodegaempresa')
                ->where('bdlicencias', 'platafor_pi055')
                ->first();
            
            //Update estadoimpuesto tabla t16_bex_inventarios
            $tblmimpuesto = DB::connection($conectionBex)->table('tblmimpuesto')
                                                        ->select('codimpuesto')->get();
             $iva          = $tblmimpuesto->pluck('codimpuesto')->toArray();
             $imp = count($tblmimpuesto);

            if (!empty($iva)) {
                for($i=0; $i<$imp; $i++){
                    $t16BexInventarios = T16BexInventarios::where('iva', $iva[$i])
                        ->update(['estadoimpuesto' => 'C']);
                    if($t16BexInventarios > 0){
                        print '◘ Columna "estadoimpuesto" actualizada en al tabla t16_bex_inventarios' . PHP_EOL;
                    }else{
                        print '◘ No hay acutalizaciones en la columna "estadoimpuesto" en al tabla t16_bex_inventarios' . PHP_EOL;
                    }
                }
            }

            //Inserta datos en estado A en la tabla tblmimpuesto
            $insertDataTblmimpuesto = T16BexInventarios::insertDataTblmimpuesto()->get();

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
                    $t16BexInventarios = T16BexInventarios::where('bodega', $codBodega[$i])
                        ->update(['estadobodega' => 'C']);
                    if($t16BexInventarios > 0){
                        print '◘ Columna "estadobodega" actualizada en al tabla t16_bex_inventarios' . PHP_EOL;
                    }else{
                        print '◘ No se acutalizo columna "estadobodega" en al tabla t16_bex_inventarios' . PHP_EOL;
                    }
                }
            }

            //Inserta datos en estado A en la tabla tblmbodega
            $insertDataToTblmbodega = T16BexInventarios::insertDataToTblmbodega()->get();
            if(sizeof($insertDataToTblmbodega) > 0){
                DB::connection($conectionBex)->table('tblmbodega')->insert($insertDataToTblmbodega->toArray());
                print '◘ Datos insertados con exito en la tabla tblmbodega' . PHP_EOL;
            }
            
            if($tblslicencias->borrardstockimportando == "S"){
                DB::connection($conectionBex)->table('tbldstock')->truncate();
                print '◘ Datos eliminados con exito en la tabla tbldstock' . PHP_EOL;
            }

            //Insertar datos en la tabla tbldstock
            $insertDataTbldstock = T16BexInventarios::insertDataTbldstock()->get();
            $codigosProductos = $insertDataTbldstock->pluck('codproducto')->toArray();

            // Obtener datos de tblmproducto en una sola consulta
            $productosData = DB::connection($conectionBex)
                ->table('tblmproducto')
                ->whereIn('CODPRODUCTO', $codigosProductos)
                ->get();

            $dataToInsert = [];
            foreach ($insertDataTbldstock as $data) {
                // Buscar los datos correspondientes en $productosData
                $productoData = $productosData->firstWhere('CODPRODUCTO', $data->codproducto);

                if ($productoData) {
                    $dataToInsert[] = [
                        'codproducto' => $data->codproducto,
                        'codbodega' => $data->codbodega,
                        'codimpuesto' => $data->codimpuesto,
                        'existencia_stock' => $data->existencia_stock
                    ];
                }
            }
            DB::connection($conectionBex)->table('tbldstock')->insert($dataToInsert);
            print '◘ Datos insertados con éxito en la tabla tbldstock' . PHP_EOL;

        } catch (\Exception $e) {
            print '¡Ocurrió un error en insertInventarioCustom: ' . $e->getMessage() . PHP_EOL;
        }
    }

    public function insertPreciosCustom($conectionBex, $datosAInsertar, $modelInstance)
    {
        try {

            DB::connection($conectionBex)->table('s1e_precios')->truncate();

            $datosAInsert = json_decode(json_encode($datosAInsertar,true));
            
            if(sizeof($datosAInsertar) > 0){
                foreach (array_chunk($datosAInsert,3000) as $dato)  
                {
                        $Insert = [];
                        $count = count($dato);
                        for($i=0;$i<$count;$i++) {
                            $Insert[] = [
                                'lista'        => $dato[$i]->lista,
                                'producto'     => $dato[$i]->producto,
                                'precio'       => $dato[$i]->precio,
                                'estadoprecio' => $dato[$i]->estadoprecio
                            ];    
                        }
                        
                        DB::connection($conectionBex)->table('s1e_precios')->insert($Insert);
                        print '◘ Datos insertados con exito en la tabla s1e_precios' . PHP_EOL;
                    }
            }

            //Actualizar estado t25_bex_precios
            DB::connection($conectionBex)->table('s1e_precios')
                                    ->join('tblmprecio','s1e_precios.lista','=','tblmprecio.codprecio')
                                    ->update(['s1e_precios.estadoprecio' => 'C']);
            print '◘ Estados actualizados con exito en la tabla s1e_precios' . PHP_EOL;
                                                                                       
            $insertPrecio = DB::connection($conectionBex)->table('s1e_precios')->where('estadoprecio','A')->get();   

            //Insertar datos en la tabla tblmprecio
            if( count($insertPrecio) > 0){
               
                DB::connection($conectionBex)->table('tblmprecio')
                                            ->insertUsing(
                                                ['codprecio', 'nomprecio'],
                                                function ($query) {
                                                    $query->selectRaw('lista,concat("LISTA PRECIO ",lista)')
                                                    ->from('s1e_precios')
                                                    ->where('estadoprecio', 'A')
                                                    ->get();
                                                }
                    );
            }

            //Delete a la tabla tbldproductoprecio
            if(sizeof($datosAInsertar) > 0){
                DB::connection($conectionBex)->table('tbldproductoprecio')->truncate();
                print '◘ Datos eliminados con exito en la tabla tbldproductoprecio' . PHP_EOL;

                DB::connection($conectionBex)->table('tbldproductoprecio')
                                            ->insertUsing(
                                                ['CODPRODUCTO', 'codprecio','precioproductoprecio'],
                                                function ($query) {
                                                    $query->selectRaw('producto,lista,precio')
                                                    ->from('s1e_precios')
                                                    ->join('tblmproducto','s1e_precios.producto','=','tblmproducto.CODPRODUCTO')
                                                    ->distinct()
                                                    ->get();
                                                }
                    );
                print '◘ Datos insertados con éxito en la tabla tbldproductoprecio' . PHP_EOL;
            }
           
        } catch (\Exception $e) {
            print '¡Ocurrió un error en insertPreciosCustom: ' . $e->getMessage() . PHP_EOL;
        }
    }
    
}