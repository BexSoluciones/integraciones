<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Time_Interval extends Model
{
    use HasFactory;

    protected $table = 'time_intervals';
    protected $connection = 'mysql';
    protected $fillable = [
        'id',
        'id_connections', 
        'id_areas',
        'time',
        'state'
    ];
    public $timestamps = false;

    public function scopeGetInterval($query, $name_db, $area){
        return $query->select('time_intervals.time')
            ->join('connections', 'connections.id', '=', 'id_connections')
            ->join('areas', 'areas.id', '=', 'id_areas')
            ->where('connections.name', $name_db)
            ->where('areas.name', $area)
            ->where('connections.active', 1)
            ->where('areas.state', 1)
            ->where('time_intervals.state', 1);
    }
}
