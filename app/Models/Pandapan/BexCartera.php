<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BexCartera extends Model {
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 'bex_cartera';

    protected $fillable = [
        'cia',
        'tercvendedor', 
        'nitcliente',
        'succliente',
        'codtipodoc',
        'documento',
        'fecmov',
        'fechavenci',
        'valor',
        'debcre',
        'recpro',
        'co_docto',
        'co_odc',
        'tipodoc_odc',
        'docto_odc',
        'planilla',
        'aux_cruce',
        'co_cruce',
        'un_cruce',
        'tipodoc_cruce',
        'numdoc_cruce',
        'codcliente',
        'estadotipodoc'
    ];
    public $timestamps = false;
}
