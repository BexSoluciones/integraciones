<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BexPlanProducto extends Model {
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 'bex_plan_productos';

    protected $fillable = [
        'pro_plancriterios',
        'descripcion',
        'estado'
    ];
    public $timestamps = false;
}
