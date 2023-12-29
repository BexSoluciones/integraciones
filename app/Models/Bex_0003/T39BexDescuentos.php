<?php

namespace App\Models\Bex_0003;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T39BexDescuentos extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't39_bex_descuentos';
protected $fillable = ['codgrupodcto', 'codproducto', 'dcto', 'estadogrupodcto'];
public $timestamps = false;
}
