<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Seed roles and permissions first
        $this->call(RolesAndPermissionsSeeder::class);

        // Create admin user
        $adminRole = \App\Models\Role::where('slug', 'admin')->first();
        if (!$adminRole) {
            $this->command->error('Admin role not found. Please check RolesAndPermissionsSeeder.');
            return;
        }
        
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => 'password', // Will be automatically hashed by the model
            'role_id' => $adminRole->id,
        ]);
    }
}
