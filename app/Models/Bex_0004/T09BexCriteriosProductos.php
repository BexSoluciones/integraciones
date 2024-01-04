<?php

namespace App\Models\Bex_0004;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T09BexCriteriosProductos extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't09_bex_criterios_productos';
protected $fillable = ['pro_plancriterios', 'pro_criteriomayor', 'descripcion', 'estado'];
public $timestamps = false;
}
