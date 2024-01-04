<?php

namespace App\Models\Bex_0004;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T40BexDescuentosOfi extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't40_bex_descuentos_ofi';
protected $fillable = ['LISTA', 'CODPRODUCTO', 'CANT1', 'DESC1', 'CANT2', 'DESC2', 'CANT3', 'DESC3', 'CANT4', 'DESC4', 'CANT5', 'DESC5', 'DESC_TOPE'];
public $timestamps = false;
}
