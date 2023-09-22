<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T32BexPromoDsctosTope extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't32_bex_promo_dsctos_tope';
protected $fillable = ['f32_idcia', 'f32_rowid', 'f32_estado', 'f32_estado1', 'f32_fini', 'f32_ffin', 'f32_co', 'f32_codproducto', 'f32_porcdcto', 'f32_tipoinv', 'f32_grupodctoitem', 'f32_nitcliente', 'f32_succliente', 'f32_puntoenvio', 'f32_tipocli', 'f32_grupodctocli', 'f32_condpago', 'f32_listaprecios', 'f32_planitem1', 'f32_criteriomayoritem1', 'f32_planitem2', 'f32_criteriomayoritem2', 'f32_plancli1', 'f32_criteriomayorcli1', 'f32_plancli2', 'f32_criteriomayorcli2', 'f32_codigoobsequi', 'f32_motivoobsequio', 'f32_umobsequio', 'f32_cantobsequio', 'f32_cantbaseobsequio', 'f32_descripcion', 'f32_factor', 'f32_cupo', 'f32_dctoval', 'f32_x'];
public $timestamps = false;
}
