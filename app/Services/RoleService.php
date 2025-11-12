<?php

namespace App\Services;

use App\Repositories\RoleRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Role Service
 * 
 * Handles business logic for roles management.
 * 
 * @package App\Services
 */
class RoleService
{
    /**
     * Create a new service instance.
     *
     * @param RoleRepository $repository
     */
    public function __construct(
        private RoleRepository $repository
    ) {}

    /**
     * Get all roles with filters.
     *
     * @param array $filters
     * @param bool $paginated
     * @param int $perPage
     * @return Collection|LengthAwarePaginator
     */
    public function getRoles(array $filters = [], bool $paginated = false, int $perPage = 15)
    {
        return $this->repository->getAll($filters, $paginated, $perPage);
    }

    /**
     * Get role by ID.
     *
     * @param int $id
     * @return \App\Models\Role|null
     */
    public function getRole(int $id): ?\App\Models\Role
    {
        return $this->repository->findById($id);
    }

    /**
     * Create a new role.
     *
     * @param array $data
     * @return \App\Models\Role
     */
    public function createRole(array $data): \App\Models\Role
    {
        return $this->repository->create($data);
    }

    /**
     * Update a role.
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Role
     */
    public function updateRole(int $id, array $data): \App\Models\Role
    {
        $role = $this->repository->findById($id);
        
        if (!$role) {
            throw new \Exception("Role not found.");
        }

        return $this->repository->update($role, $data);
    }

    /**
     * Delete a role (soft delete).
     *
     * @param int $id
     * @return bool
     */
    public function deleteRole(int $id): bool
    {
        $role = $this->repository->findById($id);
        
        if (!$role) {
            throw new \Exception("Role not found.");
        }

        if ($role->users()->count() > 0) {
            throw new \Exception("Cannot delete role. It is assigned to one or more users.");
        }

        return $this->repository->delete($role);
    }

    /**
     * Restore a soft-deleted role.
     *
     * @param int $id
     * @return bool
     */
    public function restoreRole(int $id): bool
    {
        $restored = $this->repository->restore($id);
        
        if (!$restored) {
            throw new \Exception("Role not found or not trashed.");
        }

        return $restored;
    }

    /**
     * Permanently delete a role.
     *
     * @param int $id
     * @return bool
     */
    public function forceDeleteRole(int $id): bool
    {
        $deleted = $this->repository->forceDelete($id);
        
        if (!$deleted) {
            throw new \Exception("Role not found.");
        }

        return $deleted;
    }

    /**
     * Get trashed roles.
     *
     * @param array $filters
     * @return Collection
     */
    public function getTrashedRoles(array $filters = []): Collection
    {
        return $this->repository->getTrashed($filters);
    }
}

