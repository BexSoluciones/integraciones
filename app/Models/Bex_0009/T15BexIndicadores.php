<?php

namespace App\Models\Bex_0009;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T15BexIndicadores extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't15_bex_indicadores';
protected $fillable = ['tercvendedor', 'detmensaje'];
public $timestamps = false;
}
