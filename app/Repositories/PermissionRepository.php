<?php

namespace App\Repositories;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Permission Repository
 * 
 * Handles data access logic for permissions.
 * 
 * @package App\Repositories
 */
class PermissionRepository
{
    /**
     * Get all permissions with optional filtering.
     *
     * @param array $filters
     * @param bool $paginated
     * @param int $perPage
     * @return Collection|LengthAwarePaginator
     */
    public function getAll(array $filters = [], bool $paginated = false, int $perPage = 15)
    {
        $query = Permission::withCount('roles');

        // Filter by module
        if (isset($filters['module']) && $filters['module']) {
            $query->where('module', $filters['module']);
        }

        // Filter by active status
        if (isset($filters['is_active'])) {
            if ($filters['is_active'] === '1') {
                $query->where('is_active', true);
            } elseif ($filters['is_active'] === '0') {
                $query->where('is_active', false);
            }
        }

        // Search by name, slug, module, or description
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('module', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        if ($paginated) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    /**
     * Get permission by ID.
     *
     * @param int $id
     * @return Permission|null
     */
    public function findById(int $id): ?Permission
    {
        return Permission::with('roles')->find($id);
    }

    /**
     * Create a new permission.
     *
     * @param array $data
     * @return Permission
     */
    public function create(array $data): Permission
    {
        return Permission::create($data);
    }

    /**
     * Update a permission.
     *
     * @param Permission $permission
     * @param array $data
     * @return Permission
     */
    public function update(Permission $permission, array $data): Permission
    {
        $permission->update($data);
        return $permission->fresh();
    }

    /**
     * Delete a permission (soft delete).
     *
     * @param Permission $permission
     * @return bool
     */
    public function delete(Permission $permission): bool
    {
        return $permission->delete();
    }

    /**
     * Restore a soft-deleted permission.
     *
     * @param int $id
     * @return bool
     */
    public function restore(int $id): bool
    {
        $permission = Permission::withTrashed()->find($id);
        
        if (!$permission || !$permission->trashed()) {
            return false;
        }
        
        return $permission->restore();
    }

    /**
     * Permanently delete a permission.
     *
     * @param int $id
     * @return bool
     */
    public function forceDelete(int $id): bool
    {
        $permission = Permission::withTrashed()->find($id);
        
        if (!$permission) {
            return false;
        }
        
        return $permission->forceDelete();
    }

    /**
     * Get trashed permissions.
     *
     * @param array $filters
     * @return Collection
     */
    public function getTrashed(array $filters = []): Collection
    {
        $query = Permission::onlyTrashed()->withCount('roles');

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('module', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('deleted_at', 'desc')->get();
    }

    /**
     * Get all unique modules.
     *
     * @return array
     */
    public function getModules(): array
    {
        return Permission::distinct()->pluck('module')->filter()->sort()->values()->toArray();
    }
}

