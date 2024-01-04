<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Custom_Migration extends Model
{
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 'custom_migrations';

    protected $fillable = [
        'id', 
        'name',
        'command',
        'custom_inserts_id'
    ];

    public static function getAll(){
        return static::all();
    }

    public function scopeNameTables($query){
        return $query->select('name_table', 'custom_inserts_id')
            ->where('custom_inserts_id', '!=', null);
    }
}
