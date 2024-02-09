<?php

namespace App\Models\Bex_0009;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T101BexObligaciones extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't101_bex_obligaciones';
protected $fillable = ['codempresa', 'codclientealt', 'nitcliente', 'nomcliente', 'telcliente1', 'telcliente2', 'numobligacion', 'tipocredito', 'fecfactura', 'fecven', 'diasmora', 'valtotcredito', 'valacobrar', 'valenmora', 'regional', 'codvendedor'];
public $timestamps = false;
}
