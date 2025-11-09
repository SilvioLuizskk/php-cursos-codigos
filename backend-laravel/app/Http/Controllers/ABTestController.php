<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redis;

class ABTestController extends Controller
{
    /**
     * Assign or return an A/B variant for the visitor for a given test.
     * Stores assignment in Redis per-visitor (via cookie) for 30 days.
     */
    public function getVariant(Request $request)
    {
        $test = $request->query('test', 'default');

        // Identify visitor by cookie or create one
        $visitor = $request->cookie('ab_visitor');
        if (!$visitor) {
            try {
                $visitor = bin2hex(random_bytes(8));
            } catch (\Exception $e) {
                // fallback
                $visitor = uniqid('v_', true);
            }
        }

        $assignKey = "ab:assign:{$test}:{$visitor}";
        $variant = Redis::get($assignKey);

        if (!$variant) {
            // check for configured weights in Redis (e.g., JSON or hash)
            $weightsKey = "ab:weights:{$test}";
            $weightsJson = Redis::get($weightsKey);
            $weights = null;
            if ($weightsJson) {
                // expect JSON like {"A":70,"B":30}
                try {
                    $decoded = json_decode($weightsJson, true);
                    if (is_array($decoded) && isset($decoded['A']) && isset($decoded['B'])) {
                        $weights = [ 'A' => (int)$decoded['A'], 'B' => (int)$decoded['B'] ];
                    }
                } catch (\Exception $e) {
                    $weights = null;
                }
            }

            if (!$weights) {
                // default 50/50
                $weights = ['A' => 50, 'B' => 50];
            }

            // weighted random selection
            $rand = random_int(1, array_sum($weights));
            $acc = 0;
            $chosen = 'A';
            foreach ($weights as $k => $w) {
                $acc += $w;
                if ($rand <= $acc) {
                    $chosen = $k;
                    break;
                }
            }

            $variant = $chosen;
            // persist assignment for 30 days
            Redis::setex($assignKey, 60 * 60 * 24 * 30, $variant);
            // increment exposure counter
            Redis::incr("ab:exposures:{$test}:{$variant}");
        }

        return response()->json(['variant' => $variant])->cookie('ab_visitor', $visitor, 60 * 24 * 30);
    }

    /**
     * Record a conversion for a test+variant.
     */
    public function recordConversion(Request $request)
    {
        $payload = $request->only(['test', 'variant']);
        $test = $payload['test'] ?? null;
        $variant = $payload['variant'] ?? null;

        if (!$test || !$variant) {
            return response()->json(['ok' => false, 'message' => 'missing test or variant'], 400);
        }

        $key = "ab:conversions:{$test}:{$variant}";
        Redis::incr($key);

        return response()->json(['ok' => true]);
    }
}
