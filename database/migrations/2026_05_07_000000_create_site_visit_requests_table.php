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
        Schema::create('site_visit_requests', function (Blueprint $table) {
            $table->id();
            
            // Client information
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Request details
            $table->date('preferred_date');
            $table->time('preferred_time')->nullable();
            $table->string('property_address');
            $table->string('property_size')->nullable();
            $table->text('current_condition')->nullable();
            $table->text('project_description');
            $table->text('special_requirements')->nullable();
            
            // Photos (JSON array of file paths)
            $table->json('photos')->nullable();
            
            // Status tracking
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            
            // Review tracking
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_visit_requests');
    }
};
