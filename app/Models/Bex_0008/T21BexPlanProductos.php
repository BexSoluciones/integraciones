<?php

namespace App\Models\Bex_0008;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T21BexPlanProductos extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't21_bex_plan_productos';
protected $fillable = ['pro_plancriterios', 'descripcion', 'estado'];
public $timestamps = false;
}
