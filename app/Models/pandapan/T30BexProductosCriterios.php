<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T30BexProductosCriterios extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't30_bex_productos_criterios';
protected $fillable = ['f30_pro_codproducto', 'f30_pro_plan', 'f30_pro_criteriomayor', 'f30_pro_grupodscto', 'f30_pro_tipoinv'];
public $timestamps = false;
}
