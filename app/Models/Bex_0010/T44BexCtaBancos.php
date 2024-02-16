<?php

namespace App\Models\Bex_0010;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T44BexCtaBancos extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't44_bex_ctabancos';
protected $fillable = ['ctabanco', 'ctanombanco','codbanco'];
public $timestamps = false;
}
