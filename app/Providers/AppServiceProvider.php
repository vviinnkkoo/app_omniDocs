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

        // Query log if statusbar is enabled
        if (env('STATUSBAR_ENABLED', false)) {
            DB::enableQueryLog();
        }

        // Share data with all views, cache per connection
        $connection = DB::getDefaultConnection();

        View::composer('*', function ($view) use ($connection) {
            $workYears = Cache::remember("workYears_{$connection}", 60, fn() => WorkYears::orderBy('id')->get());
            $appSettings = Cache::remember("appSettings_{$connection}", 60, fn() => Settings::pluck('setting_value', 'setting_name')->toArray());

            $view->with('workYears', $workYears)
                 ->with('appSettings', $appSettings);
        });
    }
}