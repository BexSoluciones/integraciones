<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tbl_Log extends Model {
    
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 't10_tbl_log';

    protected $fillable = [
        'codigo', 
        'descripcion',
        'created_at',
        'updated_at'
    ];
}
