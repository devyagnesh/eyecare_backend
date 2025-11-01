<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    /**
     * Get the authenticated user's store.
     */
    public function show(Request $request)
    {
        $user = $request->user();
        
        $store = Store::where('user_id', $user->id)->first();

        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found. Please create a store first.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'store' => [
                    'id' => $store->id,
                    'logo' => $store->logo ? url(Storage::url($store->logo)) : null,
                    'email' => $store->email,
                    'phone_number' => $store->phone_number,
                    'address' => $store->address,
                    'created_at' => $store->created_at->toIso8601String(),
                    'updated_at' => $store->updated_at->toIso8601String(),
                ],
            ],
        ], 200);
    }

    /**
     * Create a new store for the authenticated user.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        // Check if email is verified
        if (!$user->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify your email address before creating a store.',
            ], 403);
        }

        // Check if store already exists
        if ($user->store) {
            return response()->json([
                'success' => false,
                'message' => 'Store already exists. Use the update endpoint to modify your store.',
            ], 409);
        }

        $validated = $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:255',
            'address' => 'required|string',
        ]);

        // Handle logo upload
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('stores/logos', 'public');
        }

        $store = Store::create([
            'user_id' => $user->id,
            'logo' => $logoPath,
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Store created successfully.',
            'data' => [
                'store' => [
                    'id' => $store->id,
                    'logo' => $store->logo ? url(Storage::url($store->logo)) : null,
                    'email' => $store->email,
                    'phone_number' => $store->phone_number,
                    'address' => $store->address,
                    'created_at' => $store->created_at->toIso8601String(),
                    'updated_at' => $store->updated_at->toIso8601String(),
                ],
            ],
        ], 201);
    }

    /**
     * Update the authenticated user's store.
     */
    public function update(Request $request)
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
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'email' => 'sometimes|required|email|max:255',
            'phone_number' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string',
        ]);

        // Handle logo upload - delete old logo if exists
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($store->logo && Storage::disk('public')->exists($store->logo)) {
                Storage::disk('public')->delete($store->logo);
            }
            $validated['logo'] = $request->file('logo')->store('stores/logos', 'public');
        }

        $store->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Store updated successfully.',
            'data' => [
                'store' => [
                    'id' => $store->id,
                    'logo' => $store->logo ? url(Storage::url($store->logo)) : null,
                    'email' => $store->email,
                    'phone_number' => $store->phone_number,
                    'address' => $store->address,
                    'created_at' => $store->created_at->toIso8601String(),
                    'updated_at' => $store->updated_at->toIso8601String(),
                ],
            ],
        ], 200);
    }
}
