<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T11BexDctosProducto extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't11_bex_dctos_producto';
protected $fillable = ['f11_codgrupodcto', 'f11_codproducto', 'f11_descuento', 'f11_estado'];
public $timestamps = false;
}
