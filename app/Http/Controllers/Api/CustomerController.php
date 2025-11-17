<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCustomerRequest;
use App\Http\Requests\Api\UpdateCustomerRequest;
use App\Models\Customer;
use App\Models\Store;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private CustomerService $customerService
    ) {}

    /**
     * Get all customers for the authenticated user's store.
     * 
     * Retrieves a paginated list of customers with filtering and sorting options.
     * 
     * @queryParam paginated boolean Enable/disable pagination. Default: true. Example: true
     * @queryParam per_page integer Number of items per page. Default: 15, max: 100. Example: 15
     * @queryParam search string Search by name, email, or phone_number. Example: john
     * @queryParam name string Filter by exact name match. Example: John Doe
     * @queryParam email string Filter by exact email match. Example: john@example.com
     * @queryParam phone_number string Filter by exact phone_number match. Example: +1234567890
     * @queryParam created_from date Filter customers created from this date (YYYY-MM-DD). Example: 2025-01-01
     * @queryParam created_to date Filter customers created up to this date (YYYY-MM-DD). Example: 2025-12-31
     * @queryParam sort_by string Sort field (name, email, phone_number, created_at, updated_at). Default: created_at. Example: created_at
     * @queryParam sort_order string Sort order (asc, desc). Default: desc. Example: desc
     * 
     * @response 200 {
     *   "success": true,
     *   "data": {
     *     "customers": [
     *       {
     *         "id": 1,
     *         "name": "John Doe",
     *         "email": "john@example.com",
     *         "phone_number": "+1234567890",
     *         "created_at": "2025-01-01 00:00:00",
     *         "updated_at": "2025-01-01 00:00:00"
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
     * @response 403 {
     *   "success": false,
     *   "message": "Your account has been blocked. Please contact support."
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

        $filters = [
            'search' => $request->get('search'),
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'phone_number' => $request->get('phone_number'),
            'created_from' => $request->get('created_from'),
            'created_to' => $request->get('created_to'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_order' => $request->get('sort_order', 'desc'),
            'paginated' => filter_var($request->get('paginated', true), FILTER_VALIDATE_BOOLEAN),
            'per_page' => $request->get('per_page', 15),
        ];

        $customers = $this->customerService->getCustomers($store, $filters);

        if ($filters['paginated']) {
            $formattedCustomers = $customers->map(function ($customer) {
                return $this->customerService->formatCustomer($customer);
            });
            
            return response()->json([
                'success' => true,
                'data' => [
                    'customers' => $formattedCustomers->values()->all(),
                    'pagination' => [
                        'current_page' => $customers->currentPage(),
                        'last_page' => $customers->lastPage(),
                        'per_page' => $customers->perPage(),
                        'total' => $customers->total(),
                        'from' => $customers->firstItem(),
                        'to' => $customers->lastItem(),
                    ],
                ],
            ], 200);
        } else {
            $formattedCustomers = $customers->map(function ($customer) {
                return $this->customerService->formatCustomer($customer);
            });
            
            return response()->json([
                'success' => true,
                'data' => [
                    'customers' => $formattedCustomers->values()->all(),
                    'total' => $customers->count(),
                ],
            ], 200);
        }
    }

    /**
     * Create a new customer for the authenticated user's store.
     */
    public function store(StoreCustomerRequest $request)
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

        try {
            $customer = $this->customerService->createCustomer($store, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully.',
                'data' => [
                    'customer' => $this->customerService->formatCustomer($customer),
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Get a specific customer.
     */
    public function show(Request $request, $id)
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

        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found.',
            ], 404);
        }

        if ((int)$customer->store_id !== (int)$store->id) {
            return response()->json([
                'success' => false,
                'message' => 'Customer does not belong to your store.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'customer' => $this->customerService->formatCustomer($customer),
            ],
        ], 200);
    }

    /**
     * Update a customer.
     */
    public function update(UpdateCustomerRequest $request, $id)
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

        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found.',
            ], 404);
        }

        // Refresh customer to ensure we have latest data
        $customer->refresh();

        if ((int)$customer->store_id !== (int)$store->id) {
            \Log::warning('Customer store mismatch in controller', [
                'customer_id' => $customer->id,
                'customer_store_id' => $customer->store_id,
                'customer_store_id_type' => gettype($customer->store_id),
                'user_store_id' => $store->id,
                'user_store_id_type' => gettype($store->id),
                'user_id' => $user->id,
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Customer does not belong to your store.',
            ], 403);
        }

        try {
            $customer = $this->customerService->updateCustomer($customer, $store, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully.',
                'data' => [
                    'customer' => $this->customerService->formatCustomer($customer),
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Delete a customer.
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

        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found.',
            ], 404);
        }

        if ((int)$customer->store_id !== (int)$store->id) {
            return response()->json([
                'success' => false,
                'message' => 'Customer does not belong to your store.',
            ], 403);
        }

        try {
            $this->customerService->deleteCustomer($customer, $store);

            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }
}
