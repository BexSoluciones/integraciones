<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T36BexVendedores extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't36_bex_vendedores';
protected $fillable = ['f36_compania', 'f36_tercvendedor', 'f36_nomvendedor', 'f36_coddescuento', 'f36_codportafolio', 'f36_codsupervisor', 'f36_nomsupervisor', 'f36_nitvendedor', 'f36_centroop', 'f36_bodega', 'f36_tipodoc', 'f36_cargue', 'f36_estado', 'f36_estadosuperv', 'f36_codvendedor'];
public $timestamps = false;
}
