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
        Schema::table('settings', function (Blueprint $table) {
            $table->json('website_name')->nullable()->after('company_name');
            $table->json('company_description')->nullable()->after('tagline');
            $table->string('dark_logo_path')->nullable()->after('logo_path');
            $table->string('mobile_logo_path')->nullable()->after('dark_logo_path');
            $table->string('whatsapp')->nullable()->after('phone');
            $table->text('google_map_embed')->nullable()->after('whatsapp');
            $table->json('business_hours')->nullable()->after('address');
            $table->string('google_tag_manager_id')->nullable()->after('google_analytics_id');
            $table->string('facebook_pixel_id')->nullable()->after('google_tag_manager_id');
            $table->text('footer_scripts')->nullable()->after('custom_scripts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'website_name',
                'company_description',
                'dark_logo_path',
                'mobile_logo_path',
                'whatsapp',
                'google_map_embed',
                'business_hours',
                'google_tag_manager_id',
                'facebook_pixel_id',
                'footer_scripts'
            ]);
        });
    }
};
