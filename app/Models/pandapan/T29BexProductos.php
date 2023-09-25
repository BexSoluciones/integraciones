<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T29BexProductos extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't29_bex_productos';
protected $fillable = ['f29_plu', 'f29_descripcion', 'f29_codigo', 'f29_codunidademp', 'f29_nomunidademp', 'f29_factor', 'f29_codproveedor', 'f29_nomproveedor', 'f29_codbarra', 'f29_comb_009', 'f29_comb_010', 'f29_codmarca', 'f29_nommarca', 'f29_codunidadcaja', 'f29_detalle', 'f29_tipo_inv', 'f29_ccostos', 'f29_estado_unidademp', 'f29_estado', 'f29_estado_marca', 'f29_estadoproveedor'];
public $timestamps = false;
}
