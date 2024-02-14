<?php

namespace App\Models\Bex_0011;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T35BexUltped extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't35_bex_ultped';
protected $fillable = ['codempresa', 'tercvendedor', 'nitcliente', 'succliente', 'codproducto', 'cantidad', 'codcliente'];
public $timestamps = false;
}
