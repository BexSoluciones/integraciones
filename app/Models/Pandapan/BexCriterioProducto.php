<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BexCriterioProducto extends Model {
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 'bex_criterios_productos';

    protected $fillable = [
        'pro_plancriterios',
        'pro_criteriomayor',
        'descripcion',
        'estado'
    ];
    public $timestamps = false;
}
