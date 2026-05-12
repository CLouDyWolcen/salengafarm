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
        Schema::table('site_visit_requests', function (Blueprint $table) {
            $table->foreignId('site_visit_id')->nullable()->after('user_id')->constrained('site_visits')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_visit_requests', function (Blueprint $table) {
            $table->dropForeign(['site_visit_id']);
            $table->dropColumn('site_visit_id');
        });
    }
};
