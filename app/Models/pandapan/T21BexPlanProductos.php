<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T21BexPlanProductos extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't21_bex_plan_productos';
protected $fillable = ['f21_pro_plancriterios', 'f21_descripcion', 'f21_estado'];
public $timestamps = false;
}
