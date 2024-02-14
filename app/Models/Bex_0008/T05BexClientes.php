<?php

namespace App\Models\Bex_0008;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T05BexClientes extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't05_bex_clientes';
protected $fillable = ['consecutivo', 'codigo', 'dv', 'sucursal', 'razsoc', 'representante', 'direccion', 'telefono', 'precio', 'conpag', 'periodicidad', 'tercvendedor', 'cupo', 'nomconpag', 'barrio', 'tipocliente', 'cobraiva', 'codpais', 'coddpto', 'codmpio', 'codbarrio', 'consec', 'email', 'codcliente', 'estado', 'estadofpagovta'];
public $timestamps = false;

public function scopeCodPago($query){
    return $query->select('conpag','periodicidad')
        ->where('estadofpagovta','=','A')
        ->where('codigo','!=','')
        ->groupBy('conpag','periodicidad');
}

public function scopeClientBexTramite($query){
    return $query->join('t18_bex_mpios', function ($join) {
        $join->on('t05_bex_clientes.codmpio', '=', 't18_bex_mpios.codmpio')
             ->on('t05_bex_clientes.coddpto', '=', 't18_bex_mpios.coddpto');
    })
    ->join('t12_bex_dptos', 't05_bex_clientes.coddpto', '=', 't12_bex_dptos.coddpto')
    ->select(
        'codcliente', 'codigo', 'sucursal', 'razsoc', 'representante', 'telefono', 'celular', 'direccion', 't05_bex_clientes.codmpio',
        't18_bex_mpios.descripcion as municipios', 't12_bex_dptos.descripcion as departamento', 'email', 'tercvendedor', 'actcliente',
        'idregion', 'nomregion', 'idcanal', 'nomcanal'
    );
}
}
