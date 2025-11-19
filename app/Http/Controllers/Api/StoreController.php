<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreStoreRequest;
use App\Http\Requests\Api\UpdateStoreRequest;
use App\Models\Store;
use App\Services\StoreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private StoreService $storeService
    ) {}

    /**
     * Check if the authenticated user has a store.
     */
    public function check(Request $request)
    {
        $user = $request->user();
        
        // Check if user is blocked
        if ($user->is_blocked) {
            return response()->json([
                'success' => false,
                'message' => 'Your account has been blocked. Please contact support.',
            ], 403);
        }
        
        $store = $this->storeService->getStoreByUser($user);

        return response()->json([
            'success' => true,
            'data' => [
                'store_exists' => $store !== null,
                'store_id' => $store ? $store->id : null,
                'store_active' => $store ? $store->is_active : null,
            ],
        ], 200);
    }

    /**
     * Get the authenticated user's store.
     */
    public function show(Request $request)
    {
        $user = $request->user();
        
        // Check if user is blocked
        if ($user->is_blocked) {
            return response()->json([
                'success' => false,
                'message' => 'Your account has been blocked. Please contact support.',
            ], 403);
        }
        
        $store = $this->storeService->getStoreByUser($user);

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

        return response()->json([
            'success' => true,
            'data' => [
                'store' => $this->storeService->formatStore($store),
            ],
        ], 200);
    }

    /**
     * Create a new store for the authenticated user.
     */
    public function store(StoreStoreRequest $request)
    {
        $user = $request->user();

        // Check if user is blocked
        if ($user->is_blocked) {
            return response()->json([
                'success' => false,
                'message' => 'Your account has been blocked. Please contact support.',
            ], 403);
        }

        try {
            $validated = $request->validated();
            
            // Handle logo file upload
            if ($request->hasFile('logo')) {
                $validated['logo'] = $request->file('logo');
            }
            
            $store = $this->storeService->createStore($user, $validated);

            return response()->json([
                'success' => true,
                'message' => 'Store created successfully.',
                'data' => [
                    'store' => $this->storeService->formatStore($store),
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
     * Update the authenticated user's store.
     */
    public function update(UpdateStoreRequest $request)
    {
        $user = $request->user();

        // Check if user is blocked
        if ($user->is_blocked) {
            return response()->json([
                'success' => false,
                'message' => 'Your account has been blocked. Please contact support.',
            ], 403);
        }

        $store = $this->storeService->getStoreByUser($user);

        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found. Please create a store first.',
            ], 404);
        }

        try {
            $validated = $request->validated();
            
            // Handle logo file upload
            if ($request->hasFile('logo')) {
                $validated['logo'] = $request->file('logo');
            }
            
            $store = $this->storeService->updateStoreByObject($store, $validated);

            return response()->json([
                'success' => true,
                'message' => 'Store updated successfully.',
                'data' => [
                    'store' => $this->storeService->formatStore($store),
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }
}
