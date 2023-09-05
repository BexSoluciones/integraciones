<?php

namespace App\Console\Commands;

use App\Models\Connection;
use App\Traits\MigrateTrait;
use App\Traits\ConnectionTrait;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class MigrateIntegraciones extends Command {

    use MigrateTrait, ConnectionTrait;
    
    protected $signature   = 'migrate:database {database}';
    protected $description = 'Run migrations for all database';

    public function handle() {  

        $db = $this->argument('database'); //name of the database where we are going to migrate
        $this->connectionDB($db); //Function that configures the database
        
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
        $this->migrate($db); //Function that performs the migrations
    }
}
