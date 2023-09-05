<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BexPrecio extends Model {
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 'bex_precios';

    protected $fillable = [
        'lista',
        'producto',
        'precio',
        'ico',
        'preciomin',
        'preciomax',
        'estadoprecio'
    ];
    public $timestamps = false;
}
