<?php

namespace App\Models\Bex_0002;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T04BexCartera extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't04_bex_cartera';
protected $fillable = ['nitcliente', 'dv', 'succliente', 'codtipodoc', 'documento', 'fecmov', 'fechavenci', 'valor', 'codvendedor', 'codcliente', 'debcre', 'tipdoc_odc', 'aux_cruce', 'co_cruce', 'co_docto', 'co_odc', 'docto_odc', 'estadotipodoc', 'numdoc_cruce', 'planilla', 'recpro', 'tipdoc_cruce', 'un_cruce'];
public $timestamps = false;
}
