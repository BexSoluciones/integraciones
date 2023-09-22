<?php

namespace App\Models\Pandapan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblLog extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 'tbl_log';
protected $fillable = ['codigo', 'descripcion', 'created_at', 'updated_at'];
public $timestamps = false;
}
