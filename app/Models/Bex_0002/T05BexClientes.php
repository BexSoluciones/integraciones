<?php

namespace App\Models\Bex_0002;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T05BexClientes extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't05_bex_clientes';
protected $fillable = ['consecutivo', 'codigo', 'dv', 'sucursal', 'razsoc', 'representante', 'direccion', 'telefono', 'precio', 'conpag', 'periodicidad', 'tercvendedor', 'cupo', 'nomconpag', 'barrio', 'tipocliente', 'cobraiva', 'codpais', 'coddpto', 'codmpio', 'codbarrio', 'email', 'consec', 'codcliente', 'estado', 'estadofpagovta'];
public $timestamps = false;
}
