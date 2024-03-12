<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;

use App\Jobs\ImportationJob;
use App\Http\Requests\UpdateInformation;
use App\Http\Controllers\Controller;
use App\Models\Tbl_Log;
use App\Models\Importation_Demand;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CommandController extends Controller
{
    public function updateInformation(UpdateInformation $request){
        try {

            $data = $request->timeValidator();

            $importation = Importation_Demand::create([
                'command' => 'command:update-information',
                'name_db' => $request->name_db,
                'area'    => $request->area,
                'hour'    => $data['hourUser'],
                'date'    => $data['dateUser']
            ]);
        
            // Se registra en la cola de procesos (jobs)
            $currentTimeDate = Carbon::now();
            $delayInSeconds = $currentTimeDate
                ->diffInSeconds($data['dateUser'].' '.$data['hourUser'], 'UTC');

            ImportationJob::dispatch($importation->consecutive)
                ->onQueue($importation->area)
                ->delay($delayInSeconds);

            return response()->json([
                'status'   => 200, 
                'code'     => $importation->consecutive,
                'response' => 'Importación numero: '.$importation->consecutive.' la cual se ejecutara en la fecha: '.$importation->date.' a las '.$importation->hour
            ]);
        } catch (\Exception $e) {
            Tbl_Log::create([
                'descripcion' => 'Controller::CommandController[updateInformation()] => '.$e->getMessage()
            ]);
            return response()->json([
                'status'   => 500, 
                'response' => 'Ocurrio un error con la petición.'
            ]);
        }
    }

    public function uploadOrder(Request $request){
        try{
            $rules = [
                'name_db' => 'required|string',
            ];
            
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => 422, 
                    'response' => $validator->errors()->first()
                ]);
            }

            $connection = DB::table('connections')->where('connections.name', $request->name_db)
            ->join('connection_bexsoluciones', 'connections.id', 'connection_bexsoluciones.connection_id')
            ->select('connection_bexsoluciones.*')
            ->first();
            
            if(!$connection) {
                return response()->json([
                    'status'   => 404, 
                    'response' => 'La base de datos '. $request->name_db . 'no existe'
                ]);
            }

            // Validar que exista el area
            $areas = ['bexmovil', 'bextms', 'bextramites', 'bexwms', 'ecomerce'];
            if (!in_array($request->area, $areas)) {
                return response()->json([
                    'status'   => 200, 
                    'response' => 'El area '.$request->area.' no existe'
                ]);
            }
            
            $output = Artisan::call('command:upload-order', [
                'database' => $connection->id,
                'area' => $request->area,
                'closing' => $request->closing,
            ]);
            
            if($output == 0){
                $rutaArchivo = 'export/'.$request->name_db.'/'.$request->area.'/pedidos_txt/'.str_pad($request->closing,8,"0",STR_PAD_LEFT).'.PE0';
                $rutaCompleta = storage_path('app/public/' . $rutaArchivo);
                if (file_exists($rutaCompleta)) {
                     // URL pública del archivo
                    $urlArchivo = Storage::url($rutaArchivo);
                    // Agregar el dominio a la URL
                    $urlCompleta = url($urlArchivo);
                    return response()->json(['status' => 200, 'response' => $urlCompleta]);
                }else{
                    return response()->json(['status' => 401, 'response' => 'El cierre '.$request->closing.' no exise']);
                }
            } else {
                return response()->json(['status' => 401, 'response' => 'El cierre '.$request->closing.' no exise']);
            }
        
        } catch (\Exception $e) {
            //Log::error('Error uploadOrder: ' . $e->getMessage());
            return  'Error uploadOrder: ' . $e->getMessage();
        }
    }
}
