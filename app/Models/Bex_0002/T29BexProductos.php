<?php

namespace App\Models\Bex_0002;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T29BexProductos extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't29_bex_productos';
protected $fillable = ['codigo', 'codindadventa', 'codproveedor', 'codunidademp', 'descripcion', 'estado', 'estado_unidademp', 'estadoproveedor', 'nomproveedor', 'peso', 'unidadventa'];
public $timestamps = false;
}
