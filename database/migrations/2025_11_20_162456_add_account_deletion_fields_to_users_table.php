<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('deletion_requested_at')->nullable()->after('is_blocked');
            $table->timestamp('scheduled_deletion_at')->nullable()->after('deletion_requested_at');
            
            // Index for scheduled deletion queries
            $table->index('scheduled_deletion_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['scheduled_deletion_at']);
            $table->dropColumn(['deletion_requested_at', 'scheduled_deletion_at']);
        });
    }
};
