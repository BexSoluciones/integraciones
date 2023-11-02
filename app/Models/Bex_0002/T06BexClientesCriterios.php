<?php

namespace App\Models\Bex_0002;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T06BexClientesCriterios extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't06_bex_clientes_criterios';
protected $fillable = ['cli_criteriomayor', 'cli_dvcliente', 'cli_grupodscto', 'cli_nitcliente', 'cli_plancriterios', 'cli_succliente', 'cli_tipocli', 'cli_vendedor', 'codcliente', 'codclientealt'];
public $timestamps = false;
}
