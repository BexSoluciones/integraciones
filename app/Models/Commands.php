<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commands extends Model
{
    use HasFactory;
    protected $table = 'commands';
    protected $fillable = [
        'alias', 
        'command',
        'name_db',
        'cron_expression',
        'area',
        'cod_area',
        'estado'
    ];

    public static function getAll(){
        return static::all();
    }
}
