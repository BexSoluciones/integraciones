<?php

namespace App\Models\Bex_0006;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T01WsConfig extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't01_ws_config';
protected $fillable = ['id', 'url', 'NombreConexion', 'IdCia', 'IdProveedor', 'Usuario', 'separador', 'Clave', 'AreaTrabajo', 'estado', 'usuariointerno', 'claveinterno', 'ipinterno', 'IdConsulta', 'ConecctionType', 'proxy_host', 'proxy_port'];
public $timestamps = false;
}
