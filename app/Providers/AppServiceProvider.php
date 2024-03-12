<?php

namespace App\Providers;

use Exception;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            DB::connection()->getPDO();
            dd(DB::connection()->getDatabaseName());
         } catch (\Exception $e) {
            print('Error uploadOrder: ' . $e->getMessage());
         }
    }
}
