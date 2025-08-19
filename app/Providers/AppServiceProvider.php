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
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Force URL generation to use the current domain
        URL::forceRootUrl(request()->getSchemeAndHttpHost());

        // Get hostname
        $hostname = request()->getHost();

        // Load domain config
        $domains = config('domains');

        if (isset($domains[$hostname])) {
            $config = $domains[$hostname];
            Config::set('database.connections.mysql.database', $config['database']);
            Config::set('database.connections.mysql.username', $config['username']);
            Config::set('database.connections.mysql.password', $config['password']);
        }

        // Set default connection and reconnect
        Config::set('database.default', 'mysql');
        DB::purge('mysql');
        DB::reconnect('mysql');

        // Pagination
        Paginator::useBootstrap();

        // Share data with all views
        View::composer('*', function ($view) {
            $workYears = WorkYears::orderBy('id')->get();
            $appSettings = Settings::pluck('setting_value', 'setting_name')->toArray();

            $view->with('workYears', $workYears)
                 ->with('appSettings', $appSettings);
        });
    }
}