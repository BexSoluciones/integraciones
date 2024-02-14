<?php

namespace App\Models\Bex_0010;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T36BexVendedores extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't36_bex_vendedores';
protected $fillable = ['compania', 'tercvendedor', 'nomvendedor', 'coddescuento', 'codportafolio', 'codsupervisor', 'nomsupervisor', 'nitvendedor', 'centroop', 'bodega', 'tipodoc', 'cargue', 'estado', 'estadosuperv', 'codvendedor'];
public $timestamps = false;

public function scopeInsertDataToTblmsupervisor($query){
    return $query->select('codsupervisor', DB::raw('CONCAT("SUPERVISOR ", codsupervisor) as nomsupervisor'))
    ->where('estadosuperv', 'A')
    ->groupBy('codsupervisor');
}
}
