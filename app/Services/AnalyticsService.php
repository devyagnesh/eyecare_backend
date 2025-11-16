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
     * @param array|int $filtersOrLimit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getStorePerformance($filtersOrLimit = 20)
    {
        // Handle both old signature (int) and new signature (array)
        if (is_int($filtersOrLimit)) {
            $limit = $filtersOrLimit;
            $filters = [];
        } else {
            $filters = $filtersOrLimit;
            $limit = $filters['limit'] ?? 20;
        }

        $query = Store::with(['user', 'customers', 'eyeExaminations'])
            ->withCount([
                'customers',
                'eyeExaminations',
                'orders'
            ])
            ->withSum('orders', 'total_price');

        if (isset($filters['start_date'])) {
            $query->where('created_at', '>=', $filters['start_date']);
        }
        if (isset($filters['end_date'])) {
            $query->where('created_at', '<=', $filters['end_date']);
        }

        return $query->orderBy('created_at', 'desc')
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
     * @param array $filters
     * @return array
     */
    public function getOverallAnalytics(array $filters = []): array
    {
        return [
            'signups' => $this->getSignupAnalytics($filters),
            'stores' => $this->getStoreAnalytics($filters),
            'spam' => $this->getSpamAnalytics($filters),
            'performance' => $this->getStorePerformance($filters),
            'chart_data' => $this->getChartData($filters),
        ];
    }

    /**
     * Get chart data for visualizations.
     *
     * @param array $filters
     * @return array
     */
    public function getChartData(array $filters = []): array
    {
        $startDate = $filters['start_date'] ?? now()->subMonths(6)->startOfMonth();
        $endDate = $filters['end_date'] ?? now()->endOfMonth();
        
        if (is_string($startDate)) {
            $startDate = \Carbon\Carbon::parse($startDate);
        }
        if (is_string($endDate)) {
            $endDate = \Carbon\Carbon::parse($endDate);
        }

        // Get signups by month
        $signupsData = User::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => \Carbon\Carbon::create($item->year, $item->month, 1)->format('M Y'),
                    'value' => $item->count,
                ];
            });

        // Get stores by month
        $storesData = Store::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => \Carbon\Carbon::create($item->year, $item->month, 1)->format('M Y'),
                    'value' => $item->count,
                ];
            });

        // Get spam accounts by month
        $spamData = User::spam()
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => \Carbon\Carbon::create($item->year, $item->month, 1)->format('M Y'),
                    'value' => $item->count,
                ];
            });

        return [
            'signups' => [
                'labels' => $signupsData->pluck('label')->toArray(),
                'data' => $signupsData->pluck('value')->toArray(),
            ],
            'stores' => [
                'labels' => $storesData->pluck('label')->toArray(),
                'data' => $storesData->pluck('value')->toArray(),
            ],
            'spam' => [
                'labels' => $spamData->pluck('label')->toArray(),
                'data' => $spamData->pluck('value')->toArray(),
            ],
        ];
    }

    /**
     * Get signup analytics with filters.
     *
     * @param array $filters
     * @return array
     */
    public function getSignupAnalytics(array $filters = []): array
    {
        $query = User::query();
        
        if (isset($filters['start_date'])) {
            $query->where('created_at', '>=', $filters['start_date']);
        }
        if (isset($filters['end_date'])) {
            $query->where('created_at', '<=', $filters['end_date']);
        }

        $totalSignups = (clone $query)->count();
        $signupsThisMonth = (clone $query)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $signupsLastMonth = (clone $query)
            ->whereMonth('created_at', now()->subMonth()->month)
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
        $signupsByMonth = (clone $query)
            ->select(
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
     * Get store creation analytics with filters.
     *
     * @param array $filters
     * @return array
     */
    public function getStoreAnalytics(array $filters = []): array
    {
        $query = Store::query();
        
        if (isset($filters['start_date'])) {
            $query->where('created_at', '>=', $filters['start_date']);
        }
        if (isset($filters['end_date'])) {
            $query->where('created_at', '<=', $filters['end_date']);
        }

        $totalStores = (clone $query)->count();
        $storesThisMonth = (clone $query)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $storesLastMonth = (clone $query)
            ->whereMonth('created_at', now()->subMonth()->month)
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
        $storesByMonth = (clone $query)
            ->select(
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
     * Get spam accounts analytics with filters.
     *
     * @param array $filters
     * @return array
     */
    public function getSpamAnalytics(array $filters = []): array
    {
        $query = User::spam();
        
        if (isset($filters['start_date'])) {
            $query->where('created_at', '>=', $filters['start_date']);
        }
        if (isset($filters['end_date'])) {
            $query->where('created_at', '<=', $filters['end_date']);
        }

        $totalSpam = (clone $query)->count();
        $totalUsers = User::count();
        $spamPercentage = $totalUsers > 0 ? round(($totalSpam / $totalUsers) * 100, 2) : 0;

        // Get spam accounts created this month
        $spamThisMonth = (clone $query)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Get recent spam accounts
        $recentSpam = (clone $query)
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
}

