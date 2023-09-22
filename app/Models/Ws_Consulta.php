<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ws_Consulta extends Model
{
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $primaryKey = 'f2_id';
    protected $table = 't2_ws_consultas';

    protected $fillable = [
        'f2_id',
        'f2_idConsulta', 
        'f2_parametro',
        'f2_tabla_destino',
        'f2_estado',
        'f2_descripcion',
        'f2_prioridad',
        'f2_desde',
        'f2_cuantos',
        'f2_sentencia'
    ];

    public static function getAll(){
        return static::all()->where('f2_estado', 1);
    }
}
