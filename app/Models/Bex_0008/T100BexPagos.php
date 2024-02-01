<?php

namespace App\Models\Bex_0008;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T100BexPagos extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't100_bex_pagos';
protected $fillable = ['codclientealt', 'succliente', 'tipocredito', 'numobligacion', 'fecpago', 'valpago', 'codcliente', 'codobligacion'];
public $timestamps = false;
}
