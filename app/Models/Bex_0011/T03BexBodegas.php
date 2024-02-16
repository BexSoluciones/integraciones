<?php

namespace App\Models\Bex_0011;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T03BexBodegas extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't03_bex_bodegas';
protected $fillable = ['codigo', 'descripcion', 'estadobodega'];
public $timestamps = false;
}
