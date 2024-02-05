<?php

namespace App\Models\Bex_0008;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T41BexColor extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't41_bex_color';
protected $fillable = ['codcolor', 'nomcolor'];
public $timestamps = false;
}
