<?php

namespace App\Models\Bex_0002;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T35BexUltped extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't35_bex_ultped';
protected $fillable = ['cantidad', 'codcliente', 'codempresa', 'codproducto', 'nitcliente', 'succliente', 'tercvendedor'];
public $timestamps = false;
}
