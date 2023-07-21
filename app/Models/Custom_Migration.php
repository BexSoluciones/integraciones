<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Custom_Migration extends Model
{
    use HasFactory;

    protected $connection = 'pandapan';
    protected $table = 'custom_migrations';

    protected $fillable = [
        'id', 
        'name',
        'command'
    ];

    public static function getAll(){
        return static::all();
    }
}
