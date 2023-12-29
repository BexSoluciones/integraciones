<?php

namespace App\Models\Bex_0003;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T12BexDptos extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't12_bex_dptos';
protected $fillable = ['codpais', 'coddpto', 'descripcion'];
public $timestamps = false;
}
