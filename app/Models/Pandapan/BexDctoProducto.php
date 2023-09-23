<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BexDctoProducto extends Model {
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 'bex_dctos_producto';

    protected $fillable = [
        'codgrupodcto',
        'codproducto', 
        'descuento',
        'estado'
    ];
    public $timestamps = false;
}
