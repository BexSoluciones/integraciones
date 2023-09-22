<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T13BexEstadopedidos extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't13_bex_estadopedidos';
protected $fillable = ['f13_codemp', 'f13_codvend', 'f13_tipoped', 'f13_numped', 'f13_nitcli', 'f13_succli', 'f13_fecped', 'f13_ordenped', 'f13_codpro', 'f13_refer', 'f13_descrip', 'f13_cantped', 'f13_vlrbruped', 'f13_ivabruped', 'f13_vlrnetoped', 'f13_cantfacped', 'f13_estado', 'f13_tipo', 'f13_tipofac', 'f13_factura', 'f13_ordenfac', 'f13_cantfac', 'f13_vlrbrufac', 'f13_ivabrufac', 'f13_vlrnetofac', 'f13_obsped', 'f13_undfacped', 'f13_otromotivo', 'f13_rowid', 'f13_bex_id', 'f13_codcliente', 'f13_codvendedor', 'f13_estadoenc'];
public $timestamps = false;
}
