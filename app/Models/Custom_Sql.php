<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Custom_Sql extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql';
    protected $table = 'custom_sql';

    protected $fillable = [
        'id', 
        'connection_id',
        'category',
        'txt'
    ];
}
