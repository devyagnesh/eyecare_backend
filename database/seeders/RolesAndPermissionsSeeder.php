<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permissions
        $permissions = [
            // User Management
            ['name' => 'View Users', 'slug' => 'view-users', 'module' => 'users'],
            ['name' => 'Create Users', 'slug' => 'create-users', 'module' => 'users'],
            ['name' => 'Edit Users', 'slug' => 'edit-users', 'module' => 'users'],
            ['name' => 'Delete Users', 'slug' => 'delete-users', 'module' => 'users'],
            
            // Role Management
            ['name' => 'View Roles', 'slug' => 'view-roles', 'module' => 'roles'],
            ['name' => 'Create Roles', 'slug' => 'create-roles', 'module' => 'roles'],
            ['name' => 'Edit Roles', 'slug' => 'edit-roles', 'module' => 'roles'],
            ['name' => 'Delete Roles', 'slug' => 'delete-roles', 'module' => 'roles'],
            
            // Permission Management
            ['name' => 'View Permissions', 'slug' => 'view-permissions', 'module' => 'permissions'],
            ['name' => 'Create Permissions', 'slug' => 'create-permissions', 'module' => 'permissions'],
            ['name' => 'Edit Permissions', 'slug' => 'edit-permissions', 'module' => 'permissions'],
            ['name' => 'Delete Permissions', 'slug' => 'delete-permissions', 'module' => 'permissions'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                array_merge($permission, ['is_active' => true])
            );
        }

        // Create Roles
        $adminRole = Role::updateOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Administrator',
                'description' => 'Full system access with all permissions',
                'is_active' => true,
            ]
        );

        $userRole = Role::updateOrCreate(
            ['slug' => 'user'],
            [
                'name' => 'User',
                'description' => 'Standard user with limited permissions',
                'is_active' => true,
            ]
        );

        // Assign all permissions to admin role
        $adminRole->permissions()->sync(Permission::pluck('id'));

        // Assign view permissions to user role
        $userPermissions = Permission::whereIn('slug', [
            'view-users',
            'view-roles',
            'view-permissions'
        ])->pluck('id');
        
        $userRole->permissions()->sync($userPermissions);

        $this->command->info('Roles and Permissions seeded successfully!');
    }
}