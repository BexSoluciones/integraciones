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

    public static function getAllBexMovil(){
        return static::all()->where('custom_inserts_id', '>=',1)
                            ->where('custom_inserts_id', '<=',99);
    }

    public static function getAllBexTramite(){
        return static::all()->whereIn('custom_inserts_id', [1,2,100,101,102]);
    }

    public function scopeNameTables($query){
        return $query->select('name_table', 'custom_inserts_id')
            ->where('custom_inserts_id', '!=', null);
    }

    public function scopeNameTablesBexMovil($query){
        return $query->select('name_table', 'custom_inserts_id')
            ->where('custom_inserts_id', '>=',1)
            ->where('custom_inserts_id', '<=',99);
    }

    public function scopeNameTablesBextramite($query){
        return $query->select('name_table', 'custom_inserts_id')
            ->whereIn('custom_inserts_id', [1,2,100,101,102]);
    }
}
