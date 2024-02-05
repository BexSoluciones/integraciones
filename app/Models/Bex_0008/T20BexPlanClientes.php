<?php

namespace App\Models\Bex_0008;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T20BexPlanClientes extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't20_bex_plan_clientes';
protected $fillable = ['cli_plancriterios', 'descripcion', 'estado'];
public $timestamps = false;
}
