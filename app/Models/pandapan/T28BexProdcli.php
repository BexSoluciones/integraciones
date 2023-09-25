<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T28BexProdcli extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't28_bex_prodcli';
protected $fillable = ['f28_codempresa', 'f28_tercvendedor', 'f28_nitcliente', 'f28_succliente', 'f28_codproducto', 'f28_cantidad', 'f28_codcliente'];
public $timestamps = false;
}
