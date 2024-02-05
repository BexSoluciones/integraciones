<?php

namespace App\Models\Bex_0007;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T29BexProductos extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't29_bex_productos';
protected $fillable = ['codigo', 'descripcion', 'codunidademp', 'peso', 'codproveedor', 'nomproveedor', 'unidadventa', 'codindadventa', 'estado', 'estado_unidademp', 'estadoproveedor'];
public $timestamps = false;
}
