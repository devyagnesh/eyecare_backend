<?php

namespace App\Policies;

use App\Models\Setting;
use App\Models\User;

/**
 * Setting Policy
 * 
 * Handles authorization for settings management.
 * 
 * @package App\Policies
 */
class SettingPolicy
{
    /**
     * Determine if the user can view any settings.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view-settings');
    }

    /**
     * Determine if the user can view the setting.
     */
    public function view(User $user, Setting $setting): bool
    {
        return $user->hasPermission('view-settings');
    }

    /**
     * Determine if the user can create settings.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create-settings');
    }

    /**
     * Determine if the user can update the setting.
     */
    public function update(User $user, Setting $setting): bool
    {
        return $user->hasPermission('update-settings');
    }

    /**
     * Determine if the user can delete the setting.
     */
    public function delete(User $user, Setting $setting): bool
    {
        return $user->hasPermission('delete-settings');
    }
}

