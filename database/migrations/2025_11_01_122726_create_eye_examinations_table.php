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
        Schema::create('eye_examinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->date('exam_date');
            $table->text('chief_complaint')->nullable();
            $table->date('old_rx_date')->nullable();
            
            // Visual Acuity - Unaided
            $table->string('od_va_unaided')->nullable();
            $table->string('os_va_unaided')->nullable();
            
            // Right Eye (OD) Prescription
            $table->decimal('od_sphere', 8, 2)->nullable();
            $table->decimal('od_cylinder', 8, 2)->nullable();
            $table->integer('od_axis')->nullable()->comment('Axis 0-180 degrees');
            
            // Left Eye (OS) Prescription
            $table->decimal('os_sphere', 8, 2)->nullable();
            $table->decimal('os_cylinder', 8, 2)->nullable();
            $table->integer('os_axis')->nullable()->comment('Axis 0-180 degrees');
            
            // Addition and Pupillary Distance
            $table->decimal('add_power', 8, 2)->nullable()->comment('Reading Addition power');
            $table->decimal('pd_distance', 8, 2)->nullable()->comment('Distance PD in mm');
            $table->decimal('pd_near', 8, 2)->nullable()->comment('Near PD in mm');
            
            // Best Corrected Visual Acuity
            $table->string('od_bcva')->nullable()->comment('Right eye Best Corrected Visual Acuity');
            $table->string('os_bcva')->nullable()->comment('Left eye Best Corrected Visual Acuity');
            
            // Intraocular Pressure
            $table->integer('iop_od')->nullable()->comment('Right eye IOP in mmHg');
            $table->integer('iop_os')->nullable()->comment('Left eye IOP in mmHg');
            
            // Clinical Notes
            $table->text('fundus_notes')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('management_plan')->nullable();
            
            // Next Visit
            $table->date('next_recall_date')->nullable();
            
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index(['customer_id', 'exam_date']);
            $table->index(['store_id', 'exam_date']);
            $table->index('exam_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eye_examinations');
    }
};
