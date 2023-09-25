<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T25BexPrecios extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't25_bex_precios';
protected $fillable = ['f25_lista', 'f25_producto', 'f25_precio', 'f25_ico', 'f25_preciomin', 'f25_preciomax', 'f25_estadoprecio'];
public $timestamps = false;
}
