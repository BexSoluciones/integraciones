<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tbl_Log;
use App\Models\Importation_Demand;
use App\Traits\ConnectionTrait;

use Illuminate\Http\Request;

class ImportationController extends Controller
{
    use ConnectionTrait;

    public function consultState(Request $request){
        try{
            $importation = Importation_Demand::importationState($request->consecutive)->first();
            if(empty($importation)){
                return response()->json([
                    'status'   => 401,
                    'response' => 'No existe la importacion '.$request->consecutive,
                ]);
            }
            
            $configDB = $this->connectionDB($request->name_db, 'local');
            if($configDB != 0){
                return response()->json([
                    'descripcion' => 'Error al conectar Base de Datos '.$request->name_db 
                ]);
            }
            
            $arrayTables = [1 => 'commands', 2 => 'importation_demand'];
            $detailLogs  = Tbl_Log::where('id_table', $request->consecutive)
                ->get(['type', 'descripcion', 'created_at', 'updated_at'])
                ->map(function ($table) use ($arrayTables) {
                    $table->table_name = $arrayTables[$table->type] ?? 'Desconocida';
                    return $table;
                });

            $responseArray = [
                '1' => 'La importación está en espera.',
                '2' => 'La importación está en ejecución.',
                '3' => 'La importación ha finalizado.',
                '4' => 'La importación ha presentado un error.',
            ];
            
            $stateAsString = (string) $importation->state;
            
            if (isset($responseArray[$stateAsString])) {
                return response()->json([
                    'status'      => 200,
                    'code'        => $stateAsString,
                    'response'    => $responseArray[$stateAsString],
                    'detailsLogs' => $detailLogs
                ]);
            } else {
                return response()->json([
                    'status'   => 401,
                    'response' => 'Estado de importación no reconocido.',
                ]);
            }
        } catch (\Exception $e) {
            Tbl_Log::create([
                'descripcion' => 'ImportationController[consultState()] => '.$e->getMessage()
            ]);
            return response()->json([
                'status'   => 500, 
                'response' => 'Ocurrio un error con la petición.'
            ]);
        }
    }
}
