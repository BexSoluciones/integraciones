<?php

namespace App\Models\Bex_0009;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T24BexPortafoliop extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't24_bex_portafoliop';
protected $fillable = ['codportafolio', 'codprepack'];
public $timestamps = false;
}
