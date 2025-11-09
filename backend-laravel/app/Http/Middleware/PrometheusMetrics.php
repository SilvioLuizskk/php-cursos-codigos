<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

class PrometheusMetrics
{
    /**
     * Handle an incoming request.
     * We increment counters in Redis to expose later on /metrics
     */
    public function handle(Request $request, Closure $next)
    {
        $start = microtime(true);
        try {
            $response = $next($request);
        } catch (\Exception $e) {
            // Increment errors counter
            try {
                Redis::incr('metrics:requests:errors');
                Redis::incr('metrics:requests:total');
            } catch (\Exception $ex) {
                Log::warning('Redis unavailable for metrics');
            }
            throw $e;
        }

        $duration = microtime(true) - $start;
        try {
            Redis::incr('metrics:requests:total');
            // sum durations
            Redis::incrbyfloat('metrics:requests:duration_sum', $duration);
            // sample count for histogram
            Redis::rpush('metrics:requests:durations_samples', $duration);
        } catch (\Exception $ex) {
            Log::warning('Redis unavailable for metrics');
        }

        return $response;
    }
}
