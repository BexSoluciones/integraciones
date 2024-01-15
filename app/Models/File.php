<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 'files';

    protected $fillable = [
        'id', 
        'custom_migrations_id',
        'name',
        'state',
        'required'
    ];
}
