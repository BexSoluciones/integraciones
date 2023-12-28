<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tbl_Log extends Model {
    
    use HasFactory;

    protected $table;
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->connection = defined('mysql') ? 'mysql' : 'dynamic_connection';
        $this->table = ($this->connection === 'mysql') ? 'tbl_log' : 't10_tbl_log';
    }
    /*
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->connection = defined('dynamic_connection') ? 'dynamic_connection' : 'mysql';
        $this->table = ($this->connection === 'dynamic_connection') ? 't10_tbl_log' : 'tbl_log';
    }*/
    
    protected $fillable = [
        'codigo',
        'id_table',
        'type', 
        'descripcion',
        'created_at',
        'updated_at'
    ];
}
