<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BexProdcli extends Model {
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 'bex_prodcli';

    protected $fillable = [
        'codempresa',
        'tercvendedor',
        'nitcliente',
        'succliente',
        'codproducto',
        'cantidad',
        'codcliente'
    ];
    public $timestamps = false;
}
