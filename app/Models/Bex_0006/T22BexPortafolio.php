<?php

namespace App\Models\Bex_0006;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T22BexPortafolio extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't22_bex_portafolio';
protected $fillable = ['codportafolio', 'nomportafolio', 'estadoportafolio'];
public $timestamps = false;
}
