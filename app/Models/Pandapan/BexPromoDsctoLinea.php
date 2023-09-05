<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BexPromoDsctoLinea extends Model {
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 'bex_promo_dsctos_linea';

    protected $fillable = [
        'idcia',
        'rowid',
        'descripcion',
        'estado',
        'estado1',
        'fini'
        'ffin',
        'co',
        'codproducto',
        'porcdcto',
        'tipoinv',
        'grupodctoitem',
        'nitcliente',
        'succliente',
        'puntoenvio',
        'tipocli',
        'grupodctocli',
        'condpago',
        'listaprecios',
        'planitem1',
        'criteriomayoritem1',
        'planitem2',
        'criteriomayorcli2',
        'plancli1',
        'criteriomayorcli1'
        'plancli2',
        'criteriomayorcli2',
        'codigoobsequi',
        'motivoobsequio',
        'umobsequio',
        'cantobsequio',
        'cantbaseobsequio',
        'indmaxmin',
        'cantmin',
        'dctoval',
        'escalacomb',
        'contmaxmin',
        'plancomb',
        'prepack',
        'valor_min',
        'valor_max'
    ];
    public $timestamps = false;
}
