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

class CommandController extends Controller
{
    use ConnectionTrait;

    public function updateInformation(Request $request){
        try {
            $configDB = $this->connectionDB($request->name_db);
            if($configDB == false){
                return response()->json([
                    'status'   => 500, 
                    'response' => 'Ocurrio un error en la configuracion de BD.'
                ]);
            }

            $currentDate = Carbon::now()->toDateString();
            if($request->hour == null){
                // Primero revisa que una importacion no se este ejecutando
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
            }

            $importation = Importation_Demand::create([
                'command' => 'command:update-information',
                'name_db' => $request->name_db,
                'area'    => $request->area,
                'hour'    => $request->hour == null ? $importation_hour : $request->hour,
                'date'    => $currentDate,
            ]);
            
            // Calcula la diferencia en minutos hasta la hora de importación
            $differenceInMinutes = Carbon::parse($importation->hour)->diffInMinutes(Carbon::now());

            // Se registra en la cola de procesos (jobs)
            ImportationJob::dispatch($importation->id)->onQueue($importation->area)->delay(now()->addMinutes($differenceInMinutes));

            return response()->json([
                'status'   => 200, 
                'response' => 'Su importacion se ejecutara a las '.$importation->hour
            ]);
        
        } catch (\Exception $e) {
            Tbl_Log::create([
                'descripcion' => 'CommandController[updateInformation()] => '.$e->getMessage()
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
                $importation_hour = Carbon::now()->addMinutes(2)->toTimeString();
            } else {
                $currentTime = Carbon::now();
                $lastHour = Carbon::parse($lastImportationDemand->hour);

                // Calcula la diferencia en minutos
                $differenceInMinutes = intval($currentTime->diffInMinutes($lastHour));

                /* Si la cola de procesos está llena, la nueva solicitud queda programada para 
                5 minutos después de la última solicitud registrada*/
                if ($differenceInMinutes != 0) {
                    $importation_hour = $lastHour->addMinutes(5)->toTimeString();
                } else {
                    $importation_hour = Carbon::now()->addMinutes(2)->toTimeString();
                }
            }
            return $importation_hour;
        } catch (\Exception $e) {
            Tbl_Log::create([
                'descripcion' => 'CommandController[calculateTime()] => ' . $e->getMessage(),
            ]);
            return false;
        }
    }

    public function uploadOrder(Request $request){
        try{
            set_time_limit(0);
            Artisan::call('command:upload-order', [
                'database' => $request->alias_db,
                'area' => $request->area,
                'closing' => $request->closing,
            ]);
            $output = Artisan::output();

            return response()->json(['status' => 200, 'response' => $output]);
        } catch (\Exception $e) {
            //Log::error('Error uploadOrder: ' . $e->getMessage());
            return  'Error uploadOrder: ' . $e->getMessage();
        }
    }
}
