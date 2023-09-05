<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BexInventario extends Model {
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 'bex_inventarios';

    protected $fillable = [
        'bodega',
        'iva',
        'producto',
        'inventario',
        'estadoinpuesto',
        'estadobodega'
    ];
    public $timestamps = false;
}
