<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ABDashboardController extends Controller
{
    /**
     * Dashboard de A/B Testing
     */
    public function index(): JsonResponse
    {
        // Este controller pode usar os serviços de A/B testing existentes
        // Por enquanto retorna dados mockados

        return response()->json([
            'active_tests' => [
                [
                    'id' => 1,
                    'name' => 'homepage_button',
                    'variants' => ['A', 'B'],
                    'status' => 'running',
                    'start_date' => '2025-01-01',
                    'participants' => 1250,
                    'conversion_rate' => 3.2,
                ],
                [
                    'id' => 2,
                    'name' => 'checkout_flow',
                    'variants' => ['A', 'B', 'C'],
                    'status' => 'running',
                    'start_date' => '2025-01-15',
                    'participants' => 890,
                    'conversion_rate' => 5.7,
                ],
            ],
            'recent_conversions' => [
                [
                    'test' => 'homepage_button',
                    'variant' => 'B',
                    'conversion_type' => 'click',
                    'timestamp' => now()->subMinutes(5),
                ],
                [
                    'test' => 'checkout_flow',
                    'variant' => 'A',
                    'conversion_type' => 'purchase',
                    'timestamp' => now()->subMinutes(15),
                ],
            ],
            'summary' => [
                'total_tests' => 2,
                'running_tests' => 2,
                'completed_tests' => 0,
                'total_participants' => 2140,
                'avg_conversion_rate' => 4.45,
            ]
        ]);
    }
}
