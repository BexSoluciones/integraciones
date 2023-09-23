<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BexInactivo extends Model {
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 'bex_inactivos';

    protected $fillable = [
        'centro_ope',
        'producto'
    ];
    public $timestamps = false;
}
