<?php

namespace App\Models\Bex_0011;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T31BexPromoDsctosLinea extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't31_bex_promo_dsctos_linea';
protected $fillable = ['idcia', 'rowid', 'descripcion', 'estado', 'estado1', 'fini', 'ffin', 'co', 'codproducto', 'porcdcto', 'tipoinv', 'grupodctoitem', 'nitcliente', 'succliente', 'puntoenvio', 'tipocli', 'grupodctocli', 'condpago', 'listaprecios', 'planitem1', 'criteriomayoritem1', 'planitem2', 'criteriomayoritem2', 'plancli1', 'criteriomayorcli1', 'plancli2', 'criteriomayorcli2', 'codigoobsequi', 'motivoobsequio', 'umobsequio', 'cantobsequio', 'cantbaseobsequio', 'indmaxmin', 'cantmin', 'cantmax', 'dctoval', 'escalacomb', 'contmaxmin', 'plancomb', 'prepack', 'valor_min', 'valor_max'];
public $timestamps = false;
}
