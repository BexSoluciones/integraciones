<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $connection = 'dynamic_connection';
    protected $table="tbldmovdet";
    protected $fillable = [
        'CODMOVDET',
        'CODMOVENC',
        'CODEMPRESA',
        'CODTIPODOC',
        'PREFMOV',
        'NUMMOV',
        'CODBODEGA',
        'CODPRODUCTO',
        'CANTIDADMOV',
        'PRECIOMOV',
        'IVAMOV',
        'CODMOTDEV',
        'BONENTREGAPRODUCTO',
        'DCTO1MOV',
        'DCTO2MOV',
        'DCTO3MOV',
        'DCTO4MOV',
        'motentrega',
        'javaid',
        'DCTONC',
        'DCTOPIEFACAUT',
        'FACTOR',
        'BONIFICADO',
        'PREPACK',
        'AUTORIZACION',
        'CANTID1',
        'CANTID2',
        'UNIDAD01',
        'UNIDAD02',
        'OBSEQUIO1',
        'OBSEQUIO2',
        'IDLISPRE',
        'dctovalor',
        'autovalor',
        'ocultorowid',
        'ocultoporcval',
        'rowid',
        'cantidadpines' ,
        'codmotpines',
        'impconsumo',
        'lote',
        'peso',
        'fletesimple',       
    ];
    public $timestamps=false;

    public function producto(){
        
    }

    public function obtenerDetallePedido($numeroPedido,$centroOperacion,$tipoDocumento,$bodega){

        $sql="select * from ".$this->table." where numero_pedido='".$numeroPedido."' AND centro_operacion='".$centroOperacion."' AND tipo_documento='".$tipoDocumento."' AND bodega='".$bodega."'";          
        // $resultadoSql = DB::select($sql);
        // return json_decode(json_encode($resultadoSql),true);
    }
}
