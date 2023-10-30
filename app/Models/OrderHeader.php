<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderHeader extends Model
{
    use HasFactory;

    protected $connection = 'dynamic_connection';

    protected $table="tbldmovenc";

    protected $fillable = [
        'CODMOVENC',
        'CODEMPRESA',
        'CODTIPODOC',
        'PREFMOV',
        'NUMMOV',
        'CODVENDEDOR',
        'NUMVISITA' ,
        'CODCLIENTE',
        'CODPRECIO',
        'CODDESCUENTO',
        'CODMOTVIS',
        'FECHORINIVISITA',
        'FECHORFINVISITA',
        'EXTRARUTAVISITA',
        'FECMOV',
        'CODVEHICULO',
        'MOTENTREGA',
        'FECHORENTREGAMOV',
        'CODFPAGOVTA',
        'NUMCIERRE',
        'FECHORCIERRE',
        'CODGRACIERRE',
        'NUMCARGUE',
        'FECHORCARGUE',
        'DIARUTERO',
        'NUMLIQUIDACION',
        'FECHORLIQUIDACION',
        'ORDENCARGUEMOV',
        'MENSAJEMOV',
        'JAVAID',
        'FECCAP',
        'NUMMOVALT',
        'FECHORENTREGACLI',
        'FECNOVEDAD',
        'AUTORIZACION',
        'CODGRAAUTORIZACION',
        'DCTOGLOBAL',
        'NUMCIERREREC',
        'FECHORCIERREREC',
        'CODGRACIERREREC',
        'PROYECTO',
        'EXPORTADO',
        'MENSAJEADIC',
        'CONSCAMPANAOK',
        'CODVENDEDORTRANS',
        'EMAILB2B',
        'ORIGEN',
        'ORDENDECOMPRA',
        'direntrega',
        'tipoentrega',
        'nummovtr',
        'prefmovtr',
        'backorder',
        'prospecto',
        'puntosenvio',
        'estadoenviows',
        'fechamovws',
        'msmovws',
        'udid',
        'os',
        'ip',
        'tipofactura',
        'adjunto1',
        'adjunto2',
        'adjunto3',        
    ];
    public $timestamps=false;
}
