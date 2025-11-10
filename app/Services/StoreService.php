<?php

namespace App\Services;

use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StoreService
{
    /**
     * Get store for authenticated user.
     *
     * @param User $user
     * @return Store|null
     */
    public function getStore(User $user): ?Store
    {
        return Store::where('user_id', $user->id)->first();
    }

    /**
     * Create a new store.
     *
     * @param User $user
     * @param array $data
     * @return Store
     * @throws \Exception
     */
    public function createStore(User $user, array $data): Store
    {
        // Check if email is verified
        if (!$user->hasVerifiedEmail()) {
            throw new \Exception('Please verify your email address before creating a store.', 403);
        }

        // Check if store already exists
        if ($user->store) {
            throw new \Exception('Store already exists. Use the update endpoint to modify your store.', 409);
        }

        try {
            DB::beginTransaction();

            // Handle logo upload
            $logoPath = null;
            if (isset($data['logo']) && $data['logo']) {
                $logoPath = $data['logo']->store('stores/logos', 'public');
            }

            $store = Store::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'logo' => $logoPath,
                'email' => $data['email'],
                'phone_number' => $data['phone_number'],
                'address' => $data['address'],
            ]);

            DB::commit();

            Log::info('Store created successfully', [
                'store_id' => $store->id,
                'user_id' => $user->id,
            ]);

            return $store->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create store', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'data' => $data,
            ]);
            throw $e;
        }
    }

    /**
     * Update a store.
     *
     * @param Store $store
     * @param array $data
     * @return Store
     * @throws \Exception
     */
    public function updateStore(Store $store, array $data): Store
    {
        try {
            DB::beginTransaction();

            // Handle logo upload - delete old logo if exists
            if (isset($data['logo']) && $data['logo']) {
                // Delete old logo if exists
                if ($store->logo && Storage::disk('public')->exists($store->logo)) {
                    Storage::disk('public')->delete($store->logo);
                }
                $data['logo'] = $data['logo']->store('stores/logos', 'public');
            }

            $store->update($data);
            
            // Refresh the model to ensure we have the latest data
            $store->refresh();

            DB::commit();

            Log::info('Store updated successfully', [
                'store_id' => $store->id,
            ]);

            return $store->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update store', [
                'error' => $e->getMessage(),
                'store_id' => $store->id,
                'data' => $data,
            ]);
            throw $e;
        }
    }

    /**
     * Format store data for API response.
     *
     * @param Store $store
     * @return array
     */
    public function formatStore(Store $store): array
    {
        return [
            'id' => $store->id,
            'name' => $store->name,
            'logo' => $store->logo ? url(Storage::url($store->logo)) : null,
            'email' => $store->email,
            'phone_number' => $store->phone_number,
            'address' => $store->address,
            'created_at' => $store->created_at->toIso8601String(),
            'updated_at' => $store->updated_at->toIso8601String(),
        ];
    }
}

