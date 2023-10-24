<?php

namespace App\Models\Bex_0002;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T29BexProductos extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't29_bex_productos';
protected $fillable = ['plu', 'descripcion', 'codigo', 'codunidademp', 'nomunidademp', 'factor', 'codproveedor', 'nomproveedor', 'codbarra', 'comb_009', 'comb_010', 'codmarca', 'nommarca', 'codunidadcaja', 'detalle', 'tipo_inv', 'ccostos', 'estado_unidademp', 'estado', 'estado_marca', 'estadoproveedor'];
public $timestamps = false;
}
