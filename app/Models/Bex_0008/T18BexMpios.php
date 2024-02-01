<?php

namespace App\Models\Bex_0008;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T18BexMpios extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't18_bex_mpios';
protected $fillable = ['codpais', 'coddpto', 'codmpio', 'descripcion', 'indicador'];
public $timestamps = false;
}
