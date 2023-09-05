<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BexPrepacks extends Model {
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 'bex_prepacks';

    protected $fillable = [
        'codprepack',
        'codproducto',
        'cajas',
        'unidades',
        'nomprepack',
        'estado'
    ];
    public $timestamps = false;
}
