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
        Schema::table('users', function (Blueprint $table) {
            // Individual address fields (split from 'address')
            $table->string('city')->nullable()->after('address');
            $table->string('province')->nullable()->after('city');
            $table->string('zip_code')->nullable()->after('province');
            
            // Individual property information
            $table->enum('property_type', ['residential', 'commercial', 'industrial', 'agricultural', 'mixed_use'])->nullable()->after('gender');
            
            // Company address fields (split company_address)
            $table->string('company_city')->nullable()->after('company_address');
            $table->string('company_province')->nullable()->after('company_city');
            $table->string('company_zip_code')->nullable()->after('company_province');
            
            // Company contact person information
            $table->string('company_contact_person')->nullable()->after('company_zip_code');
            $table->string('position')->nullable()->after('company_contact_person');
            $table->string('company_phone_number')->nullable()->after('position');
            
            // Company business information
            $table->enum('business_type', ['landscaping', 'real_estate', 'construction', 'hospitality', 'retail', 'agriculture', 'other'])->nullable()->after('company_phone_number');
            $table->string('tin')->nullable()->after('business_type');
            $table->string('website_socials')->nullable()->after('tin');
            
            // Profile completion flag
            $table->boolean('profile_completed')->default(false)->after('page_access');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'city',
                'province',
                'zip_code',
                'property_type',
                'company_city',
                'company_province',
                'company_zip_code',
                'company_contact_person',
                'position',
                'company_phone_number',
                'business_type',
                'tin',
                'website_socials',
                'profile_completed',
            ]);
        });
    }
};
