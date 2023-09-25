<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T9BexCriteriosProductos extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't9_bex_criterios_productos';
protected $fillable = ['f9_pro_plancriterios', 'f9_pro_criteriomayor', 'f9_descripcion', 'f9_estado'];
public $timestamps = false;
}
