<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connection_Bexsoluciones extends Model
{
    use HasFactory;

    protected $table = 'connection_bexsoluciones';
    protected $connection = 'mysql';
    protected $fillable = [
        'name', 
        'host',
        'username',
        'password',
        'alias',
        'area',
        'active',
        'created_at',
        'updated_at'
    ];

    public static function getAll(){
        return static::all();
    }
}
