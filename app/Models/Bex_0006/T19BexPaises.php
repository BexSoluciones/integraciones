<?php

namespace App\Models\Bex_0006;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T19BexPaises extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't19_bex_paises';
protected $fillable = ['codpais', 'descripcion'];
public $timestamps = false;
}
