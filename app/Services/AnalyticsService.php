<?php

namespace App\Services;

use App\Models\User;
use App\Models\Store;
use App\Models\Customer;
use App\Models\EyeExamination;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

/**
 * Analytics Service
 * 
 * Handles all analytics calculations for the admin panel.
 * 
 * @package App\Services
 */
class AnalyticsService
{
    /**
     * Get signup analytics.
     *
     * @return array
     */
    public function getSignupAnalytics(): array
    {
        $totalSignups = User::count();
        $signupsThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $signupsLastMonth = User::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        // Calculate growth percentage
        $growth = 0;
        if ($signupsLastMonth > 0) {
            $growth = round((($signupsThisMonth - $signupsLastMonth) / $signupsLastMonth) * 100, 1);
        } elseif ($signupsThisMonth > 0) {
            $growth = 100;
        }

        // Get signups by month for the last 12 months
        $signupsByMonth = User::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        return [
            'total' => $totalSignups,
            'this_month' => $signupsThisMonth,
            'last_month' => $signupsLastMonth,
            'growth' => $growth,
            'by_month' => $signupsByMonth,
        ];
    }

    /**
     * Get store creation analytics.
     *
     * @return array
     */
    public function getStoreAnalytics(): array
    {
        $totalStores = Store::count();
        $storesThisMonth = Store::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $storesLastMonth = Store::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        // Calculate growth percentage
        $growth = 0;
        if ($storesLastMonth > 0) {
            $growth = round((($storesThisMonth - $storesLastMonth) / $storesLastMonth) * 100, 1);
        } elseif ($storesThisMonth > 0) {
            $growth = 100;
        }

        // Get stores by month for the last 12 months
        $storesByMonth = Store::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        return [
            'total' => $totalStores,
            'this_month' => $storesThisMonth,
            'last_month' => $storesLastMonth,
            'growth' => $growth,
            'by_month' => $storesByMonth,
        ];
    }

    /**
     * Get store performance analytics.
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getStorePerformance(int $limit = 20)
    {
        return Store::with(['user', 'customers', 'eyeExaminations'])
            ->withCount([
                'customers',
                'eyeExaminations',
                'orders'
            ])
            ->withSum('orders', 'total_price')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($store) {
                return [
                    'id' => $store->id,
                    'name' => $store->name,
                    'user_name' => $store->user->name ?? 'N/A',
                    'user_email' => $store->user->email ?? 'N/A',
                    'created_at' => $store->created_at,
                    'customers_count' => $store->customers_count ?? 0,
                    'examinations_count' => $store->eye_examinations_count ?? 0,
                    'orders_count' => $store->orders_count ?? 0,
                    'total_revenue' => (float) ($store->orders_sum_total_price ?? 0),
                ];
            });
    }

    /**
     * Get spam accounts analytics.
     *
     * @return array
     */
    public function getSpamAnalytics(): array
    {
        $totalSpam = User::spam()->count();
        $totalUsers = User::count();
        $spamPercentage = $totalUsers > 0 ? round(($totalSpam / $totalUsers) * 100, 2) : 0;

        // Get spam accounts created this month
        $spamThisMonth = User::spam()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Get recent spam accounts
        $recentSpam = User::spam()
            ->with('role')
            ->latest()
            ->limit(20)
            ->get();

        return [
            'total' => $totalSpam,
            'percentage' => $spamPercentage,
            'this_month' => $spamThisMonth,
            'recent' => $recentSpam,
        ];
    }

    /**
     * Get overall analytics summary.
     *
     * @return array
     */
    public function getOverallAnalytics(): array
    {
        return [
            'signups' => $this->getSignupAnalytics(),
            'stores' => $this->getStoreAnalytics(),
            'spam' => $this->getSpamAnalytics(),
            'performance' => $this->getStorePerformance(10),
        ];
    }
}

