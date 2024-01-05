<?php

namespace App\Models\Bex_0004;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T25BexPrecios extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't25_bex_precios';
protected $fillable = ['lista', 'producto', 'precio', 'estadoprecio'];
public $timestamps = false;

public function scopeInsertDataTblmprecio($query){
    return $query->select('lista as codprecio', DB::raw("concat('LISTA PRECIO ', lista) as nomprecio"))
        ->where('estadoprecio', 'A')
        ->groupBy('lista')
        ->orderBy('lista');
}

public function scopePreciosAll($query){
    return $query->distinct()
        ->select('producto as codproducto','lista as codprecio','precio as precioproductoprecio');
}
}
