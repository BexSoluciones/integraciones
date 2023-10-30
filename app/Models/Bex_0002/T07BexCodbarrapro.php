<?php

namespace App\Models\Bex_0002;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T07BexCodbarrapro extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't07_bex_codbarrapro';
protected $fillable = ['cant_asociada', 'codbar', 'codproducto'];
public $timestamps = false;
}
