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
        // Update all users with role 'user' to 'client' and set is_client = true
        DB::table('users')
            ->where('role', 'user')
            ->update([
                'role' => 'client',
                'is_client' => true,
                'updated_at' => now()
            ]);

        // Ensure all existing users have is_client = true (except admins)
        DB::table('users')
            ->whereNotIn('role', ['super_admin', 'admin'])
            ->update([
                'is_client' => true,
                'updated_at' => now()
            ]);

        // Remove any users with 'manager' role (dead code cleanup)
        // Convert them to 'admin' if any exist
        DB::table('users')
            ->where('role', 'manager')
            ->update([
                'role' => 'admin',
                'updated_at' => now()
            ]);

        // Update the default role in users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('client')->change();
            $table->boolean('is_client')->default(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert default values
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->change();
            $table->boolean('is_client')->default(false)->change();
        });

        // Note: We don't revert the role changes as that would be destructive
        // Manual intervention would be required to restore original roles
    }
};