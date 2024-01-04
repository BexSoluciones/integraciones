<?php

namespace App\Models\Bex_0004;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T14BexInactivos extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't14_bex_inactivos';
protected $fillable = ['centro_ope', 'producto'];
public $timestamps = false;
}
