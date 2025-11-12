<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

/**
 * User Service
 * 
 * Handles business logic for users management.
 * 
 * @package App\Services
 */
class UserService
{
    /**
     * Create a new service instance.
     *
     * @param UserRepository $repository
     */
    public function __construct(
        private UserRepository $repository
    ) {}

    /**
     * Get all users with filters.
     *
     * @param array $filters
     * @param bool $paginated
     * @param int $perPage
     * @return Collection|LengthAwarePaginator
     */
    public function getUsers(array $filters = [], bool $paginated = false, int $perPage = 15)
    {
        return $this->repository->getAll($filters, $paginated, $perPage);
    }

    /**
     * Get user by ID.
     *
     * @param int $id
     * @return \App\Models\User|null
     */
    public function getUser(int $id): ?\App\Models\User
    {
        return $this->repository->findById($id);
    }

    /**
     * Create a new user.
     *
     * @param array $data
     * @return \App\Models\User
     */
    public function createUser(array $data): \App\Models\User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->repository->create($data);
    }

    /**
     * Update a user.
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\User
     */
    public function updateUser(int $id, array $data): \App\Models\User
    {
        $user = $this->repository->findById($id);
        
        if (!$user) {
            throw new \Exception("User not found.");
        }

        if (isset($data['password']) && $data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        return $this->repository->update($user, $data);
    }

    /**
     * Delete a user (soft delete).
     *
     * @param int $id
     * @return bool
     */
    public function deleteUser(int $id): bool
    {
        $user = $this->repository->findById($id);
        
        if (!$user) {
            throw new \Exception("User not found.");
        }

        return $this->repository->delete($user);
    }

    /**
     * Restore a soft-deleted user.
     *
     * @param int $id
     * @return bool
     */
    public function restoreUser(int $id): bool
    {
        $restored = $this->repository->restore($id);
        
        if (!$restored) {
            throw new \Exception("User not found or not trashed.");
        }

        return $restored;
    }

    /**
     * Permanently delete a user.
     *
     * @param int $id
     * @return bool
     */
    public function forceDeleteUser(int $id): bool
    {
        $deleted = $this->repository->forceDelete($id);
        
        if (!$deleted) {
            throw new \Exception("User not found.");
        }

        return $deleted;
    }

    /**
     * Get trashed users.
     *
     * @param array $filters
     * @return Collection
     */
    public function getTrashedUsers(array $filters = []): Collection
    {
        return $this->repository->getTrashed($filters);
    }
}

