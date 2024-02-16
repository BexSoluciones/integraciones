<?php

namespace App\Models\Bex_0010;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T42BexTalla extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't42_bex_talla';
protected $fillable = ['codtalla', 'nomtalla'];
public $timestamps = false;
}
