<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BexVendedor extends Model {
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 'bex_vendedores';

    protected $fillable = [
        'compania',
        'tercvendedor',
        'nomvendedor',
        'coddescuento',
        'codportafolio',
        'codsupervisor',
        'nitvendedor',
        'centroop',
        'bodega',
        'tipodoc',
        'cargue',
        'estado',
        'estadosuperv',
        'codvendedor'
    ];
    public $timestamps = false;
}
