<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

/**
 * Analytics Controller
 * 
 * Handles admin panel requests for analytics and reporting.
 * 
 * @package App\Http\Controllers\Admin
 */
class AnalyticsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param AnalyticsService $analyticsService
     */
    public function __construct(
        private AnalyticsService $analyticsService
    ) {}

    /**
     * Display the analytics dashboard.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $filters = [
            'start_date' => $request->get('start_date'), // No default - show all data
            'end_date' => $request->get('end_date'), // No default - show all data
            'limit' => (int) $request->get('limit', 10),
        ];

        $analytics = $this->analyticsService->getOverallAnalytics($filters);

        return view('admin.analytics.index', compact('analytics', 'filters'));
    }
}

