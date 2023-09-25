<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T24BexPortafoliop extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't24_bex_portafoliop';
protected $fillable = ['f24_codportafolio', 'f24_codprepack'];
public $timestamps = false;
}
