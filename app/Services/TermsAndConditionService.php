<?php

namespace App\Services;

use App\Models\TermsAndCondition;
use App\Models\User;
use App\Models\UserTermsAcceptance;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Terms and Condition Service
 * 
 * Handles business logic for terms and conditions management.
 * 
 * @package App\Services
 */
class TermsAndConditionService
{
    /**
     * Get all terms and conditions with filters.
     *
     * @param array $filters
     * @param bool $paginated
     * @param int $perPage
     * @return Collection|LengthAwarePaginator
     */
    public function getTerms(array $filters = [], bool $paginated = false, int $perPage = 15)
    {
        $query = TermsAndCondition::with(['creator', 'updater']);

        if (isset($filters['search']) && $filters['search']) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('version', 'like', "%{$search}%");
            });
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        if ($paginated) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    /**
     * Get terms by ID.
     *
     * @param int $id
     * @return TermsAndCondition|null
     */
    public function getTermsById(int $id): ?TermsAndCondition
    {
        return TermsAndCondition::with(['creator', 'updater'])->find($id);
    }

    /**
     * Create new terms and conditions.
     *
     * @param array $data
     * @return TermsAndCondition
     */
    public function createTerms(array $data): TermsAndCondition
    {
        // If this is being set as active, deactivate all other terms
        if (isset($data['is_active']) && $data['is_active']) {
            TermsAndCondition::where('is_active', true)->update(['is_active' => false]);
        }

        $terms = TermsAndCondition::create($data);

        Log::info('Terms and conditions created', [
            'terms_id' => $terms->id,
            'version' => $terms->version,
            'created_by' => $terms->created_by,
        ]);

        return $terms->fresh(['creator', 'updater']);
    }

    /**
     * Update terms and conditions.
     *
     * @param int $id
     * @param array $data
     * @return TermsAndCondition
     */
    public function updateTerms(int $id, array $data): TermsAndCondition
    {
        $terms = TermsAndCondition::findOrFail($id);

        // If this is being set as active, deactivate all other terms
        if (isset($data['is_active']) && $data['is_active'] && !$terms->is_active) {
            TermsAndCondition::where('id', '!=', $id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $terms->update($data);

        Log::info('Terms and conditions updated', [
            'terms_id' => $terms->id,
            'version' => $terms->version,
            'updated_by' => $terms->updated_by,
        ]);

        return $terms->fresh(['creator', 'updater']);
    }

    /**
     * Delete terms and conditions (soft delete).
     *
     * @param int $id
     * @return bool
     */
    public function deleteTerms(int $id): bool
    {
        $terms = TermsAndCondition::findOrFail($id);
        return $terms->delete();
    }

    /**
     * Restore soft-deleted terms.
     *
     * @param int $id
     * @return bool
     */
    public function restoreTerms(int $id): bool
    {
        $terms = TermsAndCondition::withTrashed()->findOrFail($id);
        return $terms->restore();
    }

    /**
     * Permanently delete terms.
     *
     * @param int $id
     * @return bool
     */
    public function forceDeleteTerms(int $id): bool
    {
        $terms = TermsAndCondition::withTrashed()->findOrFail($id);
        return $terms->forceDelete();
    }

    /**
     * Get the latest active terms.
     *
     * @return TermsAndCondition|null
     */
    public function getLatestTerms(): ?TermsAndCondition
    {
        return TermsAndCondition::getLatest();
    }

    /**
     * Record user acceptance of terms.
     *
     * @param User $user
     * @param int $termsId
     * @param string|null $ipAddress
     * @param string|null $userAgent
     * @return UserTermsAcceptance
     */
    public function recordAcceptance(
        User $user,
        int $termsId,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): UserTermsAcceptance {
        // Check if already accepted
        $existing = UserTermsAcceptance::where('user_id', $user->id)
            ->where('terms_and_condition_id', $termsId)
            ->first();

        if ($existing) {
            // Update existing record
            $existing->update([
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'accepted_at' => now(),
            ]);
            return $existing->fresh();
        }

        // Create new acceptance record
        $acceptance = UserTermsAcceptance::create([
            'user_id' => $user->id,
            'terms_and_condition_id' => $termsId,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'accepted_at' => now(),
        ]);

        Log::info('Terms and conditions accepted', [
            'user_id' => $user->id,
            'terms_id' => $termsId,
            'ip_address' => $ipAddress,
        ]);

        return $acceptance;
    }

    /**
     * Get acceptance statistics for terms.
     *
     * @param int $termsId
     * @return array
     */
    public function getAcceptanceStats(int $termsId): array
    {
        $totalUsers = User::count();
        $acceptedCount = UserTermsAcceptance::where('terms_and_condition_id', $termsId)->count();
        $acceptanceRate = $totalUsers > 0 ? round(($acceptedCount / $totalUsers) * 100, 2) : 0;

        return [
            'total_users' => $totalUsers,
            'accepted_count' => $acceptedCount,
            'acceptance_rate' => $acceptanceRate,
        ];
    }
}

