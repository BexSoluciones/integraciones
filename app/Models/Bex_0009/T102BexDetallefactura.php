<?php

namespace App\Models\Bex_0009;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T102BexDetallefactura extends Model
{
    use HasFactory;

protected $connection = 'dynamic_connection';
protected $table = 't102_bex_detallefactura';
protected $fillable = ['nitcliente', 'tipodoc', 'numeroFactura', 'codproducto', 'tipoCredito', 'nomproducto', 'cantidad', 'valorUnitario', 'valorTotal'];
public $timestamps = false;
}
