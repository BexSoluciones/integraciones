<?php

namespace App\Models\Bex_0008;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 'files';
protected $fillable = ['id', 'custom_migrations_id', 'name', 'stateBexMovil', 'stateBexTramites', 'requiredBexMovil', 'requiredBexTramites'];
public $timestamps = false;
}
