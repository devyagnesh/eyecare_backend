<?php

namespace App\Services;

use App\Models\Store;
use App\Models\Customer;
use App\Models\EyeExamination;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticsService
{
    /**
     * Get statistics for a store.
     *
     * @param Store $store
     * @param array $filters
     * @return array
     */
    public function getStatistics(Store $store, array $filters = []): array
    {
        $dateFilter = $this->buildDateFilter($filters);

        $totalCustomers = $this->getTotalCustomers($store, $dateFilter);
        $totalExaminations = $this->getTotalExaminations($store, $dateFilter);
        $totalOrders = $this->getTotalOrders($store, $dateFilter);

        // Get chart data
        $chartData = $this->getChartData($store, $filters);

        return [
            'total_customers' => $totalCustomers,
            'total_examinations' => $totalExaminations,
            'total_orders' => $totalOrders,
            'filter' => $this->getFilterDescription($filters),
            'chart_data' => $chartData,
        ];
    }

    /**
     * Get total customers count.
     *
     * @param Store $store
     * @param \Closure|null $dateFilter
     * @return int
     */
    private function getTotalCustomers(Store $store, ?\Closure $dateFilter = null): int
    {
        $query = Customer::where('store_id', $store->id);

        if ($dateFilter) {
            $dateFilter($query);
        }

        return $query->count();
    }

    /**
     * Get total examinations count.
     *
     * @param Store $store
     * @param \Closure|null $dateFilter
     * @return int
     */
    private function getTotalExaminations(Store $store, ?\Closure $dateFilter = null): int
    {
        $query = EyeExamination::where('store_id', $store->id);

        if ($dateFilter) {
            $dateFilter($query);
        }

        return $query->count();
    }

    /**
     * Get total orders count.
     *
     * @param Store $store
     * @param \Closure|null $dateFilter
     * @return int
     */
    private function getTotalOrders(Store $store, ?\Closure $dateFilter = null): int
    {
        $query = Order::where('store_id', $store->id);

        if ($dateFilter) {
            $dateFilter($query);
        }

        return $query->count();
    }

    /**
     * Build date filter closure based on filters.
     *
     * @param array $filters
     * @return \Closure|null
     */
    private function buildDateFilter(array $filters): ?\Closure
    {
        // Check for current_month filter
        if (isset($filters['current_month']) && filter_var($filters['current_month'], FILTER_VALIDATE_BOOLEAN)) {
            return function ($query) {
                $query->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
            };
        }

        // Check for date range
        if (isset($filters['start_date']) || isset($filters['end_date'])) {
            return function ($query) use ($filters) {
                if (isset($filters['start_date'])) {
                    $query->whereDate('created_at', '>=', $filters['start_date']);
                }
                if (isset($filters['end_date'])) {
                    $query->whereDate('created_at', '<=', $filters['end_date']);
                }
            };
        }

        // No filter - return all data
        return null;
    }

    /**
     * Get filter description for response.
     *
     * @param array $filters
     * @return string
     */
    private function getFilterDescription(array $filters): string
    {
        if (isset($filters['current_month']) && filter_var($filters['current_month'], FILTER_VALIDATE_BOOLEAN)) {
            return 'current_month';
        }

        if (isset($filters['start_date']) || isset($filters['end_date'])) {
            $start = $filters['start_date'] ?? 'all time';
            $end = $filters['end_date'] ?? 'all time';
            return "date_range: {$start} to {$end}";
        }

        return 'all';
    }

    /**
     * Get chart data for visualizations.
     *
     * @param Store $store
     * @param array $filters
     * @return array
     */
    private function getChartData(Store $store, array $filters = []): array
    {
        $groupBy = $this->determineGroupBy($filters);
        
        $customersData = $this->getChartDataForModel(Customer::class, $store->id, $filters, $groupBy);
        $examinationsData = $this->getChartDataForModel(EyeExamination::class, $store->id, $filters, $groupBy);
        $ordersData = $this->getChartDataForModel(Order::class, $store->id, $filters, $groupBy);

        return [
            'customers' => [
                'labels' => $customersData['labels'],
                'data' => $customersData['data'],
            ],
            'examinations' => [
                'labels' => $examinationsData['labels'],
                'data' => $examinationsData['data'],
            ],
            'orders' => [
                'labels' => $ordersData['labels'],
                'data' => $ordersData['data'],
            ],
        ];
    }

    /**
     * Get chart data for a specific model.
     *
     * @param string $modelClass
     * @param int $storeId
     * @param array $filters
     * @param string $groupBy
     * @return array
     */
    private function getChartDataForModel(string $modelClass, int $storeId, array $filters, string $groupBy): array
    {
        $query = $modelClass::where('store_id', $storeId);

        // Apply date filters
        if (isset($filters['current_month']) && filter_var($filters['current_month'], FILTER_VALIDATE_BOOLEAN)) {
            $query->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year);
        } else {
            if (isset($filters['start_date'])) {
                $query->whereDate('created_at', '>=', $filters['start_date']);
            }
            if (isset($filters['end_date'])) {
                $query->whereDate('created_at', '<=', $filters['end_date']);
            }
        }

        // Group by period
        if ($groupBy === 'day') {
            $data = $query->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get()
                ->map(function ($item) {
                    return [
                        'label' => Carbon::parse($item->date)->format('M d, Y'),
                        'value' => $item->count,
                    ];
                });
        } elseif ($groupBy === 'week') {
            $data = $query->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('WEEK(created_at) as week'),
                DB::raw('COUNT(*) as count')
            )
                ->groupBy('year', 'week')
                ->orderBy('year', 'asc')
                ->orderBy('week', 'asc')
                ->get()
                ->map(function ($item) {
                    $date = Carbon::now()->setISODate($item->year, $item->week, 1);
                    return [
                        'label' => 'Week ' . $item->week . ', ' . $date->format('Y'),
                        'value' => $item->count,
                    ];
                });
        } else { // month
            $data = $query->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
                ->groupBy('year', 'month')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get()
                ->map(function ($item) {
                    return [
                        'label' => Carbon::create($item->year, $item->month, 1)->format('M Y'),
                        'value' => $item->count,
                    ];
                });
        }

        return [
            'labels' => $data->pluck('label')->toArray(),
            'data' => $data->pluck('value')->toArray(),
        ];
    }

    /**
     * Determine the grouping period based on date range.
     *
     * @param array $filters
     * @return string 'day', 'week', or 'month'
     */
    private function determineGroupBy(array $filters): string
    {
        // If current month, use daily grouping
        if (isset($filters['current_month']) && filter_var($filters['current_month'], FILTER_VALIDATE_BOOLEAN)) {
            return 'day';
        }

        // If date range is provided, calculate the difference
        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $start = Carbon::parse($filters['start_date']);
            $end = Carbon::parse($filters['end_date']);
            $daysDiff = $start->diffInDays($end);

            // If range is 30 days or less, use daily grouping
            if ($daysDiff <= 30) {
                return 'day';
            }
            // If range is 90 days or less, use weekly grouping
            elseif ($daysDiff <= 90) {
                return 'week';
            }
            // Otherwise use monthly grouping
            else {
                return 'month';
            }
        }

        // If only start_date is provided, assume it's recent (use daily)
        if (isset($filters['start_date'])) {
            $start = Carbon::parse($filters['start_date']);
            $daysDiff = $start->diffInDays(Carbon::now());
            
            if ($daysDiff <= 30) {
                return 'day';
            } elseif ($daysDiff <= 90) {
                return 'week';
            } else {
                return 'month';
            }
        }

        // Default to monthly grouping for all-time data
        return 'month';
    }
}

