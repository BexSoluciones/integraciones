<?php

namespace App\Models\Bex_0002;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T17BexMensajes extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't17_bex_mensajes';
protected $fillable = ['codmensaje', 'nommensaje', 'tipomensaje'];
public $timestamps = false;
}
