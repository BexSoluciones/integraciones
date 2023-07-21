<?php

namespace App\Console\Commands\Migrations;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;


class DataBaseIntegraciones extends Command
{
    protected $signature = 'migrate:database-integraciones';
    protected $description = 'Run migrations for Integraciones database';

    public function handle()
    {
        $this->call('migrate', [
            '--database' => 'integraciones',
            '--path' => 'database/migrations/migration-integraciones',
        ]);
    }
}
