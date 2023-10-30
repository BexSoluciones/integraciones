<?php

namespace App\Models\Bex_0002;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T34BexRuteros extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't34_bex_ruteros';
protected $fillable = ['cliente', 'dia', 'dia_descrip', 'dv', 'estadodiarutero', 'inactivo', 'secuencia', 'sucursal', 'tercvendedor'];
public $timestamps = false;
}
