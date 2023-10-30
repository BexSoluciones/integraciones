<?php

namespace App\Models\Bex_0002;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T02WsConsultas extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't02_ws_consultas';
protected $fillable = ['cuantos', 'descripcion', 'desde', 'estado', 'id', 'IdConsulta', 'parametro', 'prioridad', 'sentencia', 'tabla_destino'];
public $timestamps = false;
}
