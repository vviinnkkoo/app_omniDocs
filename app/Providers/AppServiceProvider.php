<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

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
        // Force URL generation to use the current domain
        URL::forceRootUrl(request()->getSchemeAndHttpHost());

        // Set database based on current Host
        $hostname = request()->getHost();

        switch ($hostname) {
            case 'app.omnius.hr':
                Config::set('database.connections.mysql.database', 'beatoaiw_app_omnius_art');
                Config::set('database.connections.mysql.username', 'beatoaiw_production_omnius_app_1932913');
                Config::set('database.connections.mysql.password', '-m._xfoi.JE0');
                break;

            case 'app.svijetdekoracija.hr':
                Config::set('database.connections.mysql.database', 'beatoaiw_app_svijet_dekoracija');
                Config::set('database.connections.mysql.username', 'beatoaiw_production_omnius_app_1932913');
                Config::set('database.connections.mysql.password', '-m._xfoi.JE0');
                break;

            case 'localhost':
                Config::set('database.connections.mysql.database', 'accounting_app');
                Config::set('database.connections.mysql.username', 'root');
                Config::set('database.connections.mysql.password', '');
                break;
        }

        // Postavi default connection i resetiraj ga
        Config::set('database.default', 'mysql');
        DB::purge('mysql');
        DB::reconnect('mysql');

        // Pagination
        Paginator::useBootstrap();

        // Query log ako je omogućen
        if (env('STATUSBAR_ENABLED', false)) {
            DB::enableQueryLog();
        }

        // Share data with all views
        View::composer('*', function ($view) {
            $workYears = WorkYears::get()->sortBy('id'); 
            $appSettings = Settings::pluck('setting_value', 'setting_name')->toArray();

            $view->with('workYears', $workYears)
                 ->with('appSettings', $appSettings);
        });
    }
}