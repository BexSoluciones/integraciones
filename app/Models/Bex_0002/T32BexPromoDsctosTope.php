<?php

namespace App\Models\Bex_0002;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T32BexPromoDsctosTope extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't32_bex_promo_dsctos_tope';
protected $fillable = ['cantbaseobsequio', 'cantobsequio', 'co', 'codigoobsequi', 'codproducto', 'condpago', 'criteriomayorcli1', 'criteriomayorcli2', 'criteriomayoritem1', 'criteriomayoritem2', 'cupo', 'dctoval', 'descripcion', 'estado', 'estado1', 'factor', 'ffin', 'fini', 'grupodctocli', 'grupodctoitem', 'idcia', 'listaprecios', 'motivoobsequio', 'nitcliente', 'plancli1', 'plancli2', 'planitem1', 'planitem2', 'porcdcto', 'puntoenvio', 'rowid', 'succliente', 'tipocli', 'tipoinv', 'umobsequio', 'x'];
public $timestamps = false;
}
