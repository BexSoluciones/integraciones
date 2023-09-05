<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BexPortafolio extends Model {
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 'bex_portafolio';

    protected $fillable = [
        'codportafolio',
        'nomportafolio',
        'estadoportafolio'
    ];
    public $timestamps = false;
}
