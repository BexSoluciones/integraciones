<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T35BexUltped extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't35_bex_ultped';
protected $fillable = ['f35_codempresa', 'f35_tercvendedor', 'f35_nitcliente', 'f35_succliente', 'f35_codproducto', 'f35_cantidad', 'f35_codcliente'];
public $timestamps = false;
}
