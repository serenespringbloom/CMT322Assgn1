<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // You can use this method to bind services into the service container.
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Define routes for the application
        Route::middleware('api')
            ->prefix('api') // All API routes will be prefixed with 'api'
            ->group(base_path('routes/api.php')); // Load the API routes file

        Route::middleware('web')
            ->group(base_path('routes/web.php')); // Load the web routes file
    }
}
