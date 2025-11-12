<?php

namespace App\Services;

use App\Repositories\PermissionRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Permission Service
 * 
 * Handles business logic for permissions management.
 * 
 * @package App\Services
 */
class PermissionService
{
    /**
     * Create a new service instance.
     *
     * @param PermissionRepository $repository
     */
    public function __construct(
        private PermissionRepository $repository
    ) {}

    /**
     * Get all permissions with filters.
     *
     * @param array $filters
     * @param bool $paginated
     * @param int $perPage
     * @return Collection|LengthAwarePaginator
     */
    public function getPermissions(array $filters = [], bool $paginated = false, int $perPage = 15)
    {
        return $this->repository->getAll($filters, $paginated, $perPage);
    }

    /**
     * Get permission by ID.
     *
     * @param int $id
     * @return \App\Models\Permission|null
     */
    public function getPermission(int $id): ?\App\Models\Permission
    {
        return $this->repository->findById($id);
    }

    /**
     * Create a new permission.
     *
     * @param array $data
     * @return \App\Models\Permission
     */
    public function createPermission(array $data): \App\Models\Permission
    {
        return $this->repository->create($data);
    }

    /**
     * Update a permission.
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Permission
     */
    public function updatePermission(int $id, array $data): \App\Models\Permission
    {
        $permission = $this->repository->findById($id);
        
        if (!$permission) {
            throw new \Exception("Permission not found.");
        }

        return $this->repository->update($permission, $data);
    }

    /**
     * Delete a permission (soft delete).
     *
     * @param int $id
     * @return bool
     */
    public function deletePermission(int $id): bool
    {
        $permission = $this->repository->findById($id);
        
        if (!$permission) {
            throw new \Exception("Permission not found.");
        }

        if ($permission->roles()->count() > 0) {
            throw new \Exception("Cannot delete permission. It is assigned to one or more roles.");
        }

        return $this->repository->delete($permission);
    }

    /**
     * Restore a soft-deleted permission.
     *
     * @param int $id
     * @return bool
     */
    public function restorePermission(int $id): bool
    {
        $restored = $this->repository->restore($id);
        
        if (!$restored) {
            throw new \Exception("Permission not found or not trashed.");
        }

        return $restored;
    }

    /**
     * Permanently delete a permission.
     *
     * @param int $id
     * @return bool
     */
    public function forceDeletePermission(int $id): bool
    {
        $deleted = $this->repository->forceDelete($id);
        
        if (!$deleted) {
            throw new \Exception("Permission not found.");
        }

        return $deleted;
    }

    /**
     * Get trashed permissions.
     *
     * @param array $filters
     * @return Collection
     */
    public function getTrashedPermissions(array $filters = []): Collection
    {
        return $this->repository->getTrashed($filters);
    }

    /**
     * Get all unique modules.
     *
     * @return array
     */
    public function getModules(): array
    {
        return $this->repository->getModules();
    }
}

