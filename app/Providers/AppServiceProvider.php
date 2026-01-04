<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

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

            // Set database connection dynamically
            Config::set('database.connections.mysql.host', $config['host'] ?? config('database.connections.mysql.host'));
            Config::set('database.connections.mysql.database', $config['database']);
            Config::set('database.connections.mysql.username', $config['username']);
            Config::set('database.connections.mysql.password', $config['password']);

            // Refresh connection
            DB::purge('mysql');
            DB::reconnect('mysql');
        }

        // Pagination
        Paginator::useBootstrap();

        // Share data only with main layout
        View::composer('*', function ($view) use ($hostname) {

            // Add hostname to cache key to separate per domain
            $cacheKeyWorkYears = 'work_years_' . $hostname;
            $cacheKeySettings = 'app_settings_' . $hostname;

            // Cache work years 1h per domain
            $workYears = Cache::remember($cacheKeyWorkYears, 3600, function () {
                return WorkYears::orderBy('id')->get();
            });

            // Cache app settings 1h per domain
            $appSettings = Cache::remember($cacheKeySettings, 3600, function () {
                return Settings::pluck('setting_value', 'setting_name')->toArray();
            });

            $view->with('workYears', $workYears)
                 ->with('appSettings', $appSettings)
                 ->with('appHostname', $hostname);
        });
    }
}
