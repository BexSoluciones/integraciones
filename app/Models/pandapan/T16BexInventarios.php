<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T16BexInventarios extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't16_bex_inventarios';
protected $fillable = ['f16_bodega', 'f16_iva', 'f16_producto', 'f16_inventario', 'f16_estadoimpuesto', 'f16_estadobodega'];
public $timestamps = false;
}
