<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BexMpio extends Model {
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 'bex_mpios';

    protected $fillable = [
        'codpais',
        'coddpto',
        'codmpio',
        'descripcion',
        'indicador'
    ];
    public $timestamps = false;
}
