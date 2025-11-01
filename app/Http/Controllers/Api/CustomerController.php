<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Store;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Get all customers for the authenticated user's store.
     * 
     * Query Parameters:
     * - paginated (boolean): Enable/disable pagination (default: true)
     * - per_page (integer): Number of items per page (default: 15, max: 100)
     * - search (string): Search by name, email, or phone_number
     * - name (string): Filter by exact name match
     * - email (string): Filter by exact email match
     * - phone_number (string): Filter by exact phone_number match
     * - created_from (date): Filter customers created from this date (YYYY-MM-DD)
     * - created_to (date): Filter customers created up to this date (YYYY-MM-DD)
     * - sort_by (string): Sort field (name, email, phone_number, created_at, updated_at) - default: created_at
     * - sort_order (string): Sort order (asc, desc) - default: desc
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

        $query = Customer::where('store_id', $store->id);

        // Search functionality (searches in name, email, and phone_number)
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        // Filter by exact name match
        if ($request->has('name') && !empty($request->name)) {
            $query->where('name', $request->name);
        }

        // Filter by exact email match
        if ($request->has('email') && !empty($request->email)) {
            $query->where('email', $request->email);
        }

        // Filter by exact phone_number match
        if ($request->has('phone_number') && !empty($request->phone_number)) {
            $query->where('phone_number', $request->phone_number);
        }

        // Filter by date range (created_at)
        if ($request->has('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }
        if ($request->has('created_to')) {
            $query->whereDate('created_at', '<=', $request->created_to);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        // Validate sort_by field
        $allowedSortFields = ['name', 'email', 'phone_number', 'created_at', 'updated_at'];
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'created_at';
        }
        
        // Validate sort_order
        $sortOrder = strtolower($sortOrder);
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }
        
        $query->orderBy($sortBy, $sortOrder);

        // Pagination toggle
        $paginated = filter_var($request->get('paginated', true), FILTER_VALIDATE_BOOLEAN);
        
        if ($paginated) {
            $perPage = (int) $request->get('per_page', 15);
            // Limit per_page to max 100
            $perPage = min(max($perPage, 1), 100);
            
            $customers = $query->paginate($perPage);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'customers' => $customers->items(),
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
            $customers = $query->get();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'customers' => $customers,
                    'total' => $customers->count(),
                ],
            ], 200);
        }
    }

    /**
     * Create a new customer for the authenticated user's store.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        
        $store = Store::where('user_id', $user->id)->first();

        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found. Please create a store first.',
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone_number' => 'required|string|max:255',
            'address' => 'nullable|string',
        ]);

        $customer = Customer::create([
            'store_id' => $store->id,
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Customer created successfully.',
            'data' => [
                'customer' => [
                    'id' => $customer->id,
                    'store_id' => $customer->store_id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'phone_number' => $customer->phone_number,
                    'address' => $customer->address,
                    'created_at' => $customer->created_at->toIso8601String(),
                    'updated_at' => $customer->updated_at->toIso8601String(),
                ],
            ],
        ], 201);
    }

    /**
     * Get a specific customer.
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

        $customer = Customer::where('store_id', $store->id)
            ->where('id', $id)
            ->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'customer' => [
                    'id' => $customer->id,
                    'store_id' => $customer->store_id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'phone_number' => $customer->phone_number,
                    'address' => $customer->address,
                    'created_at' => $customer->created_at->toIso8601String(),
                    'updated_at' => $customer->updated_at->toIso8601String(),
                ],
            ],
        ], 200);
    }

    /**
     * Update a customer.
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();
        
        $store = Store::where('user_id', $user->id)->first();

        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found. Please create a store first.',
            ], 404);
        }

        $customer = Customer::where('store_id', $store->id)
            ->where('id', $id)
            ->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found.',
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone_number' => 'sometimes|required|string|max:255',
            'address' => 'nullable|string',
        ]);

        $customer->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully.',
            'data' => [
                'customer' => [
                    'id' => $customer->id,
                    'store_id' => $customer->store_id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'phone_number' => $customer->phone_number,
                    'address' => $customer->address,
                    'created_at' => $customer->created_at->toIso8601String(),
                    'updated_at' => $customer->updated_at->toIso8601String(),
                ],
            ],
        ], 200);
    }

    /**
     * Delete a customer.
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        
        $store = Store::where('user_id', $user->id)->first();

        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found. Please create a store first.',
            ], 404);
        }

        $customer = Customer::where('store_id', $store->id)
            ->where('id', $id)
            ->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found.',
            ], 404);
        }

        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Customer deleted successfully.',
        ], 200);
    }
}
