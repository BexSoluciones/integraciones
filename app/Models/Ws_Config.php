<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ws_Config extends Model
{
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 't01_ws_config';

    protected $fillable = [
        'id',
        'url', 
        'NombreConexion',
        'IdCia',
        'IdProveedor',
        'Usuario',
        'separador',
        'Clave',
        'AreaTrabajo',
        'estado',
        'usuariointerno',
        'claveinterno',
        'ipinterno',
        'IdConsulta',
        'ConecctionType',
        'proxy_host',
        'proxy_port',
        'urlEnvio',
        'urlEnvioDetalle'
    ];
}
