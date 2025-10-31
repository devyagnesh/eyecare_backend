<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, assign a default role to any users without a role
        $defaultRole = DB::table('roles')->where('slug', 'user')->first();
        if ($defaultRole) {
            DB::table('users')->whereNull('role_id')->update(['role_id' => $defaultRole->id]);
        }

        // Drop the foreign key constraint
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
        });

        // Make role_id required and change onDelete to restrict
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable(false)->change();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the foreign key constraint
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
        });

        // Make role_id nullable again
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->change();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
        });
    }
};