<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->json('title'); // {"en": "...", "bn": "..."}
            $table->string('slug')->unique();
            $table->string('category'); // e.g. 'School Management', 'POS', 'CRM'
            $table->json('pricing'); // {"monthly": {"price": 10, "link": ""}, "yearly": {...}, "lifetime": {...}}
            $table->string('demo_url')->nullable();
            $table->json('features'); // Array of translatable features: {"en": ["...", "..."], "bn": ["...", "..."]}
            $table->json('screenshots')->nullable(); // Array of image paths
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
