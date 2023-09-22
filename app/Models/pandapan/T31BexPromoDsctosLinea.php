<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T31BexPromoDsctosLinea extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't31_bex_promo_dsctos_linea';
protected $fillable = ['f31_idcia', 'f31_rowid', 'f31_descripcion', 'f31_estado', 'f31_estado1', 'f31_fini', 'f31_ffin', 'f31_co', 'f31_codproducto', 'f31_porcdcto', 'f31_tipoinv', 'f31_grupodctoitem', 'f31_nitcliente', 'f31_succliente', 'f31_puntoenvio', 'f31_tipocli', 'f31_grupodctocli', 'f31_condpago', 'f31_listaprecios', 'f31_planitem1', 'f31_criteriomayoritem1', 'f31_planitem2', 'f31_criteriomayoritem2', 'f31_plancli1', 'f31_criteriomayorcli1', 'f31_plancli2', 'f31_criteriomayorcli2', 'f31_codigoobsequi', 'f31_motivoobsequio', 'f31_umobsequio', 'f31_cantobsequio', 'f31_cantbaseobsequio', 'f31_indmaxmin', 'f31_cantmin', 'f31_cantmax', 'f31_dctoval', 'f31_escalacomb', 'f31_contmaxmin', 'f31_plancomb', 'f31_prepack', 'f31_valor_min', 'f31_valor_max'];
public $timestamps = false;
}
