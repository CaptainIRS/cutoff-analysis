<?php

namespace App\Providers;

use DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $statement = 'PRAGMA cache_size=-100000';
        DB::connection('sqlite')->statement($statement);
        $statement = 'PRAGMA synchronous=OFF';
        DB::connection('sqlite')->statement($statement);
        $statement = 'PRAGMA journal_mode=OFF';
        DB::connection('sqlite')->statement($statement);
    }
}
