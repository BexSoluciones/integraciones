<?php

namespace App\Models\Bex_0007;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T16BexInventarios extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't16_bex_inventarios';
protected $fillable = ['bodega', 'iva', 'producto', 'inventario', 'estadoimpuesto', 'estadobodega'];
public $timestamps = false;
}
