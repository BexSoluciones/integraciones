<?php

namespace App\Models\Bex_0002;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T10TblLog extends Model
{
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 't10_tbl_log';
    protected $fillable = ['codigo', 'id_table','name_table', 'descripcion', 'created_at', 'updated_at'];
    public $timestamps = false;
}
