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
        // Drop table if exists (clean slate)
        Schema::dropIfExists('encrypted_files');
        
        // Create fresh table with all columns
        Schema::create('encrypted_files', function (Blueprint $table) {
            $table->id();
            $table->string('original_path', 500); // Original file path before encryption
            $table->string('encrypted_path', 500); // Path to encrypted file
            $table->string('original_filename'); // Original filename for downloads
            $table->string('file_type', 100); // MIME type
            $table->bigInteger('file_size')->unsigned(); // File size in bytes
            $table->unsignedBigInteger('uploaded_by')->nullable(); // User who uploaded (nullable for system-generated files)
            $table->string('encryption_algorithm', 50)->default('AES-256-CBC'); // Encryption method
            $table->timestamps();
            
            // Foreign key (nullable, so system can use null for auto-generated files)
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes for performance
            $table->index('original_path');
            $table->index('encrypted_path');
            $table->index('uploaded_by');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encrypted_files');
    }
};
