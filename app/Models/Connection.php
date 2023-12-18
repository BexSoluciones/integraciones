<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    use HasFactory;

    protected $table = 'connections';
    protected $connection = 'mysql';
    protected $fillable = [
        'id',
        'name', 
        'host',
        'username',
        'password',
        'alias',
        'active',
        'created_at',
        'updated_at'
    ];

    public function scopeGetAll($query){
        return $query->select('id', 'name', 'host', 'username', 'password', 'alias', 'active', 'created_at', 
            'updated_at');
    }

    public function scopeForNameDB($query, $name_db){
        return $query->select('connection_bexsoluciones.id')
            ->join('connection_bexsoluciones', 'connections.id', 'connection_bexsoluciones.connection_id')
            ->where('connections.name', $name_db);
    }
}
