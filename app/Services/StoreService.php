<?php

namespace App\Services;

use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Store Service
 * 
 * Handles business logic for stores management.
 * 
 * @package App\Services
 */
class StoreService
{
    /**
     * Get all stores with filters.
     *
     * @param array $filters
     * @param bool $paginated
     * @param int $perPage
     * @return Collection|LengthAwarePaginator
     */
    public function getStores(array $filters = [], bool $paginated = true, int $perPage = 15)
    {
        $query = Store::with(['user']);

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('email', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('phone_number', 'like', '%' . $filters['search'] . '%')
                    ->orWhereHas('user', function ($userQuery) use ($filters) {
                        $userQuery->where('name', 'like', '%' . $filters['search'] . '%')
                            ->orWhere('email', 'like', '%' . $filters['search'] . '%');
                    });
            });
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', (bool) $filters['is_active']);
        }

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        $query->orderBy($filters['sort_by'] ?? 'created_at', $filters['sort_order'] ?? 'desc');

        if ($paginated) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    /**
     * Get store by ID.
     *
     * @param int $id
     * @return Store|null
     */
    public function getStore(int $id): ?Store
    {
        return Store::with(['user'])->find($id);
    }

    /**
     * Get store by User object.
     *
     * @param User $user
     * @return Store|null
     */
    public function getStoreByUser(User $user): ?Store
    {
        return Store::with(['user'])->where('user_id', $user->id)->first();
    }

    /**
     * Format store data for API response.
     *
     * @param Store $store
     * @return array
     */
    public function formatStore(Store $store): array
    {
        // Helper to ensure full URL for logo
        $getLogoUrl = function ($path) {
            if (!$path) {
                return null;
            }
            $url = Storage::url($path);
            // If URL is already absolute, return as-is; otherwise make it absolute
            return (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) 
                ? $url 
                : url($url);
        };

        return [
            'id' => $store->id,
            'user_id' => $store->user_id,
            'name' => $store->name,
            'logo' => $getLogoUrl($store->logo),
            'email' => $store->email,
            'phone_number' => $store->phone_number,
            'address' => $store->address,
            'is_active' => $store->is_active,
            'created_at' => $store->created_at->toIso8601String(),
            'updated_at' => $store->updated_at->toIso8601String(),
        ];
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
        // Check if user already has a store
        $existingStore = $this->getStoreByUser($user);
        if ($existingStore) {
            throw new \Exception('User already has a store.', 409);
        }

        DB::beginTransaction();
        try {
            // Handle logo file upload
            if (isset($data['logo']) && ($data['logo'] instanceof \Illuminate\Http\UploadedFile || is_file($data['logo']))) {
                $logoPath = $data['logo']->store('stores/logos', 'public');
                $data['logo'] = $logoPath;
            } else {
                unset($data['logo']);
            }

            $data['user_id'] = $user->id;
            $store = Store::create($data);
            DB::commit();
            Log::info('Store created successfully', ['store_id' => $store->id, 'user_id' => $user->id]);
            return $store->fresh(['user']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create store', ['error' => $e->getMessage(), 'user_id' => $user->id]);
            throw $e;
        }
    }

    /**
     * Update a store.
     *
     * @param int $id
     * @param array $data
     * @return Store
     * @throws \Exception
     */
    public function updateStore(int $id, array $data): Store
    {
        $store = $this->getStore($id);
        
        if (!$store) {
            throw new \Exception("Store not found.");
        }

        DB::beginTransaction();
        try {
            // Handle checkbox - if not present, keep existing value
            if (!isset($data['is_active'])) {
                unset($data['is_active']);
            } else {
                $data['is_active'] = (bool) $data['is_active'];
            }

            $store->update($data);
            DB::commit();
            Log::info('Store updated successfully', ['store_id' => $store->id]);
            return $store->fresh(['user']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update store', ['store_id' => $id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Update a store by Store object.
     *
     * @param Store $store
     * @param array $data
     * @return Store
     * @throws \Exception
     */
    public function updateStoreByObject(Store $store, array $data): Store
    {
        DB::beginTransaction();
        try {
            // Handle logo file upload
            if (isset($data['logo']) && is_file($data['logo'])) {
                // Delete old logo if exists
                if ($store->logo && \Storage::disk('public')->exists($store->logo)) {
                    \Storage::disk('public')->delete($store->logo);
                }
                
                // Store new logo
                $logoPath = $data['logo']->store('stores/logos', 'public');
                $data['logo'] = $logoPath;
            } else {
                unset($data['logo']);
            }

            // Handle checkbox - if not present, keep existing value
            if (!isset($data['is_active'])) {
                unset($data['is_active']);
            } else {
                $data['is_active'] = (bool) $data['is_active'];
            }

            $store->update($data);
            DB::commit();
            Log::info('Store updated successfully', ['store_id' => $store->id]);
            return $store->fresh(['user']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update store', ['store_id' => $store->id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Toggle store active status.
     *
     * @param int $id
     * @return Store
     * @throws \Exception
     */
    public function toggleStoreStatus(int $id): Store
    {
        $store = $this->getStore($id);
        
        if (!$store) {
            throw new \Exception("Store not found.");
        }

        $store->update(['is_active' => !$store->is_active]);
        Log::info('Store status toggled', ['store_id' => $store->id, 'is_active' => $store->is_active]);
        return $store->fresh(['user']);
    }

    /**
     * Delete a store (soft delete if implemented).
     *
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function deleteStore(int $id): bool
    {
        $store = $this->getStore($id);
        
        if (!$store) {
            throw new \Exception("Store not found.");
        }

        if ($store->delete()) {
            Log::info('Store deleted successfully', ['store_id' => $id]);
            return true;
        }

        return false;
    }
}
