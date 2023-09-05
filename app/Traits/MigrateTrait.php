<?php
namespace App\Traits;

use Exception;

use App\Models\Custom_Migration;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

trait MigrateTrait {
    public function migrate($db){
        try {
            //update of migration commands in the 'custom_migrations' table
            DB::connection('dynamic_connection')
                ->table('custom_migrations')
                ->where('command', '')
                ->where(function ($query) {
                    $query->where('name', 'NOT LIKE', '%_ws_%')
                        ->orWhereNull('name');
                })
                ->where(function ($query) {
                    $query->where('name', 'NOT LIKE', '%_tbl_%')
                        ->orWhereNull('name');
                })
                ->update(['command' => ':refresh']);
        } catch (\Exception $e) {
            $this->error('Error al actualizar los comandos: '.$e->getMessage());
        }

        $customMigrations = Custom_Migration::getAll();

        foreach ($customMigrations as $migration) {
            try {
                //migration of the tables contained in the migration folder with their respective commands
                $this->call('migrate'.$migration->command, [
                    '--database' => 'dynamic_connection',
                    '--path' => 'database/migrations/migration-'.$db.'/'.$migration->name,
                ]);

                //Generate the model
                $modelName = ucfirst(Str::camel($migration->name_table));
                //Check if the model exists
                $modelPath = app_path('Models') . '/' . ucfirst($db) . '/' . $modelName . '.php';
                if (strpos($modelName, 'Bex') === 0) { //Verify the prefix "Bex"
                    if (!File::exists($modelPath)) {
                        $this->call('make:model', [
                            'name' => $modelName,
                        ]);

                        //Create the folder if it doesn't exist
                        if (!is_dir(dirname($modelPath))) { 
                            mkdir(dirname($modelPath), 0777, true);
                        }
                        
                        rename(app_path('Models') . '/' . $modelName . '.php', $modelPath);
                    } else {
                        $this->info("El modelo $modelName ya existe, no se creará nuevamente.");
                    }
                }
            } catch (\Exception $e) {
                $this->error('Error al migrar tabla '.$migration->name.': '.$e->getMessage());
            }
        }
        $this->info('Tablas y Modelos migrados con exito!');
    }
}