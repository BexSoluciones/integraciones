<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tbl_Log;
use App\Models\Importation_Demand;

use Illuminate\Http\Request;

class ImportationController extends Controller
{
    public function consultState(Request $request){
        try{
            $importation = Importation_Demand::importationState($request->consecutive)->first();
      
            if(empty($importation)){
                return response()->json([
                    'status'   => 200,
                    'response' => 'No existe la importacion '.$request->consecutive,
                ]);
            }

            $responseArray = [
                '1' => 'La importación está en espera.',
                '2' => 'La importación está en ejecución.',
                '3' => 'La importación ha finalizado.',
                '4' => 'La importación ha presentado un error.',
            ];
            
            $stateAsString = (string) $importation->state;
            
            if (isset($responseArray[$stateAsString])) {
                return response()->json([
                    'status'   => 200,
                    'code' => $stateAsString,
                    'response' => $responseArray[$stateAsString],
                ]);
            } else {
                return response()->json([
                    'status'   => 200,
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
