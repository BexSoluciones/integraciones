<?php

namespace App\Models\Bex_0002;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T30BexProductosCriterios extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't30_bex_productos_criterios';
protected $fillable = ['pro_codproducto', 'pro_plan', 'pro_criteriomayor', 'pro_grupodscto', 'pro_tipoinv'];
public $timestamps = false;
}
