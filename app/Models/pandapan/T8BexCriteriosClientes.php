<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T8BexCriteriosClientes extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't8_bex_criterios_clientes';
protected $fillable = ['f8_cli_plancriterios', 'f8_cli_criteriomayor', 'f8_descripcion', 'f8_estado', 'f8_estado2'];
public $timestamps = false;
}
