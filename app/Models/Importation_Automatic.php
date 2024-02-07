<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Importation_Automatic extends Model
{
    use HasFactory;

    protected $table = 'importation_automatic';
    protected $connection = 'mysql';
    protected $fillable = [
        'id',
        'id_table', 
        'state',
        'area',
        'date_init',
        'date_end'
    ];
    public $timestamps = false;

    public function scopeImportationState($query, $date, $name_db, $area){
        return $query->select('importation_automatic.id', 'id_table', 'importation_automatic.state', 'importation_automatic.area', 'date_init', 'date_end', 'alias')
            ->join('commands', 'commands.id', '=', 'id_table')
            ->whereRaw("DATE(date_init) = ?", [$date])
            ->where('alias', $name_db)
            ->where('importation_automatic.area', $area);
    }
}
