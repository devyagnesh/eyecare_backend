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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body');
            $table->json('data')->nullable(); // Additional data payload
            $table->enum('type', ['all', 'user', 'store'])->default('all'); // Target type
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Specific user (if type is 'user')
            $table->foreignId('store_id')->nullable()->constrained()->onDelete('cascade'); // Specific store (if type is 'store')
            $table->integer('sent_count')->default(0); // Number of devices notified
            $table->integer('failed_count')->default(0); // Number of failed deliveries
            $table->enum('status', ['pending', 'sending', 'completed', 'failed'])->default('pending');
            $table->text('error_message')->nullable(); // Error details if failed
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null'); // Admin who sent
            $table->timestamp('sent_at')->nullable(); // When notification was sent
            $table->timestamps();
            
            // Indexes
            $table->index(['type', 'status']);
            $table->index(['user_id', 'status']);
            $table->index(['store_id', 'status']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
