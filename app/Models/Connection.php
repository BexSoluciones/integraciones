<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    use HasFactory;

    protected $table = 'connections';
    protected $fillable = [
        'name', 
        'host',
        'username',
        'password',
        'alias',
        'active',
        'created_at',
        'updated_at'
    ];

    public static function getAll(){
        return static::all();
    }
}
