<?php

namespace App\Models\Bex_0002;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T16BexInventarios extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't16_bex_inventarios';
protected $fillable = ['bodega', 'estadobodega', 'estadoimpuesto', 'inventario', 'iva', 'producto'];
public $timestamps = false;
}
