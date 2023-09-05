<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BexPais extends Model {
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 'bex_paises';

    protected $fillable = [
        'codpais',
        'descripcion'
    ];
    public $timestamps = false;
}
