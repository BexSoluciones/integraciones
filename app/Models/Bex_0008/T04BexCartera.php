<?php

namespace App\Models\Bex_0008;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T04BexCartera extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't04_bex_cartera';
protected $fillable = ['nitcliente', 'dv', 'succliente', 'codtipodoc', 'documento', 'fecmov', 'fechavenci', 'vrpostf', 'valor', 'codvendedor', 'diasmora', 'codcliente', 'debcre', 'recpro', 'co_docto', 'co_odc', 'tipdoc_odc', 'docto_odc', 'planilla', 'aux_cruce', 'co_cruce', 'un_cruce', 'tipdoc_cruce', 'numdoc_cruce', 'estadotipodoc'];
public $timestamps = false;

public function scopeCarteraBexTramite($query){
    return $query->join('t05_bex_clientes', function ($join) {
        $join->on('t04_bex_cartera.nitcliente', '=', 't05_bex_clientes.codigo')
             ->on('t04_bex_cartera.succliente', '=', 't05_bex_clientes.sucursal');
            })
        ->join('t36_bex_vendedores', 't04_bex_cartera.codvendedor', '=', 't36_bex_vendedores.tercvendedor')
        ->select('compania','nitcliente','succliente','codtipodoc','documento','fecmov','fechavenci','vrpostf','valor','diasmora','t04_bex_cartera.codcliente',
        'idregion','cupo','t36_bex_vendedores.tercvendedor','nomvendedor');
}
}
