<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->json('title'); // {"en": "...", "bn": "..."}
            $table->string('slug')->unique();
            $table->string('client');
            $table->json('industry'); // {"en": "Retail", "bn": "খুচরা বিক্রয়"}
            $table->json('challenge'); // Problem: {"en": "...", "bn": "..."}
            $table->json('solution'); // Solution: {"en": "...", "bn": "..."}
            $table->json('result'); // Result: {"en": "...", "bn": "..."}
            $table->string('image_path')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};
