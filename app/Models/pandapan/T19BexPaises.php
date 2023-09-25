<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T19BexPaises extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't19_bex_paises';
protected $fillable = ['f19_codpais', 'f19_descripcion'];
public $timestamps = false;
}
