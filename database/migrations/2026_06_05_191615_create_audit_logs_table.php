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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_email');
            $table->string('user_role', 50);
            $table->string('action');
            $table->string('entity_type')->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->string('url', 500)->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            // Foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            
            // Indexes for performance
            $table->index(['user_id', 'created_at']);
            $table->index(['entity_type', 'entity_id']);
            $table->index('action');
            $table->index('created_at');
        });

        // Create triggers to prevent modifications (immutability)
        DB::unprepared('
            CREATE TRIGGER prevent_audit_log_update
            BEFORE UPDATE ON audit_logs
            FOR EACH ROW
            BEGIN
                SIGNAL SQLSTATE "45000" 
                SET MESSAGE_TEXT = "Audit logs cannot be modified";
            END
        ');

        DB::unprepared('
            CREATE TRIGGER prevent_audit_log_delete
            BEFORE DELETE ON audit_logs
            FOR EACH ROW
            BEGIN
                SIGNAL SQLSTATE "45000" 
                SET MESSAGE_TEXT = "Audit logs cannot be deleted";
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop triggers first
        DB::unprepared('DROP TRIGGER IF EXISTS prevent_audit_log_update');
        DB::unprepared('DROP TRIGGER IF EXISTS prevent_audit_log_delete');
        
        // Drop table
        Schema::dropIfExists('audit_logs');
    }
};
