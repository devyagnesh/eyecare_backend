<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreOrderRequest;
use App\Http\Requests\Api\UpdateOrderStatusRequest;
use App\Models\Store;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private OrderService $orderService
    ) {}

    /**
     * Get all orders for the authenticated user's store.
     * 
     * Retrieves a paginated list of orders with filtering and sorting options.
     * Search by customer name, email, phone number, or invoice number.
     * 
     * @queryParam search string Search by customer name, email, phone number, or invoice number. Example: john
     * @queryParam paginated boolean Enable/disable pagination. Default: true. Example: true
     * @queryParam per_page integer Number of items per page when paginated=true. Default: 15, max: 100. Example: 15
     * @queryParam customer_id integer Filter by customer ID. Example: 1
     * @queryParam status string Filter by status (pending, processing, completed, cancelled). Example: pending
     * @queryParam date_from date Filter orders from this date (YYYY-MM-DD). Example: 2025-01-01
     * @queryParam date_to date Filter orders up to this date (YYYY-MM-DD). Example: 2025-12-31
     * @queryParam sort_by string Sort field (created_at, expected_completion_date, total_price, status). Default: created_at. Example: created_at
     * @queryParam sort_order string Sort order (asc, desc). Default: desc. Example: desc
     * 
     * @response 200 {
     *   "success": true,
     *   "data": {
     *     "orders": [
     *       {
     *         "id": 1,
     *         "invoice_number": "INV-ABC-202511-0001",
     *         "customer": {
     *           "id": 1,
     *           "name": "Jane Smith",
     *           "email": "jane.smith@example.com",
     *           "phone_number": "+1987654321"
     *         },
     *         "eye_examination": {
     *           "id": 5,
     *           "exam_date": "2025-11-10"
     *         },
     *         "frame_photos": [
     *           "http://example.com/storage/orders/1/INV-ABC-202511-0001/frame-1234567890.jpg"
     *         ],
     *         "glass_details": "Progressive lenses, anti-glare coating, blue light filter",
     *         "total_price": 2500.00,
     *         "expected_completion_date": "2025-12-01",
     *         "status": "pending",
     *         "invoice_pdf_url": "http://example.com/storage/invoices/1/INV-ABC-202511-0001/invoice-INV-ABC-202511-0001.pdf",
     *         "notes": "Customer prefers thinner frames",
     *         "created_at": "2025-11-14 17:00:00",
     *         "updated_at": "2025-11-14 17:00:00"
     *       }
     *     ],
     *     "pagination": {
     *       "current_page": 1,
     *       "last_page": 1,
     *       "per_page": 15,
     *       "total": 1,
     *       "from": 1,
     *       "to": 1
     *     }
     *   }
     * }
     * 
     * @response 404 {
     *   "success": false,
     *   "message": "Store not found. Please create a store first."
     * }
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
            'search' => $request->get('search'),
            'customer_id' => $request->get('customer_id'),
            'status' => $request->get('status'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_order' => $request->get('sort_order', 'desc'),
            'paginated' => filter_var($request->get('paginated', true), FILTER_VALIDATE_BOOLEAN),
            'per_page' => $request->get('per_page', 15),
        ];

        $orders = $this->orderService->getOrders($store, $filters);

        if ($filters['paginated']) {
            $formattedOrders = $orders->map(function ($order) {
                return $this->orderService->formatOrder($order);
            });
            
            return response()->json([
                'success' => true,
                'data' => [
                    'orders' => $formattedOrders->values()->all(),
                    'pagination' => [
                        'current_page' => $orders->currentPage(),
                        'last_page' => $orders->lastPage(),
                        'per_page' => $orders->perPage(),
                        'total' => $orders->total(),
                        'from' => $orders->firstItem(),
                        'to' => $orders->lastItem(),
                    ],
                ],
            ], 200);
        } else {
            $formattedOrders = $orders->map(function ($order) {
                return $this->orderService->formatOrder($order);
            });
            
            return response()->json([
                'success' => true,
                'data' => [
                    'orders' => $formattedOrders->values()->all(),
                    'total' => $orders->count(),
                ],
            ], 200);
        }
    }

    /**
     * Create a new order with frame photo upload and generate invoice.
     * 
     * This endpoint accepts multipart/form-data for file uploads.
     * Invoice PDF is automatically generated upon order creation.
     * 
     * @response 201 {
     *   "success": true,
     *   "message": "Order created successfully and invoice generated.",
     *   "data": {
     *     "order": {
     *       "id": 1,
     *       "invoice_number": "INV-ABC-202511-0001",
     *       "customer": {
     *         "id": 1,
     *         "name": "Jane Smith",
     *         "email": "jane.smith@example.com",
     *         "phone_number": "+1987654321"
     *       },
     *       "eye_examination": {
     *         "id": 5,
     *         "exam_date": "2025-11-10"
     *       },
     *       "frame_photos": [
     *         "http://example.com/storage/orders/1/INV-ABC-202511-0001/frame-1234567890.jpg"
     *       ],
     *       "glass_details": "Progressive lenses, anti-glare coating, blue light filter",
     *       "total_price": 2500.00,
     *       "expected_completion_date": "2025-12-01",
     *       "status": "pending",
     *       "invoice_pdf_url": "http://example.com/storage/invoices/1/INV-ABC-202511-0001/invoice-INV-ABC-202511-0001.pdf",
     *       "notes": "Customer prefers thinner frames",
     *       "created_at": "2025-11-14 17:00:00",
     *       "updated_at": "2025-11-14 17:00:00"
     *     }
     *   }
     * }
     * 
     * @response 400 {
     *   "success": false,
     *   "message": "The provided data is invalid.",
     *   "errors": {
     *     "customer_id": ["Customer is required."],
     *     "total_price": ["Total price is required."]
     *   }
     * }
     * 
     * @response 404 {
     *   "success": false,
     *   "message": "Store not found. Please create a store first."
     * }
     * 
     * @response 500 {
     *   "success": false,
     *   "message": "Failed to create order."
     * }
     */
    public function store(StoreOrderRequest $request)
    {
        $user = $request->user();
        
        $store = Store::where('user_id', $user->id)->first();

        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found. Please create a store first.',
            ], 404);
        }

        try {
            $data = $request->validated();
            
            // Add frame photos files if uploaded
            if ($request->hasFile('frame_photos')) {
                $data['frame_photos'] = $request->file('frame_photos');
            }

            $order = $this->orderService->createOrder($store, $data);

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully and invoice generated.',
                'data' => [
                    'order' => $this->orderService->formatOrder($order),
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order.',
            ], 500);
        }
    }

    /**
     * Get a specific order by ID.
     * 
     * Retrieves detailed information about a specific order including customer,
     * eye examination, frame photos, and invoice details.
     * 
     * @urlParam id integer required The ID of the order. Example: 1
     * 
     * @response 200 {
     *   "success": true,
     *   "data": {
     *     "order": {
     *       "id": 1,
     *       "invoice_number": "INV-ABC-202511-0001",
     *       "customer": {
     *         "id": 1,
     *         "name": "Jane Smith",
     *         "email": "jane.smith@example.com",
     *         "phone_number": "+1987654321"
     *       },
     *       "eye_examination": {
     *         "id": 5,
     *         "exam_date": "2025-11-10"
     *       },
     *       "frame_photos": [
     *         "http://example.com/storage/orders/1/INV-ABC-202511-0001/frame-1234567890.jpg"
     *       ],
     *       "glass_details": "Progressive lenses, anti-glare coating, blue light filter",
     *       "total_price": 2500.00,
     *       "expected_completion_date": "2025-12-01",
     *       "status": "pending",
     *       "invoice_pdf_url": "http://example.com/storage/invoices/1/INV-ABC-202511-0001/invoice-INV-ABC-202511-0001.pdf",
     *       "notes": "Customer prefers thinner frames",
     *       "created_at": "2025-11-14 17:00:00",
     *       "updated_at": "2025-11-14 17:00:00"
     *     }
     *   }
     * }
     * 
     * @response 404 {
     *   "success": false,
     *   "message": "Order not found."
     * }
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        
        $store = Store::where('user_id', $user->id)->first();

        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found. Please create a store first.',
            ], 404);
        }

        $order = \App\Models\Order::where('id', $id)
            ->where('store_id', $store->id)
            ->with(['customer', 'eyeExamination'])
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'order' => $this->orderService->formatOrder($order),
            ],
        ], 200);
    }

    /**
     * Download invoice PDF for an order.
     * 
     * Returns the PDF file directly for download.
     * 
     * @urlParam id integer required The ID of the order. Example: 1
     * 
     * @response 200 The PDF file is returned as a download.
     * 
     * @response 404 {
     *   "success": false,
     *   "message": "Invoice PDF not found."
     * }
     */
    public function downloadInvoice(Request $request, $id)
    {
        $user = $request->user();
        
        $store = Store::where('user_id', $user->id)->first();

        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found.',
            ], 404);
        }

        $order = \App\Models\Order::where('id', $id)
            ->where('store_id', $store->id)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        if (!$order->invoice_pdf_path || !Storage::disk('public')->exists($order->invoice_pdf_path)) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice PDF not found.',
            ], 404);
        }

        return Storage::disk('public')->download(
            $order->invoice_pdf_path,
            'invoice-' . $order->invoice_number . '.pdf'
        );
    }

    /**
     * Update order status.
     * 
     * Updates the status of an order. Valid statuses: pending, processing, completed, cancelled.
     * 
     * @urlParam id integer required The ID of the order. Example: 1
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Order status updated successfully.",
     *   "data": {
     *     "order": {
     *       "id": 1,
     *       "invoice_number": "INV-ABC-202511-0001",
     *       "status": "processing",
     *       "customer": {
     *         "id": 1,
     *         "name": "Jane Smith",
     *         "email": "jane.smith@example.com"
     *       },
     *       "total_price": 2500.00,
     *       "created_at": "2025-11-14 17:00:00",
     *       "updated_at": "2025-11-14 17:00:00"
     *     }
     *   }
     * }
     * 
     * @response 403 {
     *   "success": false,
     *   "message": "Your account has been blocked. Please contact support."
     * }
     * 
     * @response 404 {
     *   "success": false,
     *   "message": "Order not found."
     * }
     * 
     * @response 422 {
     *   "success": false,
     *   "message": "The provided data is invalid.",
     *   "errors": {
     *     "status": ["The selected status is invalid."]
     *   }
     * }
     * 
     * @response 500 {
     *   "success": false,
     *   "message": "Failed to update order status."
     * }
     */
    public function updateStatus(UpdateOrderStatusRequest $request, $id)
    {
        $user = $request->user();
        
        $store = Store::where('user_id', $user->id)->first();

        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found. Please create a store first.',
            ], 404);
        }

        // Check if user is blocked
        if ($user->is_blocked) {
            return response()->json([
                'success' => false,
                'message' => 'Your account has been blocked. Please contact support.',
            ], 403);
        }

        // Check if store is active
        if (!$store->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Your store has been deactivated. Please contact support.',
            ], 403);
        }

        $order = \App\Models\Order::where('id', $id)
            ->where('store_id', $store->id)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        try {
            $order = $this->orderService->updateOrderStatus($order, $request->validated()['status']);

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully.',
                'data' => [
                    'order' => $this->orderService->formatOrder($order),
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order status.',
            ], 500);
        }
    }

    /**
     * Delete an order.
     * 
     * Soft deletes an order. The order can be restored if needed.
     * 
     * @urlParam id integer required The ID of the order. Example: 1
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Order deleted successfully."
     * }
     * 
     * @response 403 {
     *   "success": false,
     *   "message": "Your account has been blocked. Please contact support."
     * }
     * 
     * @response 404 {
     *   "success": false,
     *   "message": "Order not found."
     * }
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        
        // Check if user is blocked
        if ($user->is_blocked) {
            return response()->json([
                'success' => false,
                'message' => 'Your account has been blocked. Please contact support.',
            ], 403);
        }
        
        $store = Store::where('user_id', $user->id)->first();

        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found. Please create a store first.',
            ], 404);
        }

        // Check if store is active
        if (!$store->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Your store has been deactivated. Please contact support.',
            ], 403);
        }

        $order = \App\Models\Order::where('id', $id)
            ->where('store_id', $store->id)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        try {
            $this->orderService->deleteOrder($order, $store);

            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }
}
