<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BexCriterioCliente extends Model {
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 'bex_criterios_clientes';

    protected $fillable = [
        'cli_plancriterios',
        'cli_criteriomayor',
        'descripcion',
        'estado',
        'estado2'
    ];
    public $timestamps = false;
}
