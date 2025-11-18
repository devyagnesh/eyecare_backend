<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Services\StatisticsService;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private StatisticsService $statisticsService
    ) {}

    /**
     * Get statistics for the authenticated user's store.
     * 
     * Query Parameters:
     * - current_month (boolean): Filter to current month only (default: false)
     * - start_date (date): Filter from this date (YYYY-MM-DD)
     * - end_date (date): Filter up to this date (YYYY-MM-DD)
     * 
     * Note: If current_month is true, it takes precedence over date range.
     * If no filters are provided, returns all-time statistics.
     * 
     * Response includes:
     * - total_customers: Total count of customers
     * - total_examinations: Total count of examinations
     * - total_orders: Total count of orders
     * - filter: Description of applied filter
     * - chart_data: Time-series data formatted for charts with labels and data arrays
     *   - Chart data automatically groups by day (≤30 days), week (≤90 days), or month (>90 days)
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $store = Store::where('user_id', $user->id)->first();

        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found. Please create a store first.',
            ], 404);
        }

        $filters = [
            'current_month' => $request->get('current_month'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ];

        $statistics = $this->statisticsService->getStatistics($store, $filters);

        return response()->json([
            'success' => true,
            'data' => $statistics,
        ], 200);
    }
}

