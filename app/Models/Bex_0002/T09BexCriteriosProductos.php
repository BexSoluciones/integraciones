<?php

namespace App\Models\Bex_0002;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T09BexCriteriosProductos extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't09_bex_criterios_productos';
protected $fillable = ['descripcion', 'estado', 'pro_criteriomayor', 'pro_plancriterios'];
public $timestamps = false;
}
