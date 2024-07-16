<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;

use App\Models\WorkYears;
use App\Models\Settings;

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
        Paginator::useBootstrap(); // For Bootstrap 5

        // Share data with all views
        View::composer('*', function ($view) {
            $workYears = WorkYears::get()->sortBy('id'); // Years to be displayed in the frontend for invoices, payments etc.
            $appSettings = Settings::pluck('setting_value', 'setting_name' )->toArray(); // Settings to be used on various places.
            
            $view->with('workYears', $workYears)
                 ->with('appSettings', $appSettings);
        });
    }
}
