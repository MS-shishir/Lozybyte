<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add fields to existing settings table
        Schema::table('settings', function (Blueprint $table) {
            $table->string('footer_logo_path')->nullable();
            $table->string('google_analytics_id')->nullable();
            $table->text('custom_scripts')->nullable();
        });

        // 2. Create nav_items table
        Schema::create('nav_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('nav_items')->onDelete('cascade');
            $table->json('label'); // Translatable {"en": "Home", "bn": "হোম"}
            $table->string('url');
            $table->integer('order')->default(0);
            $table->boolean('is_external')->default(false);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 3. Create homepage_sections table
        Schema::create('homepage_sections', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g. hero, why_choose_us, faq
            $table->json('title')->nullable();
            $table->json('subtitle')->nullable();
            $table->json('description')->nullable();
            $table->json('short_description')->nullable();
            $table->json('button_text')->nullable();
            $table->string('button_url')->nullable();
            $table->string('background_image')->nullable();
            $table->string('main_image')->nullable();
            $table->string('icon')->nullable();
            $table->json('cards')->nullable(); // Array of cards with custom details
            $table->json('statistics')->nullable(); // Array of counters/stats
            $table->json('colors')->nullable(); // Section specific color overrides
            $table->integer('sort_order')->default(0);
            $table->boolean('visible')->default(true);
            $table->timestamps();
        });

        // 4. Create pages table
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->string('slug')->unique();
            $table->string('banner_image')->nullable();
            $table->json('content'); // Rich content or blocks
            $table->json('seo')->nullable(); // meta_title, meta_description
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 5. Create media table
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('file_path');
            $table->string('file_type');
            $table->unsignedBigInteger('file_size');
            $table->timestamps();
        });

        // 6. Create visits table (Visitor Analytics)
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_id'); // Persistent cookie
            $table->string('session_id'); // Session cookie
            $table->string('ip_address');
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->string('device')->nullable();
            $table->string('url');
            $table->string('referrer')->nullable();
            $table->string('traffic_source')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visits');
        Schema::dropIfExists('media');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('homepage_sections');
        Schema::dropIfExists('nav_items');
        
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['footer_logo_path', 'google_analytics_id', 'custom_scripts']);
        });
    }
};
