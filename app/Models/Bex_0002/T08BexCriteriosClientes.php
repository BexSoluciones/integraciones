<?php

namespace App\Models\Bex_0002;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T08BexCriteriosClientes extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't08_bex_criterios_clientes';
protected $fillable = ['cli_criteriomayor', 'cli_plancriterios', 'descripcion', 'estado', 'estado2'];
public $timestamps = false;
}
