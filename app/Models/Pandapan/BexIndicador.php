<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BexIndicador extends Model {
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 'bex_indicadores';

    protected $fillable = [
        'tercvendedor',
        'detmensaje'
    ];
    public $timestamps = false;
}
