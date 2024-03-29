<?php

namespace App\Models\Bex_0004;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T05BexClientes extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't05_bex_clientes';
protected $fillable = ['consecutivo', 'codigo', 'dv', 'sucursal', 'razsoc', 'representante', 'direccion', 'telefono', 'precio', 'conpag', 'periodicidad', 'tercvendedor', 'cupo', 'codgrupodcto', 'email', 'barrio', 'direccion2', 'codcliente', 'tipocliente', 'cobraiva', 'codpais', 'coddpto', 'codmpio', 'codbarrio', 'consec', 'estado', 'estadofpagovta'];
public $timestamps = false;

public function scopeGetColumns(){
    return 13;
}

public function scopeCodPago($query){
    return $query->select('conpag','periodicidad')
        ->where('estadofpagovta','=','A')
        ->where('codigo','!=','')
        ->groupBy('conpag','periodicidad');
}
}
