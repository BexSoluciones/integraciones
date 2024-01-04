<?php

namespace App\Models\Bex_0004;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T07BexCodbarrapro extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't07_bex_codbarrapro';
protected $fillable = ['codbar', 'codproducto', 'cant_asociada'];
public $timestamps = false;
}
