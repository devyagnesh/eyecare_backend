<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\HandlesAjaxResponses;
use App\Models\Order;
use App\Models\Store;
use App\Models\Customer;
use App\Services\OrderService;
use Illuminate\Http\Request;

/**
 * Order Controller
 * 
 * Handles admin panel requests for orders management.
 * 
 * @package App\Http\Controllers\Admin
 */
class OrderController extends Controller
{
    use HandlesAjaxResponses;

    /**
     * Create a new controller instance.
     *
     * @param OrderService $orderService
     */
    public function __construct(
        private OrderService $orderService
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->get('search'),
            'store_id' => $request->get('store_id'),
            'customer_id' => $request->get('customer_id'),
            'status' => $request->get('status'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_order' => $request->get('sort_order', 'desc'),
        ];

        // Build query for admin (all stores)
        $query = Order::with(['customer:id,name,email,phone_number', 'store:id,name', 'eyeExamination:id,exam_date']);

        // Search functionality - search by customer name, email, phone_number, or invoice_number
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%")
                                   ->orWhere('phone_number', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by store
        if (!empty($filters['store_id'])) {
            $query->where('store_id', $filters['store_id']);
        }

        // Filter by customer
        if (!empty($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }

        // Filter by status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by date range
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        // Sorting
        $sortBy = $filters['sort_by'];
        $sortOrder = $filters['sort_order'];
        
        $allowedSortFields = ['created_at', 'expected_completion_date', 'total_price', 'status'];
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'created_at';
        }
        
        $query->orderBy($sortBy, $sortOrder);

        $orders = $query->paginate(25)->withQueryString();
        $stores = Store::orderBy('name')->get();
        $customers = Customer::orderBy('name')->get();
        $statuses = ['pending', 'processing', 'completed', 'cancelled'];

        // If AJAX request, return only table HTML
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'html' => view('admin.orders.index', compact('orders', 'stores', 'customers', 'statuses', 'filters'))->render()
            ]);
        }

        return view('admin.orders.index', compact('orders', 'stores', 'customers', 'statuses', 'filters'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['customer', 'store.user', 'eyeExamination']);
        return view('admin.orders.show', compact('order'));
    }
}

