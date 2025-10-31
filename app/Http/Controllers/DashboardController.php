<?php

namespace App\Http\Controllers;

/**
 * Dashboard Controller
 * Handles the main dashboard view after authentication
 */
class DashboardController extends Controller
{
    /**
     * Display the dashboard
     */
    public function index()
    {
        return view('dashboard.index');
    }
}
