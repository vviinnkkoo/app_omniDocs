<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

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
        if (env('STATUSBAR_ENABLED', false)) {
            \DB::enableQueryLog();
        }

        // Share data with all views
        View::composer('*', function ($view) {
            $workYears = WorkYears::get()->sortBy('id'); // Years to be displayed in the frontend for invoices, payments etc.
            $appSettings = Settings::pluck('setting_value', 'setting_name' )->toArray(); // Settings to be used on various places.
            
            $view->with('workYears', $workYears)
                 ->with('appSettings', $appSettings);
        });

        // Force URL generation to use the current domain
        URL::forceRootUrl(request()->getSchemeAndHttpHost());

        // Set database based on current Host
        $hostname = request()->getHost(); // Get the current domain

        switch ($hostname) {
            case 'app.omnius.hr':
                Config::set('database.connections.mysql', [
                    'driver'    => 'mysql',
                    'host'      => env('DB_HOST', '127.0.0.1'),
                    'database'  => 'beatoaiw_app_omnius_art',  // Database for app.omnius.hr
                    'username'  => 'beatoaiw_production_omnius_app_1932913',
                    'password'  => '-m._xfoi.JE0',
                    'charset'   => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'prefix'    => '',
                    'strict'    => true,
                    'engine'    => null,
                ]);
                break;

            case 'app.svijetdekoracija.hr':
                Config::set('database.connections.mysql', [
                    'driver'    => 'mysql',
                    'host'      => env('DB_HOST', '127.0.0.1'),
                    'database'  => 'beatoaiw_app_svijet_dekoracija',  // Database for app.svijetdekoracija.hr
                    'username'  => 'beatoaiw_production_omnius_app_1932913',
                    'password'  => '-m._xfoi.JE0',
                    'charset'   => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'prefix'    => '',
                    'strict'    => true,
                    'engine'    => null,
                ]);
                break;
            case 'localhost':
                Config::set('database.connections.mysql', [
                    'driver'    => 'mysql',
                    'host'      => env('DB_HOST', '127.0.0.1'),
                    'database'  => 'accounting_app',  // Database for localhost
                    'username'  => 'root',
                    'password'  => '',
                    'charset'   => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'prefix'    => '',
                    'strict'    => true,
                    'engine'    => null,
                ]);
                break;
        }

        dd(env('STATUSBAR_ENABLED'));
    }
}
