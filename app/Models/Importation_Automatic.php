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
        'date_init',
        'date_end'
    ];
    public $timestamps = false;
}
