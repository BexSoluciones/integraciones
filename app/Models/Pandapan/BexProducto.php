<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BexProducto extends Model {
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 'bex_productos';

    protected $fillable = [
        'plu',
        'descripcion',
        'codigo',
        'codunidademp',
        'nomunidademp',
        'factor',
        'codproveedor',
        'nomproveedor',
        'codbarra',
        'comb_009',
        'comb_010',
        'codmarca',
        'nommarca',
        'codunidadcaja',
        'detalle',
        'tipo_inv',
        'ccostos',
        'estado_unidademp',
        'estado',
        'estado_marca',
        'estadoproveedor'
    ];
    public $timestamps = false;
}
