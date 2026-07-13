<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('icon')->default('Package')->after('slug');
            $table->string('color')->default('#6366f1')->after('icon');
            $table->json('tagline')->nullable()->after('color');         // {"en": "...", "bn": "..."}
            $table->json('badge')->nullable()->after('tagline');         // {"en": "...", "bn": "..."}
            $table->string('badge_color')->nullable()->after('badge');
            $table->json('description')->nullable()->after('badge_color'); // {"en": "...", "bn": "..."}
            $table->unsignedInteger('clients_count')->default(0)->after('description');
            $table->double('rating', 2, 1)->default(4.8)->after('clients_count');
            $table->string('screenshot_type')->default('school')->after('rating');
            $table->unsignedSmallInteger('sort_order')->default(0)->after('screenshot_type');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'icon', 'color', 'tagline', 'badge', 'badge_color',
                'description', 'clients_count', 'rating', 'screenshot_type', 'sort_order'
            ]);
        });
    }
};
