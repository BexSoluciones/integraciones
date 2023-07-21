<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    use HasFactory;

    protected $table = 'conexiones';

    protected $fillable = [
        'id_conexion', 
        'nombre_conexion',
        'cron'
    ];

    public static function getAll(){
        return self::select('id_conexion', 'nombre_conexion', 'cron');
            //->where('nombre_conexion', 'LIKE', 'bex movil')
            //->whereNotNull('cron')
    }
}
