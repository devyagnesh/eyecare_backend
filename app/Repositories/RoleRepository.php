<?php

namespace App\Repositories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Role Repository
 * 
 * Handles data access logic for roles.
 * 
 * @package App\Repositories
 */
class RoleRepository
{
    /**
     * Get all roles with optional filtering.
     *
     * @param array $filters
     * @param bool $paginated
     * @param int $perPage
     * @return Collection|LengthAwarePaginator
     */
    public function getAll(array $filters = [], bool $paginated = false, int $perPage = 15)
    {
        $query = Role::withCount('users');

        // Filter by active status
        if (isset($filters['is_active'])) {
            if ($filters['is_active'] === '1') {
                $query->where('is_active', true);
            } elseif ($filters['is_active'] === '0') {
                $query->where('is_active', false);
            }
        }

        // Search by name, slug, or description
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
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
     * Get role by ID.
     *
     * @param int $id
     * @return Role|null
     */
    public function findById(int $id): ?Role
    {
        return Role::with('permissions')->find($id);
    }

    /**
     * Create a new role.
     *
     * @param array $data
     * @return Role
     */
    public function create(array $data): Role
    {
        return Role::create($data);
    }

    /**
     * Update a role.
     *
     * @param Role $role
     * @param array $data
     * @return Role
     */
    public function update(Role $role, array $data): Role
    {
        $role->update($data);
        return $role->fresh();
    }

    /**
     * Delete a role (soft delete).
     *
     * @param Role $role
     * @return bool
     */
    public function delete(Role $role): bool
    {
        return $role->delete();
    }

    /**
     * Restore a soft-deleted role.
     *
     * @param int $id
     * @return bool
     */
    public function restore(int $id): bool
    {
        $role = Role::withTrashed()->find($id);
        
        if (!$role || !$role->trashed()) {
            return false;
        }
        
        return $role->restore();
    }

    /**
     * Permanently delete a role.
     *
     * @param int $id
     * @return bool
     */
    public function forceDelete(int $id): bool
    {
        $role = Role::withTrashed()->find($id);
        
        if (!$role) {
            return false;
        }
        
        return $role->forceDelete();
    }

    /**
     * Get trashed roles.
     *
     * @param array $filters
     * @return Collection
     */
    public function getTrashed(array $filters = []): Collection
    {
        $query = Role::onlyTrashed()->withCount('users');

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('deleted_at', 'desc')->get();
    }
}

