<?php

namespace App\Models\Bex_0002;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T02WsConsultas extends Model
{
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 't02_ws_consultas';
    protected $fillable = ['id', 'IdConsulta', 'parametro', 'tabla_destino', 'estado', 'descripcion', 'prioridad', 'desde', 'cuantos', 'sentencia'];
    public $timestamps = false;
}
