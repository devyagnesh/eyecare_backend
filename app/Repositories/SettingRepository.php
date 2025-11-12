<?php

namespace App\Repositories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Setting Repository
 * 
 * Handles data access logic for settings.
 * 
 * @package App\Repositories
 */
class SettingRepository
{
    /**
     * Get all settings with optional filtering.
     *
     * @param array $filters
     * @param bool $paginated
     * @param int $perPage
     * @return Collection|LengthAwarePaginator
     */
    public function getAll(array $filters = [], bool $paginated = true, int $perPage = 15)
    {
        $query = Setting::query();

        // Filter by group
        if (isset($filters['group'])) {
            $query->where('group', $filters['group']);
        }

        // Filter by type
        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        // Search by key or description
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('key', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $filters['sort_by'] ?? 'key';
        $sortOrder = $filters['sort_order'] ?? 'asc';
        $query->orderBy($sortBy, $sortOrder);

        if ($paginated) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    /**
     * Get setting by ID.
     *
     * @param int $id
     * @return Setting|null
     */
    public function findById(int $id): ?Setting
    {
        return Setting::find($id);
    }

    /**
     * Get setting by key.
     *
     * @param string $key
     * @return Setting|null
     */
    public function findByKey(string $key): ?Setting
    {
        return Setting::where('key', $key)->first();
    }

    /**
     * Get settings by group.
     *
     * @param string $group
     * @return Collection
     */
    public function getByGroup(string $group): Collection
    {
        return Setting::where('group', $group)->orderBy('key')->get();
    }

    /**
     * Create a new setting.
     *
     * @param array $data
     * @return Setting
     */
    public function create(array $data): Setting
    {
        $setting = new Setting($data);
        
        // Handle value casting
        if (isset($data['value']) && isset($data['type'])) {
            $setting->setCastedValue($data['value']);
        }
        
        $setting->save();
        
        return $setting;
    }

    /**
     * Update a setting.
     *
     * @param Setting $setting
     * @param array $data
     * @return Setting
     */
    public function update(Setting $setting, array $data): Setting
    {
        // Handle value casting
        if (isset($data['value']) && isset($data['type'])) {
            $setting->type = $data['type'];
            $setting->setCastedValue($data['value']);
            unset($data['value'], $data['type']);
        }
        
        $setting->update($data);
        
        return $setting->fresh();
    }

    /**
     * Delete a setting (soft delete).
     *
     * @param Setting $setting
     * @return bool
     */
    public function delete(Setting $setting): bool
    {
        return $setting->delete();
    }

    /**
     * Restore a soft-deleted setting.
     *
     * @param int $id
     * @return bool
     */
    public function restore(int $id): bool
    {
        $setting = Setting::withTrashed()->find($id);
        
        if (!$setting || !$setting->trashed()) {
            return false;
        }
        
        return $setting->restore();
    }

    /**
     * Permanently delete a setting.
     *
     * @param int $id
     * @return bool
     */
    public function forceDelete(int $id): bool
    {
        $setting = Setting::withTrashed()->find($id);
        
        if (!$setting) {
            return false;
        }
        
        return $setting->forceDelete();
    }

    /**
     * Get trashed settings.
     *
     * @param array $filters
     * @return Collection
     */
    public function getTrashed(array $filters = []): Collection
    {
        $query = Setting::onlyTrashed();

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('key', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('deleted_at', 'desc')->get();
    }

    /**
     * Get all unique groups.
     *
     * @return array
     */
    public function getGroups(): array
    {
        return Setting::distinct()->pluck('group')->sort()->values()->toArray();
    }
}

