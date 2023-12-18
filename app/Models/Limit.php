<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Limit extends Model
{
    use HasFactory;

    protected $table = 'limits';
    protected $connection = 'mysql';
    protected $fillable = [
        'id',
        'id_connections', 
        'id_areas',
        'number',
        'state'
    ];
    public $timestamps = false;

    public function scopeGetLimit($query, $name_db, $area){
        return $query->select('limits.number')
            ->join('connections', 'connections.id', '=', 'id_connections')
            ->join('areas', 'areas.id', '=', 'id_areas')
            ->where('connections.name', $name_db)
            ->where('areas.name', $area)
            ->where('connections.active', 1)
            ->where('areas.state', 1)
            ->where('limits.state', 1);
    }
}
