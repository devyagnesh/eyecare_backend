<?php

namespace App\Services;

use App\Models\Store;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
     * Create a new store.
     *
     * @param array $data
     * @return Store
     * @throws \Exception
     */
    public function createStore(array $data): Store
    {
        DB::beginTransaction();
        try {
            $store = Store::create($data);
            DB::commit();
            Log::info('Store created successfully', ['store_id' => $store->id]);
            return $store;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create store', ['error' => $e->getMessage()]);
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
