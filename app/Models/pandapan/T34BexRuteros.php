<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T34BexRuteros extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't34_bex_ruteros';
protected $fillable = ['f34_tercvendedor', 'f34_dia', 'f34_dia_descrip', 'f34_cliente', 'f34_dv', 'f34_sucursal', 'f34_secuencia', 'f34_inactivo', 'f34_estadodiarutero'];
public $timestamps = false;
}
