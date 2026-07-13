<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->json('title'); // {"en": "...", "bn": "..."}
            $table->string('slug')->unique();
            $table->string('icon')->nullable(); // Lucide icon name or SVG
            $table->string('image_path')->nullable();
            $table->json('description'); // {"en": "...", "bn": "..."}
            $table->json('details')->nullable(); // Detailed details for animated card expand: {"en": "...", "bn": "..."}
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
