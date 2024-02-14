<?php

namespace App\Models\Bex_0011;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T34BexRuteros extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't34_bex_ruteros';
protected $fillable = ['tercvendedor', 'dia', 'dia_descrip', 'cliente', 'dv', 'sucursal', 'secuencia', 'inactivo', 'estadodiarutero'];
public $timestamps = false;
}
