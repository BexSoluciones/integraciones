<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T7BexCodbarrapro extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't7_bex_codbarrapro';
protected $fillable = ['f7_codbar', 'f7_codproducto', 'f7_cant_asociada'];
public $timestamps = false;
}
