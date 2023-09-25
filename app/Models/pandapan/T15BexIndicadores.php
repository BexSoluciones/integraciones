<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T15BexIndicadores extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't15_bex_indicadores';
protected $fillable = ['f15_tercvendedor', 'f15_detmensaje'];
public $timestamps = false;
}
