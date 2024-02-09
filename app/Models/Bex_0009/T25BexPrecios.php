<?php

namespace App\Models\Bex_0009;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T25BexPrecios extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't25_bex_precios';
protected $fillable = ['lista', 'producto', 'precio', 'ico', 'estadoprecio'];
public $timestamps = false;
}
