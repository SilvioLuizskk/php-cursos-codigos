<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redis;

class ABStatsController extends Controller
{
    /**
     * Return exposures/conversions and conversion rate per variant for a test.
     */
    public function stats(Request $request)
    {
        $test = $request->query('test', 'default');

        $variants = ['A', 'B'];
        $data = [];
        foreach ($variants as $v) {
            $exposures = (int) Redis::get("ab:exposures:{$test}:{$v}") ?: 0;
            $conversions = (int) Redis::get("ab:conversions:{$test}:{$v}") ?: 0;
            $rate = $exposures > 0 ? round(($conversions / $exposures) * 100, 2) : null;
            $data[$v] = [
                'exposures' => $exposures,
                'conversions' => $conversions,
                'conversion_rate_percent' => $rate,
            ];
        }

        // Totals
        $totalExposures = array_sum(array_column($data, 'exposures'));
        $totalConversions = array_sum(array_column($data, 'conversions'));
        $overallRate = $totalExposures > 0 ? round(($totalConversions / $totalExposures) * 100, 2) : null;

        return response()->json([
            'test' => $test,
            'variants' => $data,
            'totals' => [
                'exposures' => $totalExposures,
                'conversions' => $totalConversions,
                'conversion_rate_percent' => $overallRate,
            ],
        ]);
    }
}
