<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Command extends Model
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
        'state'
    ];

    public function scopeGetAll($query){
        return $query->select('alias', 'command', 'name_db', 'cron_expression', 'area', 'cod_area', 'state')
                     ->where('state', '1');
    }
}
