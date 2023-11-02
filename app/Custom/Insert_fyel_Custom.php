<?php

namespace App\Custom;

use App\Custom\WebServiceSiesa;
use App\Models\Ws_Unoee_Config;
use Illuminate\Support\Facades\DB;
use App\Traits\ConnectionTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;

class Insert_fyel_Custom
{
    use ConnectionTrait;

    public function __construct()
    {
    }

    public function InsertFyelCustom($conectionBex, $datosAInsertar, $modelInstance)
    {
        $resultado = DB::connection($conectionBex)->table('tblmcliente')
            ->where('nitcliente', $datosAInsertar[0]->nitcliente)
            ->where('succliente', $datosAInsertar[0]->succliente)->first();

        $modelInstance::where('nitcliente', $resultado->NITCLIENTE)
            ->where('succliente', $resultado->SUCCLIENTE)
            ->update(['codcliente' => $resultado->CODCLIENTE]);

        // Trunca la tabla 'tbldamovil'
        DB::connection($conectionBex)->table('tbldamovil')->truncate();

        // Preparar los datos para inserción
        $dataToInsert = [];
        foreach ($datosAInsertar as $dato) {
            $dataToInsert[] = [
                'nitcliente' => $dato->nitcliente,
                'succliente' => $dato->succliente,
                'ano' => $dato->ano,
                'mes' => $dato->mes,
                'valor' => $dato->valor,
                'codvendedor' => $dato->codvendedor,
                'codcliente' => $dato->codcliente
            ];
        }

        // Insertar los datos en lotes
        DB::connection($conectionBex)->table('tbldamovil')->insert($dataToInsert);

        dd('éxito');
    }

}