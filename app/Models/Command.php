<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Command extends Model
{
    use HasFactory;

    protected $table = 'commands';
    protected $connection = 'mysql';
    protected $fillable = [
        'alias', 
        'command',
        'name_db',
        'cron_expression',
        'area',
        'cod_area',
        'state'
    ];
    public $timestamps = false;

    public function scopeGetAll($query){
        return $query->select('connection_bexsoluciones.name', 'command', 'name_db', 'cron_expression', 'commands.area', 'cod_area', 'state')
                    ->join('connection_bexsoluciones', 'commands.connection_bexsoluciones_id', '=' ,'connection_bexsoluciones.id')
                    ->where('connection_bexsoluciones.area', 'commands.area')
                    ->where('state', '1');
    }
    
    public function scopeForNameBD($query, $nameDB, $area){
        return $query->select('name_db', 'connection_bexsoluciones.name', 'commands.area', 'state')
            ->join('connection_bexsoluciones', 'commands.connection_bexsoluciones_id', '=' ,'connection_bexsoluciones.id')
            //->where('connection_bexsoluciones.area', 'commands.area')
            ->where('name_db', $nameDB)
            ->where('commands.area', $area);
    }
}
