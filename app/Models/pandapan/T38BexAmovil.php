<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T38BexAmovil extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't38_bex_amovil';
protected $fillable = ['f38_nitcliente', 'f38_succliente', 'f38_ano', 'f38_mes', 'f38_valor', 'f38_tercvendedor', 'f38_codcliente', 'f38_codvendedor'];
public $timestamps = false;
}
