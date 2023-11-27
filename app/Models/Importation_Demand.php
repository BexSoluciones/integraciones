<?php

namespace App\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Importation_Demand extends Model
{
    use HasFactory;
    protected $table = 'importation_demand';
    protected $fillable = [
        'id', 
        'command',
        'name_db',
        'area',
        'hour',
        'date',
        'state',
        'created_at',
        'updated_at'
    ];

    public function scopeLast($query){
        $currentDate = Carbon::now()->toDateString();
        return $query->where('date', $currentDate)
            ->where('state', 1)
            ->orderBy('hour', 'desc');
    }

    public function scopeForId($query, $id){
        return $query->select('command', 'name_db', 'area')->where('id', $id);
    }

    public function scopeImportationInCurse($query, $name_db, $area){
        return $query->where('name_db', $name_db)
            ->where('area', $area)
            ->where('estado', '2')
            ->first();
    }
}
