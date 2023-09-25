<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T14BexInactivos extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't14_bex_inactivos';
protected $fillable = ['f14_centro_ope', 'f14_producto'];
public $timestamps = false;
}
