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
        Schema::create('user_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('device_id')->nullable(); // Unique device identifier
            $table->string('device_type')->nullable(); // mobile, tablet, desktop
            $table->string('device_name')->nullable(); // Device name/model
            $table->string('os_name')->nullable(); // iOS, Android, Windows, etc.
            $table->string('os_version')->nullable();
            $table->string('browser_name')->nullable(); // Chrome, Safari, etc.
            $table->string('browser_version')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->text('notification_token')->nullable(); // FCM token, APNS token, etc.
            $table->string('notification_platform')->nullable(); // fcm, apns, web-push
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_active_at')->nullable();
            $table->timestamps();

            // Unique constraint: one device_id per user
            $table->unique(['user_id', 'device_id']);
            
            // Indexes for faster queries
            $table->index(['user_id', 'is_active']);
            // Note: notification_token is TEXT, so we can't index it directly
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_devices');
    }
};