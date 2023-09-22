<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T2WsConsultas extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't2_ws_consultas';
protected $fillable = ['f2_id', 'f2_IdConsulta', 'f2_parametro', 'f2_tabla_destino', 'f2_estado', 'f2_descripcion', 'f2_prioridad', 'f2_desde', 'f2_cuantos', 'f2_sentencia'];
public $timestamps = false;
}
