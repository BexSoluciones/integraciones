<?php

namespace App\Models\Bex_0002;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T27BexPresupuestos extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't27_bex_presupuestos';
protected $fillable = ['fecpptovendia', 'precio', 'tercvendedor'];
public $timestamps = false;
}
