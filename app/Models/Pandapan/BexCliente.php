<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BexCliente extends Model {
    
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 'bex_clientes';

    protected $fillable = [
        'consecutivo',
        'codigo', 
        'dv',
        'sucursal',
        'razsoc',
        'representante',
        'direccion',
        'telefono',
        'precio',
        'conpag',
        'periodicidad',
        'tercvendedor',
        'cupo',
        'nomconpag',
        'barrio',
        'tipocliente',
        'cobraiva',
        'codpais',
        'coddpto',
        'codmpio',
        'codbarrio',
        'consec',
        'codcliente',
        'estado',
        'estadofpagovta'
    ];
    public $timestamps = false;
}
