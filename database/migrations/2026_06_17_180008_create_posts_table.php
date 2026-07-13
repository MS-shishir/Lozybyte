<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->json('title'); // {"en": "...", "bn": "..."}
            $table->string('slug')->unique();
            $table->json('content'); // Translatable post content (can store HTML or Markdown)
            $table->string('image_path')->nullable();
            $table->json('seo')->nullable(); // {"meta_title": {"en": ""}, "meta_description": {"en": ""}, "og_image": ""}
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
