<?php

namespace App\Models\Bex_0003;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T29BexProductos extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't29_bex_productos';
protected $fillable = ['codigo', 'descripcion', 'codunidademp', 'peso', 'codproveedor', 'nomproveedor', 'unidadventa', 'codindadventa', 'estado', 'estado_unidademp', 'estadoproveedor'];
public $timestamps = false;

public function scopeDataToInsertUnidadEmp($query){
    return $query->select('codunidademp', 'codunidademp as NOMUNIDADEMP')
        ->where('codigo','!=','')
        ->where('estado_unidademp', 'A')
        ->groupBy('codunidademp'); 
}

public function scopeDataToInsertTblmproveedor($query){
    return $query->select('codproveedor', 'nomproveedor')
        ->where('estadoproveedor', 'A')
        ->where('codigo', '!=', '')
        ->groupBy('codproveedor', 'nomproveedor');
}

public function scopeDataUpdateTblmproducto($query){
    return $query->select('codigo as PLUPRODUCTO', 'descripcion as NOMPRODUCTO', 'codunidademp', 'peso', 'codproveedor', 
                   'unidadventa');
}

public function scopeDistintProducts($query){
    return $query->select('codigo as codproducto', DB::raw("'001' as codempresa"), 'codigo as pluproducto', 
                          'descripcion as nomproducto', 'codunidademp', DB::raw("'0' as bonentregaproducto,
                          '0' as codgrupoproducto, 'A' as estadoproducto"))
        ->where('estado', 'A')
        ->distinct();
}
}
