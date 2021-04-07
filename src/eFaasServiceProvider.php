<?php

namespace eFaasIntegrator;

use Illuminate\Auth\Events\Logout;
use eFaasIntegrator\eFaasIntegrator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class eFaasServiceProvider extends ServiceProvider {

     /**
     * Boot the application services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen(Logout::class, function(){
            app('efaas')->efaasLogout();
        });

        
        $this->registerRoutes();

        $this->loadViewsFrom(__DIR__.'/views', 'efaas');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/config/efaas.php' => config_path('efaas.php'),
            ], 'config');
            
            $this->publishes([
                __DIR__.'/views' => $this->app->resourcePath('views/vendor/efaas'),
            ], 'views');
        }

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('efaas', function () {
            return new eFaasIntegrator();
        });

        $this->mergeConfigFrom(__DIR__.'/config/efaas.php', 'efaas');
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            eFaasIntegrator::class,
        ];
    }

    /**
     * Registers routes for efaas to work
     * 
     * @return void
     */
    private function registerRoutes()
    {
        // dd( __DIR__ . ('/routes.php'));

        Route::namespace('eFaasIntegrator')->middleware('web')->group( __DIR__ . ('/routes.php'));
    }

}