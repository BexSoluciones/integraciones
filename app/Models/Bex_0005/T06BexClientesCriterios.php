<?php

namespace App\Models\Bex_0005;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T06BexClientesCriterios extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't06_bex_clientes_criterios';
protected $fillable = ['cli_nitcliente', 'cli_dvcliente', 'cli_succliente', 'cli_vendedor', 'cli_plancriterios', 'cli_criteriomayor', 'cli_grupodscto', 'cli_tipocli', 'codclientealt', 'codcliente'];
public $timestamps = false;
}
