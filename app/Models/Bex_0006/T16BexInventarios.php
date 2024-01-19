<?php

namespace App\Models\Bex_0006;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T16BexInventarios extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't16_bex_inventarios';
protected $fillable = ['bodega', 'iva', 'producto', 'inventario', 'estadoimpuesto', 'estadobodega'];
public $timestamps = false;

public function scopeInsertDataTblmimpuesto($query){
    return $query->select('iva as codimpuesto', DB::raw("concat('IVA ', iva) as nomimpuesto"), 'iva as porcimpuesto')
        ->where('estadoimpuesto', 'A')
        ->where('producto','<>', '')
        ->groupBy('iva');
}

public function scopeInsertDataTbldstock($query){
    return $query->join('t29_bex_productos', 't16_bex_inventarios.producto', '=', 't29_bex_productos.codigo')
        ->select('producto as codproducto', 'bodega as codbodega', 'iva as codimpuesto', 'inventario as existencia_stock');
}

public function scopeInsertDataToTblmbodega($query){
    return $query->select('bodega as codbodega', DB::raw("concat('BODEGA ', bodega) as nombodega"), DB::raw("'N' as ctlstockbodega"))
    ->where('estadobodega', 'A')
    ->groupBy('bodega');
}
}
