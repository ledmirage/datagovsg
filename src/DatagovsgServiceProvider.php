<?php

namespace Ledmirage\Datagovsg;

use Illuminate\Support\ServiceProvider;

class DatagovsgServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/datagovsg.php' => config_path('datagovsg.php'),
        ]);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->make('Ledmirage\Datagovsg\Nea');
    }
}