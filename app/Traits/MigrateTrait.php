<?php
namespace App\Traits;

use Exception;

use App\Models\Custom_Migration;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

trait MigrateTrait {

    public function preMigration($db){
        if (!Schema::connection('dynamic_connection')->hasTable('custom_migrations')) {
            try {
                //DB growth 'custom_migrations'
                Schema::connection('dynamic_connection')->create('custom_migrations', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->string('name_table');
                    $table->string('command')->default('');
                    $table->timestamps();
                });
            } catch (\Exception $e) {
                $this->error('Error al crear la tabla custom_migrations: ' . $e->getMessage());
            }

            //gets the names of all the tables in the migrations
            $migrationFiles = File::glob(database_path('migrations/migration-'.$db.'/*.php'));

            foreach ($migrationFiles as $file) {
                $migrationName = pathinfo($file, PATHINFO_FILENAME); //Name Migration
                $migrationCode = file_get_contents($file); //Name Table

                //get table name
                if (preg_match("/Schema::create\s*\(\s*'([^']+)'/", $migrationCode, $matches)) {
                    $tableName = $matches[1];
                }
                //inserts the migration names data into the 'custom_migrations' table
                DB::connection('dynamic_connection')->table('custom_migrations')->insert([
                    'name'       => $migrationName.'.php',
                    'name_table' => $tableName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } 
        $this->migrateTables($db); //Function that performs the migrations
    }

    public function migrateTables($db){
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