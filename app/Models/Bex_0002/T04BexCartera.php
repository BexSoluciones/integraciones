<?php

namespace App\Models\Bex_0002;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T04BexCartera extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't04_bex_cartera';
protected $fillable = ['cia', 'tercvendedor', 'nitcliente', 'succliente', 'codtipodoc', 'documento', 'fecmov', 'fechavenci', 'valor', 'debcre', 'recpro', 'co_docto', 'co_odc', 'tipdoc_odc', 'docto_odc', 'planilla', 'aux_cruce', 'co_cruce', 'un_cruce', 'tipdoc_cruce', 'numdoc_cruce', 'codcliente', 'estadotipodoc'];
public $timestamps = false;
}
