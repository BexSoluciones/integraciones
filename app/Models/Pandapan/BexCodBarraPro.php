<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BexCodBarraPro extends Model {
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 'bex_codbarrapro';

    protected $fillable = [
        'codbar',
        'codproducto',
        'cant_asociada'
    ];
    public $timestamps = false;
}
