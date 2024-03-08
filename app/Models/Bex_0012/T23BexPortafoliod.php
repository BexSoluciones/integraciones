<?php

namespace App\Models\Bex_0012;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T23BexPortafoliod extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't23_bex_portafoliod';
protected $fillable = ['codproducto', 'codportafolio'];
public $timestamps = false;
}
