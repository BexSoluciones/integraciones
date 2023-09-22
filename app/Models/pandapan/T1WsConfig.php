<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T1WsConfig extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't1_ws_config';
protected $fillable = ['f1_id', 'f1_url', 'f1_NombreConexion', 'f1_IdCia', 'f1_IdProveedor', 'f1_Usuario', 'f1_separador', 'f1_Clave', 'f1_AreaTrabajo', 'f1_estado', 'f1_usuariointerno', 'f1_claveinterno', 'f1_ipinterno', 'f1_IdConsulta', 'f1_proxy_host', 'f1_proxy_port'];
public $timestamps = false;
}
