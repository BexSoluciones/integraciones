<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;

use App\Jobs\ImportationJob;
use App\Models\Command;
use App\Models\Tbl_Log;
use App\Models\Importation_Demand;
use App\Models\Connection_Bexsoluciones;
use App\Traits\ConnectionTrait;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CommandController extends Controller
{
    use ConnectionTrait;

    public function updateInformation(Request $request){
        try {
            $count= count($request->all());
            if($count == 4){

                $keys = ['name_db', 'area', 'date', 'hour'];
                foreach($request->all() as $key=>$value){
                    // return $key;
                    if (!in_array($key, $keys)) {
                        return response()->json([
                            'status'   => 422, 
                            'response' => 'El atributo '.$key.' es incorrecto'
                        ]);
                    }
                }
            }else{
                return response()->json([
                    'status'   => 422, 
                    'response' =>'El numero de atributos no son correctos'
                ]);
            }

            $rules = [
                'date' => 'nullable|date_format:Y-m-d',
                'hour' => 'nullable|date_format:H:i',
            ];
            
            $messages = [
                'date.date_format' => 'Formato :attribute incorrecto (yyyy-mm-dd).',
                'hour.date_format' => 'Formato :attribute incorrecto (H:i).',
            ];
            
            $validator = Validator::make($request->all(), $rules, $messages);
            
            if ($validator->fails()) {
                return response()->json([
                    'status'   => 422, 
                    'response' => $validator->errors()->first()
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
            
            // Valida que no supere el numero de importaciónes permitidos por dia
            $NumberOfAttemptsPerDay = Importation_Demand::NumberOfAttemptsPerDay($request->name_db);

            if($NumberOfAttemptsPerDay >= 20){
                return response()->json([
                    'status'   => 500, 
                    'response' => 'Usted ya supero el limite de importaciones por dia.'
                ]);
            }

            $configDB = $this->connectionDB($connection->id, 'external', $request->area);
        
            if($configDB == false){
                return response()->json([
                    'status'   => 500, 
                    'response' => 'Ocurrio un error en la configuración de BD.'
                ]);
            }
          
            if ($request->date === '') {
                $dateUser = Carbon::now()->toDateString();
            } else {
                $dateUser = Carbon::parse($request->date)->toDateString();
            }
    
            if ($request->hour === null ) {
                $hourUser = Carbon::now()->toTimeString();
            } else {
                $hourUser = Carbon::parse($request->hour)->toTimeString();
            }

            // Primero revisa que una importación no se este ejecutando
            $importation = Command::forNameBD($request->name_db, $request->area)->first();

            if($importation->state == '2'){
                return response()->json([
                    'status'   => 200, 
                    'response' => 'Ya tienes una importación en curso.'
                ]);
            }
                
            $currentHour = Carbon::now()->toTimeString();
            $currentDate = Carbon::now()->toDateString();
            
            //Primero calcula la diferencia entre la hora actual y la que ingresa el usuario
            if ($dateUser < $currentDate) {
                return response()->json([
                    'status'   => 200, 
                    'response' => 'La importación no puede ejecutarse a las '.$hourUser.'. Por favor selecciona otra hora.'
                ]);
            }

            if ($hourUser < $currentHour && $dateUser == $currentDate) {
                return response()->json([
                    'status'   => 200, 
                    'response' => 'La importación no puede ejecutarse a las '.$hourUser.'. Por favor selecciona otra hora.'
                ]);
            }
                
            $processAndRunning = Importation_Demand::processAndRunning()->get();
    
            if(isset($processAndRunning)){
                foreach ($processAndRunning as $data) {
                    $datetimeData = Carbon::parse($data->date.' '.$data->hour, 'UTC');
                    $datetimeUser = Carbon::parse($dateUser.' '.$hourUser, 'UTC' );

                    $differenceInMinutes = $datetimeUser->diffInMinutes($datetimeData);
                    if($differenceInMinutes <= 30){
                        return response()->json([
                            'status'   => 200, 
                            'response' => 'La importación no puede ejecutarse a las '.$hourUser.'. Por favor selecciona otra hora.'
                        ]);
                    }
                }
            }

            $importation = Importation_Demand::create([
                'command' => 'command:update-information',
                'name_db' => $request->name_db,
                'area'    => $request->area,
                'hour'    => $hourUser,
                'date'    => $dateUser,
            ]);
            
            // Se registra en la cola de procesos (jobs)
            $currentTimeDate = Carbon::now();
            $delayInSeconds = $currentTimeDate->diffInSeconds($dateUser.' '.$hourUser, 'UTC');
            ImportationJob::dispatch($importation->consecutive)->onQueue($importation->area)->delay($delayInSeconds);

            return response()->json([
                'status'   => 200, 
                'code' => $importation->consecutive,
                'response' => 'Importación numero: '.$importation->consecutive.'  la cual se ejecutara a las '.$importation->hour
            ]);
        
        } catch (\Exception $e) {
            Tbl_Log::create([
                'descripcion' => 'Controller::CommandController[updateInformation()] => '.$e->getMessage()
            ]);
            return response()->json([
                'status'   => 500, 
                'response' => 'Ocurrio un error con la petición.  '.$e->getMessage()
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

            if($output == 1){
                $rutaArchivo = 'export/'.$request->name_db.'/'.$request->area.'/pedidos_txt/'.$request->closing.'.PE0';
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
