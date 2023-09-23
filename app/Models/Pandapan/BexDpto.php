<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BexDpto extends Model {
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 'bex_dptos';

    protected $fillable = [
        'codpais',
        'coddpto',
        'descripcion'
    ];
    public $timestamps = false;
}
