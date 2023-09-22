<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T6BexClientesCriterios extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't6_bex_clientes_criterios';
protected $fillable = ['f6_cli_nitcliente', 'f6_cli_dvcliente', 'f6_cli_succliente', 'f6_cli_vendedor', 'f6_cli_plancriterios', 'f6_cli_criteriomayor', 'f6_cli_grupodscto', 'f6_cli_tipocli', 'f6_codclientealt', 'f6_codcliente'];
public $timestamps = false;
}
