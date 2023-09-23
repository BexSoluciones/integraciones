<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BexBodega extends Model {
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 'bex_bodegas';

    protected $fillable = [
        'codigo',
        'descripcion',
        'estadobodega'
    ];
    public $timestamps = false;
}
