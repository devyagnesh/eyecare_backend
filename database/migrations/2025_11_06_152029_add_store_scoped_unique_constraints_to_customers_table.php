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
        Schema::table('customers', function (Blueprint $table) {
            // Drop existing unique constraint on phone_number if it exists
            try {
                $table->dropUnique(['phone_number']);
            } catch (\Exception $e) {
                // Constraint doesn't exist, continue
            }
            
            // Add composite unique constraint: phone_number must be unique per store
            $table->unique(['store_id', 'phone_number'], 'customers_store_phone_unique');
            
            // Add composite unique constraint: email must be unique per store (allows multiple NULLs)
            // Note: In SQL, NULL != NULL, so multiple NULL emails are allowed per store
            $table->unique(['store_id', 'email'], 'customers_store_email_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Drop composite unique constraints
            $table->dropUnique('customers_store_phone_unique');
            $table->dropUnique('customers_store_email_unique');
            
            // Restore global unique constraint on phone_number
            $table->unique('phone_number');
        });
    }
};
