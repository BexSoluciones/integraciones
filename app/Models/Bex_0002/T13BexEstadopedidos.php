<?php

namespace App\Models\Bex_0002;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T13BexEstadopedidos extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't13_bex_estadopedidos';
protected $fillable = ['bex_id', 'cantfac', 'cantfacped', 'cantped', 'codcliente', 'codemp', 'codpro', 'codvend', 'codvendedor', 'descrip', 'estado', 'estadoenc', 'factura', 'fecped', 'ivabrufac', 'ivabruped', 'nitcli', 'numped', 'obsped', 'ordenfac', 'ordenped', 'refer', 'rowid', 'succli', 'tipo', 'tipofac', 'tipoped', 'vlrbrufac', 'vlrbruped', 'vlrnetofac', 'vlrnetoped', 'ws_id'];
public $timestamps = false;
}
