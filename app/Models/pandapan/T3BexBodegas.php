<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T3BexBodegas extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't3_bex_bodegas';
protected $fillable = ['f3_codigo', 'f3_descripcion', 'f3_estadobodega'];
public $timestamps = false;
}
