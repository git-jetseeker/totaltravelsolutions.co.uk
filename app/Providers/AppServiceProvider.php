<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $crmHelpers = app_path('helpers.php');
        if (is_file($crmHelpers)) {
            require_once $crmHelpers;
        }

        $brandHelpers = app_path('Helpers/parkingzone.php');
        if (is_file($brandHelpers)) {
            require_once $brandHelpers;
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
