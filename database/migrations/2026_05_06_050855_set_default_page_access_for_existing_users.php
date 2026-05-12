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
        // Set default page access for existing users
        DB::table('users')->whereNull('page_access')->update([
            'page_access' => DB::raw("
                CASE 
                    WHEN role = 'super_admin' THEN NULL
                    WHEN role = 'admin' THEN '[\"dashboard\",\"inventory\",\"point_of_sale\",\"requests\",\"site_visits\"]'
                    WHEN role = 'client' THEN '[\"dashboard\",\"plant_guide\",\"site_data\"]'
                    ELSE '[]'
                END
            ")
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally revert to NULL if needed
        // DB::table('users')->update(['page_access' => null]);
    }
};
