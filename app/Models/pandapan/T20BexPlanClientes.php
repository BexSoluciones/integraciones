<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T20BexPlanClientes extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't20_bex_plan_clientes';
protected $fillable = ['f20_cli_plancriterios', 'f20_descripcion', 'f20_estado'];
public $timestamps = false;
}
