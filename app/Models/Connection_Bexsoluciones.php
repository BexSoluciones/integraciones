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
        'id',
        'name', 
        'host',
        'username',
        'password',
        'area',
        'active',
        'connection_id',
        'created_at',
        'updated_at'
    ];

    public static function getAll(){
        return static::all();
    }
}
