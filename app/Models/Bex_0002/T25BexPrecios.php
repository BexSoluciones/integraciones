<?php

namespace App\Models\Bex_0002;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T25BexPrecios extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't25_bex_precios';
protected $fillable = ['estadoprecio', 'lista', 'precio', 'producto'];
public $timestamps = false;
}