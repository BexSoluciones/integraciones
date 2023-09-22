<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T4BexCartera extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't4_bex_cartera';
protected $fillable = ['f4_cia', 'f4_tercvendedor', 'f4_nitcliente', 'f4_succliente', 'f4_codtipodoc', 'f4_documento', 'f4_fecmov', 'f4_fechavenci', 'f4_valor', 'f4_debcre', 'f4_recpro', 'f4_co_docto', 'f4_co_odc', 'f4_tipdoc_odc', 'f4_docto_odc', 'f4_planilla', 'f4_aux_cruce', 'f4_co_cruce', 'f4_un_cruce', 'f4_tipdoc_cruce', 'f4_numdoc_cruce', 'f4_codcliente', 'f4_estadotipodoc'];
public $timestamps = false;
}
