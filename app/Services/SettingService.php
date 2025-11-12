<?php

namespace App\Services;

use App\Repositories\SettingRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Setting Service
 * 
 * Handles business logic for settings management.
 * 
 * @package App\Services
 */
class SettingService
{
    /**
     * Create a new service instance.
     *
     * @param SettingRepository $repository
     */
    public function __construct(
        private SettingRepository $repository
    ) {}

    /**
     * Get all settings with filters.
     *
     * @param array $filters
     * @param bool $paginated
     * @param int $perPage
     * @return Collection|LengthAwarePaginator
     */
    public function getSettings(array $filters = [], bool $paginated = true, int $perPage = 15)
    {
        return $this->repository->getAll($filters, $paginated, $perPage);
    }

    /**
     * Get setting by ID.
     *
     * @param int $id
     * @return \App\Models\Setting|null
     */
    public function getSetting(int $id): ?\App\Models\Setting
    {
        return $this->repository->findById($id);
    }

    /**
     * Get setting by key.
     *
     * @param string $key
     * @return \App\Models\Setting|null
     */
    public function getSettingByKey(string $key): ?\App\Models\Setting
    {
        return $this->repository->findByKey($key);
    }

    /**
     * Get settings by group.
     *
     * @param string $group
     * @return Collection
     */
    public function getSettingsByGroup(string $group): Collection
    {
        return $this->repository->getByGroup($group);
    }

    /**
     * Create a new setting.
     *
     * @param array $data
     * @return \App\Models\Setting
     */
    public function createSetting(array $data): \App\Models\Setting
    {
        // Validate key uniqueness
        if ($this->repository->findByKey($data['key'])) {
            throw new \Exception("Setting with key '{$data['key']}' already exists.");
        }

        return $this->repository->create($data);
    }

    /**
     * Update a setting.
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Setting
     */
    public function updateSetting(int $id, array $data): \App\Models\Setting
    {
        $setting = $this->repository->findById($id);
        
        if (!$setting) {
            throw new \Exception("Setting not found.");
        }

        // If key is being changed, check uniqueness
        if (isset($data['key']) && $data['key'] !== $setting->key) {
            if ($this->repository->findByKey($data['key'])) {
                throw new \Exception("Setting with key '{$data['key']}' already exists.");
            }
        }

        return $this->repository->update($setting, $data);
    }

    /**
     * Delete a setting (soft delete).
     *
     * @param int $id
     * @return bool
     */
    public function deleteSetting(int $id): bool
    {
        $setting = $this->repository->findById($id);
        
        if (!$setting) {
            throw new \Exception("Setting not found.");
        }

        return $this->repository->delete($setting);
    }

    /**
     * Restore a soft-deleted setting.
     *
     * @param int $id
     * @return bool
     */
    public function restoreSetting(int $id): bool
    {
        $restored = $this->repository->restore($id);
        
        if (!$restored) {
            throw new \Exception("Setting not found or not trashed.");
        }

        return $restored;
    }

    /**
     * Permanently delete a setting.
     *
     * @param int $id
     * @return bool
     */
    public function forceDeleteSetting(int $id): bool
    {
        $deleted = $this->repository->forceDelete($id);
        
        if (!$deleted) {
            throw new \Exception("Setting not found.");
        }

        return $deleted;
    }

    /**
     * Get trashed settings.
     *
     * @param array $filters
     * @return Collection
     */
    public function getTrashedSettings(array $filters = []): Collection
    {
        return $this->repository->getTrashed($filters);
    }

    /**
     * Get all unique groups.
     *
     * @return array
     */
    public function getGroups(): array
    {
        return $this->repository->getGroups();
    }

    /**
     * Format setting for API response.
     *
     * @param \App\Models\Setting $setting
     * @return array
     */
    public function formatSetting(\App\Models\Setting $setting): array
    {
        return [
            'id' => $setting->id,
            'key' => $setting->key,
            'value' => $setting->getCastedValue(),
            'type' => $setting->type,
            'group' => $setting->group,
            'description' => $setting->description,
            'is_public' => $setting->is_public,
            'created_at' => $setting->created_at->toIso8601String(),
            'updated_at' => $setting->updated_at->toIso8601String(),
        ];
    }
}

