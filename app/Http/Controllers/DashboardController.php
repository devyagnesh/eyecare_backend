<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

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
        $stats = [
            'total_users' => User::count(),
            'total_roles' => Role::count(),
            'total_permissions' => Permission::count(),
            'active_roles' => Role::where('is_active', true)->count(),
            'active_permissions' => Permission::where('is_active', true)->count(),
            'users_this_month' => User::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'users_last_month' => User::whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year)
                ->count(),
            'recent_users' => User::with('role')->latest()->take(5)->get(),
        ];

        // Calculate growth percentage
        if ($stats['users_last_month'] > 0) {
            $stats['user_growth'] = round((($stats['users_this_month'] - $stats['users_last_month']) / $stats['users_last_month']) * 100, 1);
        } else {
            $stats['user_growth'] = $stats['users_this_month'] > 0 ? 100 : 0;
        }

        return view('dashboard.index', compact('stats'));
    }
}
