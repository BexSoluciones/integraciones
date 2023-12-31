<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ws_Unoee_Config extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 'ws_unoee_config';
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
    'proxy_port'
];
public static function getConnectionId($idConnection){
    return static::find($idConnection);
}
}
