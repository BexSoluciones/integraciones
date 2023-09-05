<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BexProductoCriterio extends Model {
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 'bex_productos_criterios';

    protected $fillable = [
        'pro_codproducto',
        'pro_plan',
        'pro_criteriomayor',
        'pro_grupodscto',
        'pro_tipoinv'
    ];
    public $timestamps = false;
}
