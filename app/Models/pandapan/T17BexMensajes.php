<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T17BexMensajes extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't17_bex_mensajes';
protected $fillable = ['f17_codmensaje', 'f17_tipomensaje', 'f17_nommensaje'];
public $timestamps = false;
}
