<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PricingPlan;

class PricingPlansSeeder extends Seeder
{
    public function run(): void
    {
        PricingPlan::truncate();

        $plans = [
            [
                'name'           => ['en' => 'School Management Platform', 'bn' => 'স্কুল ম্যানেজমেন্ট প্ল্যাটফর্ম'],
                'category'       => ['en' => 'School Plan', 'bn' => 'স্কুল প্ল্যান'],
                'tagline'        => ['en' => 'Best for schools & colleges', 'bn' => 'স্কুল-কলেজের জন্য সেরা সমাধান'],
                'badge'          => ['en' => '', 'bn' => ''],
                'description'    => ['en' => 'Complete school ERP solution.', 'bn' => 'সম্পূর্ণ স্কুল ইআরপি সমাধান।'],
                'features'       => [
                    'en' => ['Student Management', 'Fee Collection', 'Attendance Tracking', 'Report Generation', 'Parent Portal'],
                    'bn' => ['ছাত্র ব্যবস্থাপনা', 'ফি সংগ্রহ', 'উপস্থিতি ট্র্যাকিং', 'রিপোর্ট তৈরি', 'অভিভাবক পোর্টাল'],
                ],
                'price_monthly'  => '49',
                'price_yearly'   => '499',
                'price_lifetime' => '999',
                'link_monthly'   => '#contact',
                'link_yearly'    => '#contact',
                'link_lifetime'  => '#contact',
                'color'          => '#6366f1',
                'is_featured'    => false,
                'status'         => true,
                'sort_order'     => 0,
            ],
            [
                'name'           => ['en' => 'Retail POS Engine', 'bn' => 'রিটেইল পিওএস ইঞ্জিন'],
                'category'       => ['en' => 'POS Plan', 'bn' => 'পিওএস প্ল্যান'],
                'tagline'        => ['en' => 'Ideal for retail & shops', 'bn' => 'খুচরা দোকান ও রিটেইলের জন্য আদর্শ'],
                'badge'          => ['en' => 'Popular', 'bn' => 'জনপ্রিয়'],
                'description'    => ['en' => 'Fast billing and inventory management.', 'bn' => 'দ্রুত বিলিং ও ইনভেন্টরি ব্যবস্থাপনা।'],
                'features'       => [
                    'en' => ['Fast Billing', 'Inventory Sync', 'Cashier Management', 'Sales Reports', 'Multi-Branch Support'],
                    'bn' => ['দ্রুত বিলিং', 'ইনভেন্টরি সিঙ্ক', 'ক্যাশিয়ার ব্যবস্থাপনা', 'বিক্রয় রিপোর্ট', 'মাল্টি-ব্র্যাঞ্চ সাপোর্ট'],
                ],
                'price_monthly'  => '29',
                'price_yearly'   => '299',
                'price_lifetime' => '699',
                'link_monthly'   => '#contact',
                'link_yearly'    => '#contact',
                'link_lifetime'  => '#contact',
                'color'          => '#8b5cf6',
                'is_featured'    => true,
                'status'         => true,
                'sort_order'     => 1,
            ],
            [
                'name'           => ['en' => 'Smart Pharmacy SaaS', 'bn' => 'স্মার্ট ফার্মেসি সাস'],
                'category'       => ['en' => 'Pharmacy Plan', 'bn' => 'ফার্মেসি প্ল্যান'],
                'tagline'        => ['en' => 'Built for pharmacies & clinics', 'bn' => 'ফার্মেসি ও ক্লিনিকের জন্য তৈরি'],
                'badge'          => ['en' => '', 'bn' => ''],
                'description'    => ['en' => 'Complete pharmacy management solution.', 'bn' => 'সম্পূর্ণ ফার্মেসি ব্যবস্থাপনা সমাধান।'],
                'features'       => [
                    'en' => ['Drug Management', 'Prescription Tracking', 'Expiry Alerts', 'Supplier Management', 'GST Billing'],
                    'bn' => ['ওষুধ ব্যবস্থাপনা', 'প্রেসক্রিপশন ট্র্যাকিং', 'মেয়াদ উত্তীর্ণ সতর্কতা', 'সরবরাহকারী ব্যবস্থাপনা', 'জিএসটি বিলিং'],
                ],
                'price_monthly'  => '35',
                'price_yearly'   => '349',
                'price_lifetime' => '799',
                'link_monthly'   => '#contact',
                'link_yearly'    => '#contact',
                'link_lifetime'  => '#contact',
                'color'          => '#06b6d4',
                'is_featured'    => false,
                'status'         => true,
                'sort_order'     => 2,
            ],
        ];

        foreach ($plans as $plan) {
            PricingPlan::create($plan);
        }
    }
}
