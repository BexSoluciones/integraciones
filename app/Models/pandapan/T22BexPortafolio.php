<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T22BexPortafolio extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't22_bex_portafolio';
protected $fillable = ['f22_codportafolio', 'f22_nomportafolio', 'f22_estadoportafolio'];
public $timestamps = false;
}