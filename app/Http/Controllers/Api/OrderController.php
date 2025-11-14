<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreOrderRequest;
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
     * Query Parameters:
     * - paginated (boolean): Enable/disable pagination (default: true)
     * - per_page (integer): Number of items per page when paginated=true (default: 15, max: 100)
     * - customer_id (integer): Filter by customer ID
     * - status (string): Filter by status (pending, processing, completed, cancelled)
     * - date_from (date): Filter orders from this date (YYYY-MM-DD)
     * - date_to (date): Filter orders up to this date (YYYY-MM-DD)
     * - sort_by (string): Sort field (created_at, expected_completion_date, total_price, status) - default: created_at
     * - sort_order (string): Sort order (asc, desc) - default: desc
     * 
     * @example payload
     * GET /api/orders?customer_id=1&status=pending&paginated=true&per_page=15
     * 
     * @example success_response
     * {
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
     *         "frame_photo": "http://example.com/storage/orders/1/INV-ABC-202511-0001/frame-1234567890.jpg",
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
     * @status 200 Success
     * @status 404 Store not found
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
     * Content-Type: multipart/form-data
     * 
     * @example payload
     * POST /api/orders
     * Content-Type: multipart/form-data
     * 
     * {
     *   "customer_id": 1,
     *   "eye_examination_id": 5,
     *   "frame_photo": [file upload - JPEG, PNG, WebP, max 5MB],
     *   "glass_details": "Progressive lenses, anti-glare coating, blue light filter",
     *   "total_price": 2500.00,
     *   "expected_completion_date": "2025-12-01",
     *   "status": "pending",
     *   "notes": "Customer prefers thinner frames"
     * }
     * 
     * @example success_response
     * {
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
     *       "frame_photo": "http://example.com/storage/orders/1/INV-ABC-202511-0001/frame-1234567890.jpg",
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
     * @example error_response
     * {
     *   "success": false,
     *   "message": "The provided data is invalid.",
     *   "errors": {
     *     "customer_id": ["Customer is required."],
     *     "total_price": ["Total price is required."]
     *   }
     * }
     * 
     * @status 200 Order created successfully
     * @status 400 Validation error
     * @status 404 Store or customer not found
     * @status 500 Server error
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
            
            // Add frame photo file if uploaded
            if ($request->hasFile('frame_photo')) {
                $data['frame_photo'] = $request->file('frame_photo');
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
     * @example payload
     * GET /api/orders/1
     * 
     * @example success_response
     * {
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
     *       "frame_photo": "http://example.com/storage/orders/1/INV-ABC-202511-0001/frame-1234567890.jpg",
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
     * @status 200 Success
     * @status 404 Order or store not found
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
     * @example payload
     * GET /api/orders/1/download-invoice
     * 
     * @example success_response
     * [PDF file download]
     * 
     * @status 200 PDF file download
     * @status 404 Order or invoice PDF not found
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
}
