<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->json('company_name'); // Translatable json: {"en": "...", "bn": "..."}
            $table->json('tagline')->nullable(); // Translatable
            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->json('address')->nullable(); // Translatable
            $table->json('social_links')->nullable(); // Facebook, Twitter, LinkedIn, etc.
            $table->json('theme_config')->nullable(); // primary_color, secondary_color, font_family, button_style, default_mode
            $table->json('seo_defaults')->nullable(); // meta_title, meta_description, og_image, schema_markup
            $table->json('homepage_sections')->nullable(); // visibility toggle and configurations
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
