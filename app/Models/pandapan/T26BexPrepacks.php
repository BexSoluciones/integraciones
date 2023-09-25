<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T26BexPrepacks extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't26_bex_prepacks';
protected $fillable = ['f26_codprepack', 'f26_codproducto', 'f26_cajas', 'f26_unidades', 'f26_nomprepack', 'f26_estado'];
public $timestamps = false;
}
