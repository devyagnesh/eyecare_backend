<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * User Repository
 * 
 * Handles data access logic for users.
 * 
 * @package App\Repositories
 */
class UserRepository
{
    /**
     * Get all users with optional filtering.
     *
     * @param array $filters
     * @param bool $paginated
     * @param int $perPage
     * @return Collection|LengthAwarePaginator
     */
    public function getAll(array $filters = [], bool $paginated = false, int $perPage = 15)
    {
        $query = User::with('role');

        // Filter by role
        if (isset($filters['role_id']) && $filters['role_id']) {
            $query->where('role_id', $filters['role_id']);
        }

        // Filter by email verification status
        if (isset($filters['email_verified'])) {
            if ($filters['email_verified'] === '1') {
                $query->whereNotNull('email_verified_at');
            } elseif ($filters['email_verified'] === '0') {
                $query->whereNull('email_verified_at');
            }
        }

        // Search by name or email
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
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
     * Get user by ID.
     *
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User
    {
        return User::with('role')->find($id);
    }

    /**
     * Create a new user.
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Update a user.
     *
     * @param User $user
     * @param array $data
     * @return User
     */
    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user->fresh();
    }

    /**
     * Delete a user (soft delete).
     *
     * @param User $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->delete();
    }

    /**
     * Restore a soft-deleted user.
     *
     * @param int $id
     * @return bool
     */
    public function restore(int $id): bool
    {
        $user = User::withTrashed()->find($id);
        
        if (!$user || !$user->trashed()) {
            return false;
        }
        
        return $user->restore();
    }

    /**
     * Permanently delete a user.
     *
     * @param int $id
     * @return bool
     */
    public function forceDelete(int $id): bool
    {
        $user = User::withTrashed()->find($id);
        
        if (!$user) {
            return false;
        }
        
        return $user->forceDelete();
    }

    /**
     * Get trashed users.
     *
     * @param array $filters
     * @return Collection
     */
    public function getTrashed(array $filters = []): Collection
    {
        $query = User::onlyTrashed()->with('role');

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('deleted_at', 'desc')->get();
    }
}

