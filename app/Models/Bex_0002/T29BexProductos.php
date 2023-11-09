<?php

namespace App\Models\Bex_0002;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class T29BexProductos extends Model
{
    use HasFactory;

    protected $connection = 'dynamic_connection';
    protected $table = 't29_bex_productos';
    protected $fillable = [
        'codigo', 
        'descripcion', 
        'codunidademp', 
        'peso', 
        'codproveedor',
        'nomproveedor', 
        'unidadventa', 
        'codindadventa', 
        'estado', 
        'estado_unidademp', 
        'estadoproveedor'
    ];
    public $timestamps = false;

    public function scopeDataInsertTblmunidademp($query){
        return $query->select('codunidademp as codunidademp', 'codunidademp as nomunidademp')
            ->where('estado_unidademp', 'A')
            ->groupBy('codunidademp', 'estado_unidademp');
    }

    public function scopeDataInsertTblmproveedor($query){
        return $query->select('codproveedor', 'nomproveedor')
            ->where('estadoproveedor', 'A')
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
