<?php

namespace App\Models\Bex_0006;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T101BexObligaciones extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't101_bex_obligaciones';
protected $fillable = ['codempresa', 'codclientealt', 'nitcliente', 'nomcliente', 'telcliente1', 'telcliente2', 'numobligacion', 'tipocredito', 'fecfactura', 'fecven', 'diasmora', 'valtotcredito', 'valacobrar', 'valenmora', 'regional', 'codvendedor'];
public $timestamps = false;

public function scopeObligacionesBexTramite($query)
{
    return $query->join('t05_bex_clientes', function ($join) {
            $join->on('t101_bex_obligaciones.nitcliente', '=', 't05_bex_clientes.codigo')
                 ->on('t101_bex_obligaciones.regional', '=', 't05_bex_clientes.nomregion');
        })
        ->join('t18_bex_mpios', function ($join) {
            $join->on('t05_bex_clientes.codmpio', '=', 't18_bex_mpios.codmpio')
                 ->on('t05_bex_clientes.coddpto', '=', 't18_bex_mpios.coddpto');
        })
        ->join('t12_bex_dptos', 't05_bex_clientes.coddpto', '=', 't12_bex_dptos.coddpto')
        ->join('t36_bex_vendedores', 't101_bex_obligaciones.codvendedor', '=', 't36_bex_vendedores.tercvendedor')
        ->select(
            'codempresa', 'codclientealt', 'nitcliente', 'sucursal', 'razsoc', 'nomcliente', 'telcliente1', 'telcliente2', 'celular', 'direccion', 'barrio',
            't05_bex_clientes.codmpio', 't18_bex_mpios.descripcion AS nommpio', 't12_bex_dptos.descripcion AS nomdpto', 'email', 'numobligacion',
            'tipocredito', 'fecfactura', 'fecven', 'diasmora', 'valtotcredito', 'valacobrar', 'valenmora', 'regional', 't101_bex_obligaciones.codvendedor',
            'nomvendedor','cupo'
        );
}
}
