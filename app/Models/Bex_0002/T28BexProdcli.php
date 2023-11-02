<?php

namespace App\Models\Bex_0002;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T28BexProdcli extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't28_bex_prodcli';
protected $fillable = ['codempresa', 'tercvendedor', 'nitcliente', 'succliente', 'codproducto', 'cantidad', 'codcliente'];
public $timestamps = false;
}
