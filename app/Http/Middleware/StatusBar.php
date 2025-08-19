<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatusBar
{
    public function handle(Request $request, Closure $next)
    {
        // Aktiviramo DB query log odmah
        if (env('STATUSBAR_ENABLED', false)) {
            DB::enableQueryLog();
        }

        $start = microtime(true);

        $response = $next($request);

        if (env('STATUSBAR_ENABLED', false)) {
            $queryLog = DB::getQueryLog();
            $queryCount = count($queryLog);
            $queryTime = collect($queryLog)->sum(fn($q) => $q['time']);
            $memory = memory_get_peak_usage(true);
            $overhead = microtime(true) - LARAVEL_START;

            $statusBarHtml = "
                <div style='width:100%;background:#222;color:#fff;font-size:12px;padding:5px 10px;position:fixed;bottom:0;left:0;z-index:9999;box-shadow:0 -2px 5px rgba(0,0,0,0.3);'>
                    Queries: {$queryCount} | Query time: " . round($queryTime, 2) . " ms |
                    Memory usage: " . round($memory / 1024 / 1024, 2) . " MB |
                    Total time: " . round($overhead, 2) . " s
                </div>
            ";

            if ($response->headers->get('Content-Type') && str_contains($response->headers->get('Content-Type'), 'text/html')) {
                $content = $response->getContent();
                $content = preg_replace('/(<body[^>]*>)/i', '$1'.$statusBarHtml, $content, 1);
                $response->setContent($content);
            }
        }

        return $response;
    }
}