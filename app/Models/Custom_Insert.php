<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Custom_Insert extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'custom_inserts';

    protected $fillable = [
        'id', 
        'method',
        'state'
    ];

    public function scopeMethods($query){
        return $query->select('id', 'method')
        ->where('state', 1);
    }

}
