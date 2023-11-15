<?php

namespace App\Custom;

use App\Models\Bex_0002\T25BexPrecios;
use App\Models\Bex_0002\T29BexProductos;
use App\Models\Bex_0002\T16BexInventarios;
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
                                     ->whereColumn('s1e_clientes.codigo','tblmcliente.nitcliente')
                                     ->update(['s1e_clientes.estado' => 'C']);

        $insertClient = DB::connection($conectionBex)->table('s1e_clientes')->where('estado','A')->get();   

        if( count($insertClient) > 0){
           
            DB::connection($conectionBex)->table('tblmcliente')
                                        ->insertUsing(
                                            ['nitcliente', 'dvcliente','succliente','razcliente','nomcliente','dircliente','telcliente',
                                            'codbarrio','codtipocliente','codfpagovta','codprecio','coddescuento','email'],
                                            function ($query) {
                                                $query->select('codigo','dv','sucursal','razsoc','representante','direccion','telefono',
                                                'periodicidad','periodicidad','conpag','precio','dv','email')
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
                                     ->select('tblmvendedor.codvendedor','s1e_ruteros.dia','s1e_ruteros.secuencia',
                                              's1e_clientes.codcliente','s1e_clientes.cupo','s1e_clientes.precio','s1e_clientes.codgrupodcto')
                                     ->distinct()->get();

        DB::connection($conectionBex)->table('tblmrutero')
                                     ->join('s1e_clientes','tblmrutero.CODCLIENTE','=','s1e_clientes.codcliente')
                                     ->update(['tblmrutero.CUPO' => DB::raw('s1e_clientes.cupo')]);
 
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

    public function insertProductsCustom($conectionBex)
    {
        try {
            //Update estado_unidademp tabla t29_bex_productos
            $tblmunidademp = DB::connection($conectionBex)->table('tblmunidademp')->get();
            $codUnidadEmp  = $tblmunidademp->pluck('codunidademp')->toArray();

            if (!empty($codUnidadEmp)) {
                $t29BexProductos = T29BexProductos::whereIn('codunidademp', $codUnidadEmp)
                    ->update(['estado_unidademp' => 'C']);
                if($t29BexProductos == 1){
                    print '◘ Columna "estado_unidademp" actualizada en al tabla t29_bex_productos' . PHP_EOL;
                }else{
                    print '◘ No se acutalizo columna "estado_unidademp" en al tabla t29_bex_productos' . PHP_EOL;
                }
            }

            //Inserta datos en estado A en la tabla tblmunidademp
            $dataToInsertUnidadEmp = T29BexProductos::dataToInsertUnidadEmp()->get();
            if(sizeof($dataToInsertUnidadEmp) > 0){
                DB::connection($conectionBex)->table('tblmunidademp')->insert($dataToInsertUnidadEmp->toArray());
                print '◘ Datos insertados con exito en la tabla tblmunidademp' . PHP_EOL;
            }

            //Update estadoproveedor tabla t29_bex_productos
            $tblmproveedor = DB::connection($conectionBex)->table('tblmproveedor')->get();
            $codProveedor  = $tblmproveedor->pluck('CODPROVEEDOR')->toArray();
            $provee = count($codProveedor);
            if (!empty($codProveedor)) {
                for($i=0;$i<$provee;$i++){
                    $t29BexProductos = T29BexProductos::where('codproveedor', $codProveedor[$i])
                        ->update(['estadoproveedor' => 'C']);
                    // if($t29BexProductos == 1){
                    //     print '◘ Columna "estadoproveedor" actualizada en al tabla t29_bex_productos' . PHP_EOL;
                    // }else{
                    //     print '◘ No se acutalizo columna "estadoproveedor" en al tabla t29_bex_productos' . PHP_EOL;
                    // }
                }
            }

            //Inserta datos en estado A en la tabla t29_bex_productos
            $dataToInsertTblmproveedor = T29BexProductos::dataToInsertTblmproveedor()->get();
            if(sizeof($dataToInsertTblmproveedor) > 0){
                DB::connection($conectionBex)->table('tblmproveedor')->insert($dataToInsertTblmproveedor->toArray());
                print '◘ Datos insertados con exito en la tabla t29_bex_productos' . PHP_EOL;
            }

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

    public function insertPreciosCustom($conectionBex)
    {
        try {
            //Actualizar estado t25_bex_precios
            $codigosPrecio = DB::connection($conectionBex)->table('tblmprecio')->pluck('codprecio')->toArray();
            T25BexPrecios::whereIn('lista', $codigosPrecio)
                ->update(['estadoprecio' => 'C']);
            print '◘ Estados actualizados con exito en la tabla t25_bex_precios' . PHP_EOL;

            //Insertar datos en la tabla tblmprecio
            $insertDataTblmprecio = T25BexPrecios::insertDataTblmprecio()->get();
            DB::connection($conectionBex)
                ->table('tblmprecio')
                ->insert($insertDataTblmprecio->toArray());
            print '◘ Datos insertados con exito en la tabla tblmprecio' . PHP_EOL;

            //Delete a la tabla tbldproductoprecio
            DB::connection($conectionBex)->table('tbldproductoprecio')->truncate();
            print '◘ Datos eliminados con exito en la tabla tbldproductoprecio' . PHP_EOL;

            //Insertar datos en la tabla tbldproductoprecio
            $t25_bex_precios = T25BexPrecios::preciosAll()->get();
            $codigosProductos = $t25_bex_precios->pluck('codproducto')->toArray();

            $productosData = DB::connection($conectionBex)
                ->table('tblmproducto')
                ->whereIn('CODPRODUCTO', $codigosProductos)
                ->get();

            $dataToInsert = [];
            foreach($t25_bex_precios as $data){
                $resultados = $productoData = $productosData->firstWhere('CODPRODUCTO', $data->codproducto);
                if($resultados){
                    $dataToInsert[] = [
                        'codproducto'          => $data->codproducto,
                        'codprecio'            => $data->codprecio,
                        'precioproductoprecio' => $data->precioproductoprecio
                    ];
                }
            }

            // Eliminar duplicados usando array_unique
            $dataToInsert = array_unique($dataToInsert, SORT_REGULAR);

            DB::connection($conectionBex)->table('tbldproductoprecio')->insert($dataToInsert);
            print '◘ Datos insertados con éxito en la tabla tbldproductoprecio' . PHP_EOL;

        } catch (\Exception $e) {
            print '¡Ocurrió un error en insertPreciosCustom: ' . $e->getMessage() . PHP_EOL;
        }
    }
    
}