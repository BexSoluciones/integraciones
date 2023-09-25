<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T27BexPresupuestos extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't27_bex_presupuestos';
protected $fillable = ['f27_tercvendedor', 'f27_fecpptovendia', 'f27_precio'];
public $timestamps = false;
}
