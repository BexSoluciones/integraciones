<?php

namespace App\Console\Commands\Migrations;

use DB;

use App\Traits\MigrateTrait;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;


class DataBasePandapan extends Command {
    
    use MigrateTrait;

    protected $signature   = 'migrate:database-pandapan';
    protected $description = 'Run migrations for Pandapan database';

    public function handle() {  

        $db = 'pandapan'; //name of the database where we are going to migrate
        //create the 'custom migrations' table if it does not exist in the database
        if (!Schema::connection($db)->hasTable('custom_migrations')) {
            try {
                Schema::connection($db)->create('custom_migrations', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->string('command')->default('');
                    $table->timestamps();
                });
            } catch (\Exception $e) {
                $this->error('Error al crear la tabla custom_migrations: '.$e->getMessage());
            }

            //gets the names of all the tables in the migrations
            $migrationFiles = File::glob(database_path('migrations/migration-'.$db.'/*.php'));

            foreach ($migrationFiles as $file) {
                $migrationName = pathinfo($file, PATHINFO_FILENAME);
                //inserts the migration names data into the 'custom_migrations' table
                DB::connection($db)->table('custom_migrations')->insert([
                    'name' => $migrationName.'.php',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        $this->migrate($db); //
    }
}
