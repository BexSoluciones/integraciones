<?php

namespace App\Models\Bex_0002;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T36BexVendedores extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't36_bex_vendedores';
protected $fillable = ['compania', 'tercvendedor', 'nomvendedor', 'coddescuento', 'codportafolio', 'codsupervisor', 'nomsupervisor', 'nitvendedor', 'centroop', 'bodega', 'tipodoc', 'cargue', 'estado', 'estadosuperv', 'codvendedor'];
public $timestamps = false;
}
