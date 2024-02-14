<?php

namespace App\Models\Bex_0011;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T38BexEntregas extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't38_bex_entregas';
protected $fillable = ['id_entregas', 'tipopedido', 'numpedido', 'numentrega', 'placa', 'conductor', 'fecsalida', 'fecentrega', 'UnidadOperativa'];
public $timestamps = false;
}
