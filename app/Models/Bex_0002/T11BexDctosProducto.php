<?php

namespace App\Models\Bex_0002;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T11BexDctosProducto extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't11_bex_dctos_producto';
protected $fillable = ['codgrupodcto', 'codproducto', 'descuento', 'estado'];
public $timestamps = false;
}
