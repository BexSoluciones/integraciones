<?php

namespace App\Models\Bex_0006;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T05BexClientes extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't05_bex_clientes';
protected $fillable = ['consecutivo', 'codigo', 'dv', 'sucursal', 'razsoc', 'representante', 'direccion', 'telefono', 'precio', 'conpag', 'periodicidad', 'tercvendedor', 'cupo', 'codgrupodcto', 'email', 'barrio', 'codcliente', 'tipocliente', 'cobraiva', 'codpais', 'coddpto', 'codmpio', 'codbarrio', 'celular', 'actcliente', 'idregion', 'nomregion', 'idcanal', 'nomcanal','bloqueo', 'infoCupoDisponible','estado', 'estadofpagovta'];
public $timestamps = false;

public function scopeCodPago($query){
    return $query->select('conpag','periodicidad')
        ->where('estadofpagovta','=','A')
        ->where('codigo','!=','')
        ->groupBy('conpag','periodicidad');
}

public function scopeClientBexTramite($query)
{
    return $query->join('t18_bex_mpios as mpios', function ($join) {
        $join->on('t05_bex_clientes.codmpio', '=', 'mpios.codmpio')
             ->on('t05_bex_clientes.coddpto', '=', 'mpios.coddpto');
    })
    ->join('t12_bex_dptos as dptos', 't05_bex_clientes.coddpto', '=', 'dptos.coddpto')
    ->join('t36_bex_vendedores as vendedores', 't05_bex_clientes.tercvendedor', '=', 'vendedores.tercvendedor')
    ->select([
        't05_bex_clientes.codcliente',
        't05_bex_clientes.codigo',
        't05_bex_clientes.sucursal',
        't05_bex_clientes.razsoc',
        't05_bex_clientes.representante',
        't05_bex_clientes.telefono',
        't05_bex_clientes.celular',
        't05_bex_clientes.direccion',
        't05_bex_clientes.codmpio',
        'mpios.descripcion as municipios',
        'dptos.descripcion as departamento',
        't05_bex_clientes.email',
        't05_bex_clientes.tercvendedor',
        'vendedores.nomvendedor as nomvendedor',
        't05_bex_clientes.actcliente',
        't05_bex_clientes.idregion',
        't05_bex_clientes.nomregion',
        't05_bex_clientes.idcanal',
        't05_bex_clientes.nomcanal'
    ]);
}

}
