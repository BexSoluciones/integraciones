<?php

namespace App\Models\Bex_0002;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T31BexPromoDsctosLinea extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't31_bex_promo_dsctos_linea';
protected $fillable = ['cantbaseobsequio', 'cantmax', 'cantmin', 'cantobsequio', 'co', 'codigoobsequi', 'codproducto', 'condpago', 'contmaxmin', 'criteriomayorcli1', 'criteriomayorcli2', 'criteriomayoritem1', 'criteriomayoritem2', 'dctoval', 'descripcion', 'escalacomb', 'estado', 'estado1', 'ffin', 'fini', 'grupodctocli', 'grupodctoitem', 'idcia', 'indmaxmin', 'listaprecios', 'motivoobsequio', 'nitcliente', 'plancli1', 'plancli2', 'plancomb', 'planitem1', 'planitem2', 'porcdcto', 'prepack', 'puntoenvio', 'rowid', 'succliente', 'tipocli', 'tipoinv', 'umobsequio', 'valor_max', 'valor_min'];
public $timestamps = false;
}
