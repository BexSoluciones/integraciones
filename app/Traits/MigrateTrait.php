<?php
namespace App\Traits;

use Exception;
use App\Models\Custom_Migration;
use Illuminate\Support\Facades\DB;

trait MigrateTrait {
    public function migrate($db){
        try {
            //update of migration commands in the 'custom_migrations' table
            DB::connection('dynamic_connection')
                ->table('custom_migrations')
                ->where('command', '')
                ->where(function ($query) {
                    $query->where('name', 'NOT LIKE', '%\_ws\_%')
                        ->orWhereNull('name')
                        ->orWhere('name', 'NOT LIKE', '%\_tbl\_%');
                })
                ->update(['command' => ':refresh']);
        } catch (\Exception $e) {
            $this->error('Error al actualizar los comandos: '.$e->getMessage());
        }

        $customMigrations = Custom_Migration::getAll();

        //migration of the tables contained in the migration folder with their respective commands
        foreach ($customMigrations as $migration) {
            try {
                $this->call('migrate'.$migration->command, [
                    '--database' => 'dynamic_connection',
                    '--path' => 'database/migrations/migration-'.$db.'/'.$migration->name,
                ]);
            } catch (\Exception $e) {
                $this->error('Error al migrar tabla '.$migration->name.': '.$e->getMessage());
            }
        }
        $this->info('Tablas migradas con exito!');
    }
}