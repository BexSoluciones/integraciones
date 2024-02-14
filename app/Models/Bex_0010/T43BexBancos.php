<?php

namespace App\Models\Bex_0010;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T43BexBancos extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't43_bex_bancos';
protected $fillable = ['codbanco', 'nombanco'];
public $timestamps = false;
}
