<?php

namespace App\Models\Bex_0002;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T33BexReferenciasAlternas extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't33_bex_referencias_alternas';
protected $fillable = ['fecha', 'cia', 'item', 'referencia'];
public $timestamps = false;
}
