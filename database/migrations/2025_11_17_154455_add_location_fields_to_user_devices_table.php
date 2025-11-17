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
        Schema::table('user_devices', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('ip_address');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->string('city', 100)->nullable()->after('longitude');
            $table->string('region', 100)->nullable()->after('city');
            $table->string('country', 100)->nullable()->after('region');
            $table->string('country_code', 2)->nullable()->after('country');
            
            // Index for location-based queries
            $table->index(['country_code', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_devices', function (Blueprint $table) {
            $table->dropIndex(['country_code', 'is_active']);
            $table->dropColumn(['latitude', 'longitude', 'city', 'region', 'country', 'country_code']);
        });
    }
};
