<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BexMensaje extends Model {
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 'bex_mensajes';

    protected $fillable = [
        'codmensaje',
        'tipomensaje',
        'nommensaje'
    ];
    public $timestamps = false;
}
