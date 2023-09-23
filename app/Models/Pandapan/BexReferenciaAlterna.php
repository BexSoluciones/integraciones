<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BexReferenciaAlterna extends Model {
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 'bex_referencias_alternas';

    protected $fillable = [
        'fecha',
        'cia',
        'item',
        'referencia'
    ];
    public $timestamps = false;
}
