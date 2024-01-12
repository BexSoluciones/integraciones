<?php

namespace App\Http\Controllers;

use App\Models\Connection;

use Illuminate\Support\Facades\Event;
use Illuminate\Database\Events\MigrationsEnded;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\App;
use Symfony\Component\Console\Input\ArgvInput;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class AssemblyController extends Controller {
    
    public function register(){

        $databaseList = DB::select("SHOW DATABASES LIKE 'bex\_%'");
        $databases = [];

        foreach ($databaseList as $database) {
            $databaseName = reset($database); // Obtiene el primer valor del objeto
            $databases[] = $databaseName;
        }

        return view('integracion.registro', compact('databases'));
    }

    public function store(Request $request){
        
        try {

    
            Artisan::call('command:migrations');
            //Artisan::call('generate:migrations');
            $output = Artisan::output();
            return $output;
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
        
        /*
        try{

            Artisan::call('migrate:generate');

    

            $assembly = Connection::create([
                'name'       => $request->nombreDB,
                'host'       => 'host',
                'username'   => 'root',
                'password'   => null,
                'alias'      => $request->aliasDB,
                'active'     => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            $salida = Artisan::call('migrate:generate');

            // Puedes hacer algo con la salida, por ejemplo, devolverla en una vista
            return $salida;





            // Ruta completa al archivo Artisan
            $artisanPath = base_path('artisan');

            // Llama al comando utilizando la ruta completa
            Artisan::call('migrate:generate', [
                '--path' => 'database/migrations/migration-'.$assembly->name, 
            ]);

            // Obtiene la salida del comando (opcional)
            $output = Artisan::output();
            return $output;

            

                // Ejecutar el comando personalizado "migrate:generate"
                $output = shell_exec('php artisan migrate:generate --path=database/migrations/migration-'.$assembly->name);
                
            
    
            return response()->json(['status'  => 200, 
                                     'success' => $output]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'error' => $e->getMessage()]);
        }*/
    }
}
