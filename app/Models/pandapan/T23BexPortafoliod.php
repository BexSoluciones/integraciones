<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T23BexPortafoliod extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't23_bex_portafoliod';
protected $fillable = ['f23_codproducto', 'f23_codportafolio'];
public $timestamps = false;
}
