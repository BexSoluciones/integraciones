<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Stringable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;


class CommandController extends Controller
{
    public function updateInformation(Request $request){
        try {
            set_time_limit(0);
            Artisan::call('command:update-information '.$request->name_db);
            $output = Artisan::output();
            if($output){
                Artisan::call('command:export-information '.$request->name_db);
            }
            $output = Artisan::output();
            if(empty($output)){
                return response()->json(['status' => 200, 'response' => 'Ha ocurrido un arror al procesar la peticion']);    
            }
            return response()->json(['status' => 200, 'response' => 'Proceso finalizado']);
        } catch (\Exception $e) {
            Log::error('Error schedule: ' . $e->getMessage());
            return 'error';
        }
    }

    public function uploadOrder(Request $request){
        try{
            set_time_limit(0);
            Artisan::call('command:upload-order', [
                'database' => $request->name_db,
                'area' => $request->area,
                'closing' => $request->closing,
            ]);
            $output = Artisan::output();

            return response()->json(['status' => 200, 'response' => $output]);
        } catch (\Exception $e) {
            Log::error('Error uploadOrder: ' . $e->getMessage());
            return  'Error uploadOrder: ' . $e->getMessage();
        }
    }
}
