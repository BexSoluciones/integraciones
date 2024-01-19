<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ws_Consulta extends Model
{
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 't02_ws_consultas';

    protected $fillable = [
        'id',
        'idConsulta', 
        'parametro',
        'tabla_destino',
        'estado',
        'descripcion',
        'prioridad',
        'desde',
        'cuantos',
        'sentencia'
    ];

    public static function getAll(){
        return static::all()->where('estado', 1);
    }
    public static function getAllBexTram(){
        return static::all()->where('desde', 1);
    }
}
