<?php

namespace App\Models\Bex_0008;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T26BexPrepacks extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't26_bex_prepacks';
protected $fillable = ['codprepack', 'codproducto', 'cajas', 'unidades', 'nomprepack', 'estado'];
public $timestamps = false;
}
