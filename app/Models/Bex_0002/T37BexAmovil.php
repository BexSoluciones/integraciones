<?php

namespace App\Models\Bex_0002;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T37BexAmovil extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't37_bex_amovil';
protected $fillable = ['ano', 'codcliente', 'codvendedor', 'mes', 'nitcliente', 'succliente', 'tercvendedor', 'valor'];
public $timestamps = false;
}