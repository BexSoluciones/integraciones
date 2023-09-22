<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T33BexReferenciasAlternas extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't33_bex_referencias_alternas';
protected $fillable = ['f33_fecha', 'f33_cia', 'f33_item', 'f33_referencia'];
public $timestamps = false;
}
