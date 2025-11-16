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
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $analytics = $this->analyticsService->getOverallAnalytics();

        return view('admin.analytics.index', compact('analytics'));
    }
}

