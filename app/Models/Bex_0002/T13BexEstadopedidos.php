<?php

namespace App\Models\Bex_0002;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T13BexEstadopedidos extends Model
{
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 't13_bex_estadopedidos';
    protected $fillable = ['codemp', 'codvend', 'tipoped', 'numped', 'nitcli', 'succli', 'fecped', 'ordenped', 'codpro', 'refer', 'descrip', 'cantped', 'vlrbruped', 'ivabruped', 'vlrnetoped', 'cantfacped', 'estado', 'tipo', 'tipofac', 'factura', 'ordenfac', 'cantfac', 'vlrbrufac', 'ivabrufac', 'vlrnetofac', 'obsped', 'ws_id', 'codcliente', 'rowid', 'bex_id', 'codvendedor', 'estadoenc'];
    public $timestamps = false;
}
