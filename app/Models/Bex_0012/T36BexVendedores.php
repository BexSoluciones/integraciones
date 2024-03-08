<?php

namespace App\Models\Bex_0012;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T36BexVendedores extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't36_bex_vendedores';
protected $fillable = ['compania', 'tercvendedor', 'centroop', 'tipodoc', 'estado'];
public $timestamps = false;
}
