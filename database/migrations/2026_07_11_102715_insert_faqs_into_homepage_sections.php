<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Find pricing sort_order to place faqs right after it
        $pricingOrder = DB::table('homepage_sections')
            ->where('key', 'pricing')
            ->value('sort_order');

        $faqsSortOrder = $pricingOrder ? $pricingOrder + 1 : 15;

        // Shift everything at or after faqsSortOrder up by 1 (skip if faqs already exists)
        $alreadyExists = DB::table('homepage_sections')->where('key', 'faqs')->exists();

        if (!$alreadyExists) {
            DB::table('homepage_sections')
                ->where('sort_order', '>=', $faqsSortOrder)
                ->increment('sort_order');

            DB::table('homepage_sections')->insert([
                'key'        => 'faqs',
                'title'      => json_encode(['en' => 'Frequently Asked Questions', 'bn' => 'সাধারণ জিজ্ঞাসা']),
                'subtitle'   => json_encode([
                    'en' => 'Everything you need to know about our products, billing, and support.',
                    'bn' => 'আমাদের প্রোডাক্ট, বিলিং ও সাপোর্ট সম্পর্কে সকল প্রয়োজনীয় তথ্য এখানে পাবেন।',
                ]),
                'visible'    => true,
                'sort_order' => $faqsSortOrder,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        $faqsRow = DB::table('homepage_sections')->where('key', 'faqs')->first();

        if ($faqsRow) {
            DB::table('homepage_sections')
                ->where('sort_order', '>', $faqsRow->sort_order)
                ->decrement('sort_order');

            DB::table('homepage_sections')->where('key', 'faqs')->delete();
        }
    }
};
