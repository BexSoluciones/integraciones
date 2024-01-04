<?php

namespace App\Models\Bex_0004;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T37BexAmovil extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't37_bex_amovil';
protected $fillable = ['nitcliente', 'succliente', 'ano', 'mes', 'valor', 'tercvendedor', 'codcliente', 'codvendedor'];
public $timestamps = false;
}
