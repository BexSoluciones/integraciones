<?php

namespace App\Models\Bex_0002;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T01WsConfig extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't01_ws_config';
protected $fillable = ['AreaTrabajo', 'Clave', 'claveinterno', 'ConecctionType', 'estado', 'id', 'IdCia', 'IdConsulta', 'IdProveedor', 'ipinterno', 'NombreConexion', 'proxy_host', 'proxy_port', 'separador', 'url', 'Usuario', 'usuariointerno'];
public $timestamps = false;
}
