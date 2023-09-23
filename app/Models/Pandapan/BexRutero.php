<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BexRutero extends Model {
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 'bex_ruteros';

    protected $fillable = [
        'tercvendedor',
        'dia',
        'dia_descrip',
        'cliente',
        'dv',
        'sucursal',
        'secuencia',
        'inactivo',
        'estadodiarutero'
    ];
    public $timestamps = false;
}
