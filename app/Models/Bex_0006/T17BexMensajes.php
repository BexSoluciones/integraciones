<?php

namespace App\Models\Bex_0006;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T17BexMensajes extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't17_bex_mensajes';
protected $fillable = ['codmensaje', 'tipomensaje', 'nommensaje'];
public $timestamps = false;
}
