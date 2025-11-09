<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redis;

class ABAdminController extends Controller
{
    /**
     * Return weights for a test (JSON). If none set, returns default 50/50.
     */
    public function getWeights(Request $request)
    {
        $test = $request->query('test', 'default');
        $weightsKey = "ab:weights:{$test}";
        $weightsJson = Redis::get($weightsKey);
        if ($weightsJson) {
            $decoded = json_decode($weightsJson, true);
            if (is_array($decoded) && isset($decoded['A']) && isset($decoded['B'])) {
                return response()->json(['test' => $test, 'weights' => $decoded]);
            }
        }
        return response()->json(['test' => $test, 'weights' => ['A' => 50, 'B' => 50]]);
    }

    /**
     * Set weights for a test. Expects { test: 'name', weights: { A:70, B:30 } }
     */
    public function setWeights(Request $request)
    {
        $payload = $request->only(['test', 'weights']);
        $test = $payload['test'] ?? null;
        $weights = $payload['weights'] ?? null;

        if (!$test || !is_array($weights) || !isset($weights['A']) || !isset($weights['B'])) {
            return response()->json(['ok' => false, 'message' => 'invalid payload'], 400);
        }

        $a = (int)$weights['A'];
        $b = (int)$weights['B'];
        if ($a < 0 || $b < 0 || ($a + $b) === 0) {
            return response()->json(['ok' => false, 'message' => 'invalid weights'], 400);
        }

        $weightsKey = "ab:weights:{$test}";
        Redis::set($weightsKey, json_encode(['A' => $a, 'B' => $b]));

        return response()->json(['ok' => true, 'test' => $test, 'weights' => ['A' => $a, 'B' => $b]]);
    }
}
