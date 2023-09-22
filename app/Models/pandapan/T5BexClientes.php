<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T5BexClientes extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't5_bex_clientes';
protected $fillable = ['f5_consecutivo', 'f5_codigo', 'f5_dv', 'f5_sucursal', 'f5_razsoc', 'f5_representante', 'f5_direccion', 'f5_telefono', 'f5_precio', 'f5_conpag', 'f5_periodicidad', 'f5_tercvendedor', 'f5_cupo', 'f5_nomconpag', 'f5_barrio', 'f5_tipocliente', 'f5_cobraiva', 'f5_codpais', 'f5_coddpto', 'f5_codmpio', 'f5_codbarrio', 'f5_email', 'f5_consec', 'f5_codcliente', 'f5_estado', 'f5_estadofpagovta'];
public $timestamps = false;
}
