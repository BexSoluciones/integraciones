<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;

use App\Jobs\ImportationJob;
use App\Models\Command;
use App\Models\Tbl_Log;
use App\Models\Importation_Demand;
use App\Traits\ConnectionTrait;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class CommandController extends Controller
{
    use ConnectionTrait;

    public function updateInformation(Request $request){
        try {

            // Valida que no supere el numero de importaciónes permitidos por dia
            $NumberOfAttemptsPerDay = Importation_Demand::NumberOfAttemptsPerDay($request->name_db);

            if($NumberOfAttemptsPerDay >= 10){
                return response()->json([
                    'status'   => 500, 
                    'response' => 'Usted ya supero el limite de importaciones por dia.'
                ]);
            }

            $configDB = $this->connectionDB($request->name_db);

            return $configDB;
            
            if($configDB == false){
                return response()->json([
                    'status'   => 500, 
                    'response' => 'Ocurrio un error en la configuración de BD.'
                ]);
            }

            $currentDate = Carbon::now()->toDateString();
            if($request->hour == null){
                // Primero revisa que una importación no se este ejecutando
                $importation = Command::forNameBD($request->name_db, $request->area)->first();
                if($importation->state == '2'){
                    return response()->json([
                        'status'   => 200, 
                        'response' => 'Ya tienes una importación en curso.'
                    ]);
                }

                // Calcula el tiempo en el cual se ejecutara una importacion
                $importation_hour = $this->calculateTime();
                if($importation_hour == false){
                    return response()->json([
                        'status'   => 500, 
                        'response' => 'Ocurrio un error con la petición.'
                    ]);
                }
            }else{
                // Se mira si la importación se puede ejecutar a la hora que pidio el cliente
                $importation_hour = $this->calculateTime();

                
                $hourUser = Carbon::parse($request->hour);
                $lastHour = Carbon::parse($importation_hour);

                // Calcula la diferencia en minutos
                $differenceInMinutes = intval($hourUser->diffInMinutes($lastHour));

                if($differenceInMinutes >= 0){
                    $importation_hour = $request->hour;
                } else{
                    return response()->json([
                        'status'   => 200, 
                        'response' => 'La importación no puede ejecutarse a las '.$request->hour.'. Por favor selecciona otra hora.'
                    ]);
                }
            }

            $importation = Importation_Demand::create([
                'command' => 'command:update-information',
                'name_db' => $request->name_db,
                'area'    => $request->area,
                'hour'    => $importation_hour,
                'date'    => $currentDate,
            ]);
            
            // Calcula la diferencia en minutos hasta la hora de importación
            $differenceInMinutes = Carbon::parse($importation->hour)->diffInMinutes(Carbon::now());

            // Se registra en la cola de procesos (jobs)
            ImportationJob::dispatch($importation->consecutive)
                ->onQueue($importation->area)
                ->delay(now()->addMinutes($differenceInMinutes));

            return response()->json([
                'status'   => 200, 
                'response' => 'Importación numero: '.$importation->consecutive.'  la cual se ejecutara a las '.$importation->hour
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

    private function calculateTime(){
        try {
            $lastImportationDemand = Importation_Demand::last()->first();

            if (empty($lastImportationDemand)) {
                // Si no hay registros, programa la nueva solicitud para 2 minutos después de la hora actual
                $importation_hour = Carbon::now()->addMinutes(1)->toTimeString();
            } else {
                $currentTime = Carbon::now();
                $lastHour = Carbon::parse($lastImportationDemand->hour);

                // Calcula la diferencia en minutos
                $differenceInMinutes = intval($currentTime->diffInMinutes($lastHour));

                /* Si la cola de procesos está llena, la nueva solicitud queda programada para 
                5 minutos después de la última solicitud registrada*/
                if ($differenceInMinutes != 0) {
                    $importation_hour = $lastHour->addMinutes(3)->toTimeString();
                } else {
                    $importation_hour = Carbon::now()->addMinutes(1)->toTimeString();
                }
            }
            return $importation_hour;
        } catch (\Exception $e) {
            Tbl_Log::create([
                'descripcion' => 'Controller::CommandController[calculateTime()] => ' . $e->getMessage(),
            ]);
            return false;
        }
    }

    public function uploadOrder(Request $request){
        try{
            Artisan::call('command:upload-order', [
                'database' => $request->alias_db,
                'area' => $request->area,
                'closing' => $request->closing,
            ]);
            /*
            $output = Artisan::output();
            $currentTime = Carbon::now()->format('Ymd');
            $rutaArchivo = 'export/bex_0002/pedidos_txt/'.$currentTime.'.txt';
            $rutaCompleta = storage_path('app/public/' . $rutaArchivo);
            if (file_exists($rutaCompleta)) {
                 // URL pública del archivo
                $urlArchivo = Storage::url($rutaArchivo);
                // Agregar el dominio a la URL
                $urlCompleta = url($urlArchivo);
            }else{
                return response()->json(['status' => 200, 'response' => 'Ruta no encontrada']);
            }*/
            return response()->json(['status' => 200, 'response' => 'Generando plano']);
        } catch (\Exception $e) {
            //Log::error('Error uploadOrder: ' . $e->getMessage());
            return  'Error uploadOrder: ' . $e->getMessage();
        }
    }
}
