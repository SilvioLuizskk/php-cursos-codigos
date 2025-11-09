<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

class MetricsController extends Controller
{
    public function index()
    {
        try {
            $total = (int) Redis::get('metrics:requests:total') ?: 0;
            $errors = (int) Redis::get('metrics:requests:errors') ?: 0;
            $durationSum = (float) Redis::get('metrics:requests:duration_sum') ?: 0.0;
            $samples = Redis::lrange('metrics:requests:durations_samples', 0, -1) ?: [];

            $avg = $total > 0 ? ($durationSum / max(1, $total)) : 0.0;

            // Output Prometheus text format
            $lines = [];
            $lines[] = "# HELP app_requests_total Total number of requests";
            $lines[] = "# TYPE app_requests_total counter";
            $lines[] = "app_requests_total {$total}";

            $lines[] = "# HELP app_requests_errors Total number of request errors";
            $lines[] = "# TYPE app_requests_errors counter";
            $lines[] = "app_requests_errors {$errors}";

            $lines[] = "# HELP app_request_duration_seconds_average Average request duration seconds";
            $lines[] = "# TYPE app_request_duration_seconds_average gauge";
            $lines[] = "app_request_duration_seconds_average {$avg}";

            // simple quantiles from samples if available
            if (count($samples) > 0) {
                $floatSamples = array_map('floatval', $samples);
                sort($floatSamples);
                $p50 = $floatSamples[intval(count($floatSamples) * 0.5)];
                $p95 = $floatSamples[intval(count($floatSamples) * 0.95)];
                $lines[] = "app_request_duration_seconds_p50 {$p50}";
                $lines[] = "app_request_duration_seconds_p95 {$p95}";
            }

            // Export simple A/B metrics from Redis (exposures & conversions)
            $lines[] = "# HELP app_ab_exposures_total Total A/B exposures per test and variant";
            $lines[] = "# TYPE app_ab_exposures_total counter";
            $lines[] = "# HELP app_ab_conversions_total Total A/B conversions per test and variant";
            $lines[] = "# TYPE app_ab_conversions_total counter";

            try {
                $keys = Redis::keys('ab:exposures:*');
                foreach ($keys as $k) {
                    // key format: ab:exposures:<test>:<variant>
                    $parts = explode(':', $k);
                    if (count($parts) >= 4) {
                        $test = $parts[2];
                        $variant = $parts[3];
                        $val = (int) Redis::get($k);
                        $lines[] = "app_ab_exposures_total{test=\"{$test}\",variant=\"{$variant}\"} {$val}";
                    }
                }

                $ckeys = Redis::keys('ab:conversions:*');
                foreach ($ckeys as $k) {
                    $parts = explode(':', $k);
                    if (count($parts) >= 4) {
                        $test = $parts[2];
                        $variant = $parts[3];
                        $val = (int) Redis::get($k);
                        $lines[] = "app_ab_conversions_total{test=\"{$test}\",variant=\"{$variant}\"} {$val}";
                    }
                }
            } catch (\Exception $e) {
                // ignore AB metric errors
            }

            return response(implode("\n", $lines) . "\n", 200, ['Content-Type' => 'text/plain']);
        } catch (\Exception $e) {
            Log::error('Failed to read metrics: ' . $e->getMessage());
            return response("# metrics unavailable\n", 500, ['Content-Type' => 'text/plain']);
        }
    }
}
