<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BexPortafoliod extends Model {
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 'bex_portafoliod';

    protected $fillable = [
        'codproducto',
        'codportafolio'
    ];
    public $timestamps = false;
}
