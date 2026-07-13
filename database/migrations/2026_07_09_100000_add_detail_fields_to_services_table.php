<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('color')->default('#6366f1')->after('icon');
            $table->string('glow_color')->default('rgba(99,102,241,0.2)')->after('color');
            $table->string('timeline')->nullable()->after('glow_color');        // e.g. "2–4 weeks"
            $table->string('starting_price')->nullable()->after('timeline');   // e.g. "$500"
            $table->json('case_result')->nullable()->after('starting_price');  // {"en": "...", "bn": "..."}
            $table->json('features')->nullable()->after('case_result');        // {"en": [...], "bn": [...]}
            $table->json('process_steps')->nullable()->after('features');      // {"en": [...], "bn": [...]}
            $table->unsignedSmallInteger('sort_order')->default(0)->after('process_steps');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'color', 'glow_color', 'timeline', 'starting_price',
                'case_result', 'features', 'process_steps', 'sort_order'
            ]);
        });
    }
};
