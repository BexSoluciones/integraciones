<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T18BexMpios extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't18_bex_mpios';
protected $fillable = ['f18_codpais', 'f18_coddpto', 'f18_codmpio', 'f18_descripcion', 'f18_indicador'];
public $timestamps = false;
}
