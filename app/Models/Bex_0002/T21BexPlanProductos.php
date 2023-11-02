<?php

namespace App\Models\Bex_0002;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T21BexPlanProductos extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't21_bex_plan_productos';
protected $fillable = ['descripcion', 'estado', 'pro_plancriterios'];
public $timestamps = false;
}
