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

            return $output;
            
            /*
            if ($output === 0) {
                // El comando se ejecutó con éxito
                Artisan::call('command:export-information');
                return 'exito';
            } else {
                // El comando falló
                $output = Artisan::output();
                echo $output;
                return 'error';
            }*/
        } catch (\Exception $e) {
            Log::error('Error schedule: ' . $e->getMessage());
            return 'error';
        }
        
    }
}
