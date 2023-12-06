<?php

namespace App\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Importation_Demand extends Model
{
    use HasFactory;
    protected $table = 'importation_demand';
    protected $connection = 'mysql';
    protected $primaryKey = 'consecutive';
    protected $fillable = [
        'consecutive', 
        'command',
        'name_db',
        'area',
        'hour',
        'date',
        'state',
        'created_at',
        'updated_at'
    ];
   
    public function scopeProcessAndRunning($query){
        $currentDate = Carbon::now()->toDateString();
        $currentHour = Carbon::now()->toTimeString();
        //Restamos 30 minutos
        $newHour = Carbon::parse($currentHour)->subMinutes(30)->toTimeString();
        return $query->where('date', '>=', $currentDate)
            ->where('hour', '>=',  $newHour)
            ->whereIn('state', ['1','2']);
    }

    public function scopeForConsecutive($query, $consecutive){
        return $query->select('command', 'name_db', 'area')->where('consecutive', $consecutive);
    }

    public function scopeImportationInCurse($query, $name_db, $area){
        return $query->select('id', 'state', 'updated_at')
            ->where('name_db', $name_db)
            ->where('area', $area)
            ->whereIn('estado', ['1', '2'])
            ->first();
    }

    public function scopeNumberOfAttemptsPerDay($query, $name_db){
        $currentDate = Carbon::now()->toDateString();
        return $query->where('name_db', $name_db)
            ->where('date', $currentDate)
            ->where('state', '!=', '4')
            ->count();
    }

    public function scopeImportationState($query, $consecutive){
        return $query->select('state')
            ->where('consecutive', $consecutive);
    }
}
