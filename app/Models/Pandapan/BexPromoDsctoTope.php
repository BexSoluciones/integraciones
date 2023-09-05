<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BexPromoDsctoTope extends Model {
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 'bex_promo_dsctos_tope';

    protected $fillable = [
        'idcia',
        'rowid',
        'estado',
        'estado1',
        'fini',
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
        'codigoobsequi',
        'motivoobsequio',
        'umobsequio',
        'cantobsequio',
        'cantbaseobsequio',
        'descripcion',
        'factor',
        'cupo',
        'dctoval',
        'x'
    ];
    public $timestamps = false;
}
