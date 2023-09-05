<?php
namespace App\Traits;

use Exception;
use App\Models\Pandapan\BexMpio;
use App\Models\Pandapan\BexPais;
use App\Models\Pandapan\BexDpto;
use App\Models\Pandapan\BexRutero;
use App\Models\Pandapan\BexBodega;
use App\Models\Pandapan\BexProdcli;
use App\Models\Pandapan\BexCliente;
use App\Models\Pandapan\BexMensaje;
use App\Models\Pandapan\BexVendedor;
use App\Models\Pandapan\BexProducto;
use App\Models\Pandapan\BexPrepacks;
use App\Models\Pandapan\BexIndicador;
use App\Models\Pandapan\BexInventario;
use App\Models\Pandapan\BexPortafolio;
use App\Models\Pandapan\BexCodBarraPro;
use App\Models\Pandapan\BexPlanCliente;
use App\Models\Pandapan\BexPortafoliod;
use App\Models\Pandapan\BexPlanProducto;
use App\Models\Pandapan\BexDctoProducto;
use App\Models\Pandapan\BexPromoDsctoTope;
use App\Models\Pandapan\BexPromoDsctoLinea;
use App\Models\Pandapan\BexClienteCriterio;
use App\Models\Pandapan\BexCriterioCliente;
use App\Models\Pandapan\BexCriterioProducto;
use App\Models\Pandapan\BexProductoCriterio;
use App\Models\Pandapan\BexReferenciaAlterna;

use Illuminate\Support\Facades\DB;

trait TemporalPandapanTrait {

    public function insert($table, $data){
        if($table == "CLIENTES-" && isset($data['codigo'])){
            $clienteData = [
                'codigo'=>$data['codigo'], 'dv'=>$data['dv'], 'sucursal'=>$data['suc'], 'razsoc'=>$data['razsoc'],
                'representante'=>$data['repres'], 'direccion'=>$data['direcc'], 'telefono'=>$data['telefono'],
                'precio'=>$data['precio'], 'conpag'=>$data['conpag'], 'periodicidad'=>$data['period'],
                'tercvendedor'=>$data['codven'], 'cupo'=>$data['cupo'], 'nomconpag'=>$data['nomconpag'],
                'barrio'=>$data['barrio'], 'tipocliente'=>$data['tipocli'], 'cobraiva'=>$data['cobraiva'],
                'codpais'=>$data['pais'], 'coddpto'=>$data['depto'], 'codmpio'=>$data['ciudad'], 'codbarrio'=>'//',
                'consec'=>$data['email'], 'codcliente'=>'//', 'estado'=>'//', 'estadofpagovta'=>'//'
            ];
            BexCliente::create($clienteData);
            $this->info('Codigo '.$data['codigo'].' cliente registrado exitosamente.');
        }

        if($table == "CLIENTES_CRITERIOS" && isset($data['nit'])){
            $clienteCriterioData = [
                'cli_nitcliente'=>$data['nit'], 'cli_dvcliente'=>$data['dv'], 'cli_succliente'=>$data['suc'],
                'cli_vendedor'=>$data['vendedor'], 'cli_plancriterios'=>$data['plancri'], 
                'cli_criteriomayor'=>$data['crimay'], 'cli_grupodscto'=>$data['grudcto'], 
                'cli_tipocli'=>$data['tipocli'], 'codclientealt'=>$data['codclientealt'], 'codcliente'=>'//'
            ];
            BexClienteCriterio::create($clienteCriterioData);
            $this->info('Nit '.$data['nit'].' cliente criterio registrado exitosamente.');
        }

        if($table == "AMOVL-"){
            //La consulta se demora en cargar
        }

        if($table == "CARTERA-"){
            //No coinciden los datos que trae con la tabla
        }

        if($table == "DCTOS_PRODUCTO" && isset($data['CODGRU'])){
            $dctoProductoData = [
                'codgrupodcto'=>$data['CODGRU'], 'codproducto'=>$data['CODPRO'], 'descuento'=>$data['DESCTO'],
                'estado'=>'//'
            ];
            BexDctoProducto::create($dctoProductoData);
            $this->info('Descuento '.$data['CODGRU'].' registrado exitosamente.');
        }

        if($table == "INACTIVOS-"){
            //No coiciden los datos
        }

        if($table == 'INDICADORES' && isset($data['VEND'])){
            $indicadoresData = [
                'tercvendedor'=>$data['VEND'], 'detmensaje'=>$data['DETALLE']
            ];
            BexIndicador::create($indicadoresData);
            $this->info('Indicador vendedor '.$data['VEND'].' registrado exitosamente.');
        }

        if($table == 'INVENTARIOS-' && isset($data['bodega'])){
            $iventariosData = [
                'bodega'=>$data['bodega'], 'iva'=>$data['iva'], 'producto'=>$data['produc'], 'inventario'=>$data['invent']
            ];
            BexInventario::create($iventariosData);
            $this->info('Inventario bodega '.$data['bodega'].' registrado exitosamente.');
        }

        if($table == 'MENSAJES' && isset($data['CODMEN'])){
            $mensajesjeData = [
                'codmensaje'=>$data['CODMEN'], 'tipomensaje'=>$data['TIPMEN'], 'nommensaje'=>$data['NOMMEN']
            ];
            BexMensaje::create($mensajesjeData);
            $this->info('Mensaje '.$data['CODMEN'].' registrado exitosamente.');
        }

        if($table == "PENDI-"){
            //No existe tabla en la BD con este nombre
        }

        if($table == "PORTAFOLIO" && isset($data['codpor'])){
            $portafolioData = [
                'codportafolio'=>$data['codpor'], 'nomportafolio'=>$data['despor']
            ];
            BexPortafolio::create($portafolioData);
            $this->info('Portafolio '.$data['codpor'].' registrado exitosamente.');
        }

        if($table == "PORTAFOLIOD-" && isset($data['codpro'])){
            $portafoliodData = [
                'codproducto'=>$data['codpro'], 'codportafolio'=>$data['codpor']
            ];
            BexPortafoliod::create($portafoliodData);
            $this->info('Portafoliod '.$data['codpro'].' registrado exitosamente.');
        }

        if($table == "PRECIOS-"){
            //La consulta parece tener error de sintaxis
        }

        if($table == "PREPACKS" && isset($data['codprep'])){
            $prepacksData = [
                'codprepack'=>$data['codprep'], 'codproducto'=>$data['codpro'], 'cajas'=>$data['cajas'],
                'unidades'=>$data['unidades'], 'nomprepack'=>$data['nomprepack'],
            ];
            BexPrepacks::create($prepacksData);
            $this->info('Prepacks '.$data['codprep'].' registrado exitosamente.');
        }

        if($table == "PRESUPUESTOS-"){
            //No da respuesta el query
        }

        if($table == "PRODCLI" && isset($data['NITCLI'])){
            $prodcliData = [
                'codempresa'=>$data['CODEMP'], 'tercvendedor'=>$data['CODVEN'], 'nitcliente'=>$data['NITCLI'],
                'succliente'=>$data['SUCURS'], 'codproducto'=>$data['CODPRO'], 'cantidad'=>$data['CANTID'],
                'codcliente'=>'//'
            ];
            BexProdcli::create($prodcliData);
            $this->info('Prodcli con CODPRO '.$data['CODPRO'].' registrado exitosamente.');
        }

        if($table == "PRODUCTOS" && isset($data['PLU'])){
            $productosData = [
                'pru'=>$data['PLU'], 'descripcion'=>$data['descr'], 'codigo'=>$data['codigo'], 
                'codunidademp'=>$data['coduni1'], 'nomunidademp'=>$data['nomuni1'], 'factor'=>$data['factor1'],
                'codproveedor'=>$data['codpro'], 'nomproveedor'=>$data['nompro'], 'codbarra'=>$data['codbar'],
                'comb_009'=>$data['comb_009'], 'comb_010'=>$data['comb_010'], 'codmarca'=>$data['codmarca'],
                'nommarca'=>$data['nommarca'], 'codunidadcaja'=>$data['coduni2'], 'detalle'=>$data['beee2_descripcion'],
                'tipo_inv'=>$data['tipo_inv'], 'ccostos'=>'//'
            ];
            BexProducto::create($productosData);
            $this->info('Producto '.$data['PLU'].' registrado exitosamente.');
        }

        if($table == "PRODUCTOS_CRITERIOS" && isset($data['CODIGO'])){
            $productosCriteriosData = [
                'pro_codproducto'=>$data['CODIGO'], 'pro_plan'=>$data['PLANMAY'], 
                'pro_criteriomayor'=>$data['CRIMAY'], 'pro_grupodscto'=>$data['DSCTO'],
                'pro_tipoinv'=>$data['TIPOINV']
            ];

            BexProductoCriterio::create($productosCriteriosData);
            $this->info('Producto criterio '.$data['CODIGO'].' registrado exitosamente.');
        }

        if($table == "PROMO_DSCTOS_LINEA" && isset($data['idcia'])){
            $promoDsctoLineaData = [
                'idcia'=>$data['idcia'], 'rowid'=>$data['rowid'], 'descripcion'=>$data['descripcio'],
                'estado'=>$data['estado'], 'estado1'=>$data['estado1'], 'fini'=>$data['fini'], 'ffin'=>$data['ffin'],
                'co'=>$data['co'], 'codproducto'=>$data['codproduct'], 'porcdcto'=>$data['porcdcto'],
                'tipoinv'=>$data['tipoinv'], 'grupodctoitem'=>$data['grupodctoi'], 'nitcliente'=>$data['nitcliente'],
                'succliente'=>$data['succli'], 'puntoenvio'=>$data['puntoenvio'], 'tipocli'=>$data['tipocli'],
                'grupodctocli'=>$data['grupodctoc'], 'condpago'>$data['conpago'], 'listaprecios'=>$data['listapreci'],
                'planitem1'=>$data['planitem1'], 'criteriomayoritem1'=>$data['crimayite1'], 'planitem2'=>$data['planitem2'],
                'criteriomayorcli2'=>$data['crimayite2'], 'plancli1'=>$data['plancli1'], 'criteriomayorcli1'>$data['crimaycli1'],
                'plancli2'=>$data['plancli2'], 'criteriomayorcli2'=>$data['crimaycli2'], 'codigoobsequi'=>$data['codobsequi'],
                'motivoobsequio'=>$data['motobsequi'], 'umobsequio'=>$data['umobsequio'], 'cantobsequio'=>$data['cantobsequ'],
                'cantbaseobsequio'=>$data['cantbaseob'], 'indmaxmin'=>$data['indmaxmin'], 'cantmin'=>$data['cantmin'],
                'dctoval'=>$data['dctoval'], 'escalacomb'=>$data['escalacomb'], 'contmaxmin'=>$data['contmaxmin'],
                'plancomb'=>$data['plancomb'], 'prepack'=>'//', 'valor_min'=>'//', 'valor_max'=>'//'
            ];

            BexPromoDsctoTope::create($promoDsctoLineaData);
            $this->info('Promo descuento tope '.$data['idcia'].' registrado exitosamente.');
        }
       
        if($table == "PROMO_DSCTOS_TOPE-" && isset($data['idcia'])){
            $promoDsctoTopeData = [
                'idcia'=>$data['idcia'], 'rowid'=>$data['rowid'], 'estado'=>$data['estado'], 'estado1'=>$data['estado1'],
                'fini'=>$data['fini'], 'ffin'=>$data['ffin'], 'co'=>$data['co'], 'codproducto'=>$data['codproduct'],
                'porcdcto'=>$data['porcdcto'], 'tipoinv'=>$data['tipoinv'], 'grupodctoitem'=>$data['grupodctoi'],
                'nitcliente'=>$data['nitcliente'], 'succliente'=>$data['succli'], 'puntoenvio'=>$data['puntoenvio'],
                'tipocli'=>$data['tipocli'], 'grupodctocli'=>$data['grupodctoc'], 'condpago'=>$data['conpago'],
                'listaprecios'=>$data['listapreci'], 'planitem1'=>$data['planitem1'], 
                'criteriomayoritem1'=>$data['crimayite1'], 'planitem2'=>$data['planitem2'],
                'criteriomayorcli2'=>$data['crimayite2'], 'codigoobsequi'=>$data['codobsequi'],
                'motivoobsequio'=>$data['motobsequi'], 'umobsequio'=>$data['umobsequio'],
                'cantobsequio'=>$data['cantobsequ'], 'cantbaseobsequio'=>$data['cantbaseob'],
                'descripcion'=>$data['descripcio'], 'factor'=>$data['factor'], 'cupo'=>$data['cupo'],
                'dctoval'=>$data['dctoval'], 'x'=>'//'
            ];

            BexPromoDsctoTope::create($promoDsctoTopeData);
            $this->info('Promo descuento linea '.$data['idcia'].' registrado exitosamente.');
        }

        if($table == "ULTPED-"){
            //No se obtiene respuesta en el servidor
        }

        if($table == "VENDEDOR-" && isset($data['cia'])){{
            $vendedorData = [
                'compania'=>$data['cia'], 'tercvendedor'=>$data['nomsup'], 'nomvendedor'=>$data['nomvend'],
                'coddescuento'=>$data['coddes'], 'codportafolio'=>$data['portaf'], 'codsupervisor'=>$data['codsup'],
                'nitvendedor'=>$data['nitvend'], 'centroop'=>$data['centroop'], 'bodega'=>$data['bodega'],
                'tipodoc'=>$data['tipodoc'], 'cargue'=>$data['cargue'], 'codvendedor'=>$data['codvend']
            ];

            BexVendedor::create($vendedorData);
            $this->info('Compañia '.$data['cia'].' registrada exitosamente.');
        }}

        if($table == "VENTAS_UN-"){
            //Error de sintaxis en la cunsulta 
        }

        if($table == "CRITERIOS_CLIENTES" && isset($data['f206_id_plan'])){
            $criterioClienteData = [
                'cli_plancriterios'=>$data['f206_id_plan'], 'cli_criteriomayor'=>$data['f206_id'],
                'descripcion'=>$data['f206_descripcion']
            ];

            BexCriterioCliente::create($criterioClienteData);
            $this->info('Criterio Cliente'.$data['f206_id_plan'].' registrado exitosamente.');
        }

        if($table == "PLAN_CLIENTES" && isset($data['f204_id'])){
            $planClienteData = [
                'cli_plancriterios'=>$data['f204_id'], 'descripcion'=>$data['f204_descripcion']
            ];

            BexPlanCliente::create($planClienteData);
            $this->info('Plan cliente '.$data['f204_id'].' registrado exitosamente.');
        }
        
        if($table == "CRITERIOS_PRODUCTOS" && isset($data['f106_id_plan'])){
            $criterioProductoData = [
                'pro_plancriterios'=>$data['f106_id_plan'], 'pro_criteriomayor'=>$data['f106_id'],
                'descripcion'=>$data['f106_descripcion']
            ];

            BexCriterioProducto::create($criterioProductoData);
            $this->info('Criterio producto '.$data['f106_id_plan'].' registrado exitosamente.');
        }

        if($table == "PLAN_PRODUCTOS" && isset($data['f105_id'])){
            $planProductoData = [
                'pro_plancriterios'=>$data['f105_id'], 'descripcion'=>$data['f105_descripcion']
            ];

            BexPlanProducto::create($planProductoData);
            $this->info('Plan producto '.$data['f105_id'].' registrado exitosamente.');
        }

        if($table == "BODEGAS" && isset($data['codbod'])){
            $bodegaData = [
                'codigo'=>$data['codbod'], 'descripcion'=>$data['desbod']
            ];

            BexBodega::create($bodegaData);
            $this->info('Bodega '.$data['codbod'].' registrada exitosamente.');
        }

        if($table == "PROMO_DSCTOS_LINEA_COMBINADOS"){
            //No trae ningun resultado
        }

        if($table == "RUTEROS" && isset($data['tercvendedor'])){
            $ruteroData = [
                'tercvendedor'=>$data['tercvendedor'], 'dia'=>$data['dia'], 'dia_descrip'=>$data['dia_descrip'],
                'cliente'=>$data['cliente'], 'dv'=>$data['dv'], 'sucursal'=>$data['sucursal'],
                'secuencia'=>$data['secuencia'], 'inactivo'=>$data['inactivo']
            ];

            BexRutero::create($ruteroData);
            $this->info('Rutero '.$data['tercvendedor'].' registrado exitosamente.');
        }

        if($table == "REFERENCIAS_ALTERNAS" && isset($data['fecha'])){
            $referenciaAlternaData = [
                'fecha'=>$data['fecha'], 'cia'=>$data['cia'], 'item'=>$data['item'], 'referencia'=>$data['referencia']
            ];

            BexReferenciaAlterna::create($referenciaAlternaData);
            $this->info('Referencia alterna '.$data['referencia'].' registrada exitosamente.');
        }

        if($table == "PAISES-" && isset($data['codpais'])){
            $paisData = [
                'codpais'=>$data['codpais'], 'descripcion'=>$data['pais']
            ];

            BexPais::create($paisData);
            $this->info('Pais '.$data['codpais'].' registrado exitosamente.');
        }

        if($table == "DEPARTAMENTOS-" && isset($data['codpais'])){
            $departamentoData = [
                'codpais'=>$data['codpais'], 'coddpto'=>$data['coddpto'], 'descripcion'=>$data['dpto']
            ];

            BexDpto::create($departamentoData);
            $this->info('Departamento '.$data['coddpto'].' registrado exitosamente.');
        }

        if($table == "MUNICIPIOS" && isset($data['codpais'])){
            $municipioData = [
                'codpais'=>$data['codpais'], 'coddpto'=>$data['coddpto'], 'codmpio'=>$data['codmpio'],
                'descripcion'=>$data['mpio'], 'indicador'=>$data['indicativo']
            ];

            BexMpio::create($municipioData);
            $this->info('Municipio '.$data['codmpio'].' registrado exitosamente.');
        }
    
        if($table == "CODBARRAPRO" && isset($data['codbarra'])){
            //$jsonResult = json_encode($data, JSON_PRETTY_PRINT);
            //$this->info($jsonResult);
            $codBarraProData = [
                'codbar'=>$data['codbarra'], 'codproducto'=>$data['codproducto'], 'cant_asociada'=>$data['cantidad']
            ];

            BexCodBarraPro::create($codBarraProData);
            $this->info('Codigo de Barra Producto '.$data['codbarra'].' registrado exitosamente.');
        }

    }
}