<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pricing_plans', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['price', 'billing_cycle']);
        });

        Schema::table('pricing_plans', function (Blueprint $table) {
            // Category label like "School Plan", "POS Plan"
            $table->json('category')->nullable()->after('name');
            // Tagline / subheading
            $table->json('tagline')->nullable()->after('category');
            // Three billing price columns
            $table->string('price_monthly')->nullable()->after('tagline');
            $table->string('price_yearly')->nullable()->after('price_monthly');
            $table->string('price_lifetime')->nullable()->after('price_yearly');
            // Checkout links per billing period
            $table->string('link_monthly')->nullable()->after('price_lifetime');
            $table->string('link_yearly')->nullable()->after('link_monthly');
            $table->string('link_lifetime')->nullable()->after('link_yearly');
            // Accent color and badge
            $table->string('color')->default('#6366f1')->after('link_lifetime');
            $table->json('badge')->nullable()->after('color');
        });
    }

    public function down(): void
    {
        Schema::table('pricing_plans', function (Blueprint $table) {
            $table->dropColumn([
                'category', 'tagline',
                'price_monthly', 'price_yearly', 'price_lifetime',
                'link_monthly', 'link_yearly', 'link_lifetime',
                'color', 'badge'
            ]);
            $table->string('price')->nullable();
            $table->string('billing_cycle')->nullable();
        });
    }
};
