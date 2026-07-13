<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Setting;
use App\Models\Service;
use App\Models\Product;
use App\Models\Portfolio;
use App\Models\Testimonial;
use App\Models\Team;
use App\Models\Category;
use App\Models\Post;
use App\Models\NavItem;
use App\Models\Client;
use App\Models\HomepageSection;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Admins
        User::updateOrCreate(
            ['email' => 'admin@lozybyte.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('admin123'),
                'role' => 'super_admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'content@lozybyte.com'],
            [
                'name' => 'Content Manager',
                'password' => Hash::make('content123'),
                'role' => 'content_manager',
            ]
        );

        User::updateOrCreate(
            ['email' => 'sales@lozybyte.com'],
            [
                'name' => 'Sales Manager',
                'password' => Hash::make('sales123'),
                'role' => 'sales_manager',
            ]
        );

        User::updateOrCreate(
            ['email' => 'saas@lozybyte.com'],
            [
                'name' => 'SaaS Manager',
                'password' => Hash::make('saas123'),
                'role' => 'saas_manager',
            ]
        );

        // 2. Seed Settings
        Setting::updateOrCreate(
            ['id' => 1],
            [
                'company_name' => [
                    'en' => 'Lozybyte',
                    'bn' => 'লজিইবাইট'],
                'tagline' => [
                    'en' => 'We Build Digital Businesses',
                    'bn' => 'আমরা ডিজিটাল ব্যবসা তৈরি করি'],
                'logo_path' => '/images/logo.png',
                'favicon_path' => '/images/favicon.ico',
                'footer_logo_path' => '/images/logo.png',
                'email' => 'info@lozybyte.com',
                'phone' => '+8801700000000',
                'address' => [
                    'en' => 'House 12, Road 5, Dhanmondi, Dhaka, Bangladesh',
                    'bn' => 'বাসা ১২, রোড ৫, ধানমন্ডি, ঢাকা, বাংলাদেশ'],
                'social_links' => [
                    'facebook' => 'https://facebook.com/lozybyte',
                    'twitter' => 'https://twitter.com/lozybyte',
                    'linkedin' => 'https://linkedin.com/company/lozybyte',
                    'github' => 'https://github.com/lozybyte'
                ],
                'theme_config' => [
                    'primary_color' => '#6366f1',   // Indigo-500
                    'secondary_color' => '#06b6d4', // Cyan-500
                    'font_family' => 'Outfit',      // Outfit font
                    'button_style' => 'rounded-full', // rounded-full, rounded-lg, rounded-none
                    'default_mode' => 'dark',        // light, dark
                    'navbar_cta_text' => [
                        'en' => 'Start Project',
                        'bn' => 'প্রজেক্ট শুরু করুন'],
                    'navbar_cta_url' => '#contact'
                ],
                'seo_defaults' => [
                    'meta_title' => [
                        'en' => 'Lozybyte | Headless Software & SaaS Development Company',
                        'bn' => 'লজিইবাইট | হেডলেস সফটওয়্যার ও সাস ডেভেলপমেন্ট কোম্পানি'],
                    'meta_description' => [
                        'en' => 'We design, build and scale custom SaaS platforms, high-performance web applications, and complete business automations.',
                        'bn' => 'আমরা কাস্টম সাস প্ল্যাটফর্ম, হাই-পারফরম্যান্স ওয়েব অ্যাপ্লিকেশন এবং ব্যবসায়িক অটোমেশন তৈরি ও স্কেল করি।'],
                    'og_image' => '/images/og-image.jpg',
                    'schema_markup' => '{
                        "@context": "https://schema.org",
                        "@type": "SoftwareApplication",
                        "name": "Lozybyte Solutions",
                        "url": "https://lozybyte.com",
                        "applicationCategory": "BusinessApplication"
                    }'
                ],
                'google_analytics_id' => 'G-XXXXXXXXXX',
                'custom_scripts' => '<!-- Custom Head Scripts -->'
            ]
        );

        // 3. Seed Services
        $services = [
            [
                'title' => ['en' => 'Website Development', 'bn' => 'ওয়েবসাইট ডেভেলপমেন্ট'],
                'slug' => 'website',
                'icon' => 'Globe',
                'description' => [
                    'en' => 'Ultra-fast headless sites using Next.js and Tailwind CSS.',
                    'bn' => 'নেক্সট জেএস এবং টেলউইন্ড সিএসএস সহ আল্ট্রা-ফাস্ট হেডলেস সাইট।'],
                'details' => [
                    'en' => 'We design SEO-optimized, highly interactive frontends coupled with headless CMS architectures to offer instant page loads and unparalleled security.',
                    'bn' => 'আমরা ইনস্ট্যান্ট পেজ লোড এবং অতুলনীয় সিকিউরিটি নিশ্চিত করতে এসইও-অপ্টিমাইজড, ইন্টারঅ্যাক্টিভ ফ্রন্টএন্ড এবং হেডলেস সিএমএস আর্কিটেকচার তৈরি করি।']
            ],
            [
                'title' => ['en' => 'SaaS Platforms', 'bn' => 'সাস প্ল্যাটফর্ম'],
                'slug' => 'saas',
                'icon' => 'Layers',
                'description' => [
                    'en' => 'Scalable subscription platforms with recurring billing integrations.',
                    'bn' => 'সাবস্ক্রিপশন এবং বিলিং ইন্টিগ্রেশন সহ স্কেলযোগ্য প্ল্যাটফর্ম।'],
                'details' => [
                    'en' => 'End-to-end multi-tenant SaaS development including subscription engines, tier control, usage analytics, and custom user portals.',
                    'bn' => 'সাবস্ক্রিপশন ইঞ্জিন, টিয়ার কন্ট্রোল, ব্যবহার অ্যানালিটিক্স এবং কাস্টম ইউজার পোর্টাল সহ মাল্টি-টেন্যান্ট সাস ডেভেলপমেন্ট।']
            ],
            [
                'title' => ['en' => 'Mobile Apps', 'bn' => 'মোবাইল অ্যাপস'],
                'slug' => 'mobile-app',
                'icon' => 'Smartphone',
                'description' => [
                    'en' => 'Cross-platform native apps built using Flutter and React Native.',
                    'bn' => 'ফ্লাটার এবং রিঅ্যাক্ট নেটিভ ব্যবহার করে তৈরি ক্রস-প্ল্যাটফর্ম নেティブ অ্যাপস।'],
                'details' => [
                    'en' => 'Deploy lightning-fast applications for iOS and Android using a shared codebase, complete with offline databases, push notifications, and geolocation.',
                    'bn' => 'অফলাইন ডাটাবেস, পুশ নোটিফিকেশন এবং জিওলোকেশন ফিচারের সমন্বয়ে একই কোডবেস দিয়ে দ্রুতগতির আইওএস ও অ্যান্ড্রয়েড অ্যাপ্লিকেশন।']
            ],
            [
                'title' => ['en' => 'ERP Solutions', 'bn' => 'ইআরপি সমাধান'],
                'slug' => 'erp',
                'icon' => 'Briefcase',
                'description' => [
                    'en' => 'Integrated enterprise systems to streamline internal workflows.',
                    'bn' => 'অভ্যন্তরীণ কর্মপ্রবাহ সহজ করার জন্য সমন্বিত এন্টারপ্রাইজ সিস্টেম।'],
                'details' => [
                    'en' => 'Custom modules for human resources, inventory management, supply chains, automated accounting, and robust business intelligence analytics.',
                    'bn' => 'হিউম্যান রিসোর্স, ইনভেন্টরি ম্যানেজমেন্ট, সাপ্লাই চেইন, অটোমেটেড অ্যাকাউন্টিং এবং শক্তিশালী বিজনেস ইন্টেলিজেন্স অ্যানালিটিক্স মডিউল।']
            ],
            [
                'title' => ['en' => 'POS Systems', 'bn' => 'পিওএস সিস্টেম'],
                'slug' => 'pos',
                'icon' => 'ShoppingCart',
                'description' => [
                    'en' => 'Cloud point of sale software with barcode and hardware support.',
                    'bn' => 'বারকোড এবং হার্ডওয়্যার সাপোর্ট সহ ক্লাউড পয়েন্ট অব সেল সফটওয়্যার।'],
                'details' => [
                    'en' => 'Real-time billing, offline sale syncing, invoice printing, multi-store support, customer loyalty point systems, and integrated payment gateways.',
                    'bn' => 'রিয়েল-টাইম বিলিং, অফলাইন সেল সিঙ্কিং, চালান প্রিন্ট, মাল্টি-স্টোর সাপোর্ট, লয়্যালটি পয়েন্ট সিস্টেম এবং পেমেন্ট গেটওয়ে।'
                ]
            ],
            [
                'title' => ['en' => 'School Software', 'bn' => 'স্কুল সফটওয়্যার'],
                'slug' => 'school',
                'icon' => 'BookOpen',
                'description' => [
                    'en' => 'Complete automation software for schools, colleges, and training centers.',
                    'bn' => 'স্কুল, কলেজ এবং ট্রেনিং সেন্টারের জন্য সম্পূর্ণ অটোমেশন সফটওয়্যার।'],
                'details' => [
                    'en' => 'Includes student information, grading metrics, online fees collections, staff payroll systems, parent-teacher portals, and live virtual classrooms.',
                    'bn' => 'ছাত্র বিবরণী, গ্রেডিং ম্যাট্রিক্স, অনলাইন ফি কালেকশন, স্টাফ পেরোল সিস্টেম, অভিভাবক পোর্টাল এবং লাইভ ভার্চুয়াল ক্লাসরুম।'
                ]
            ]
        ];

        foreach ($services as $srv) {
            Service::updateOrCreate(['slug' => $srv['slug']], $srv);
        }

// 4. Seed SaaS Products
$products = [
    [
        'title' => ['en' => 'School Management Platform', 'bn' => 'স্কুল ম্যানেজমেন্ট প্ল্যাটফর্ম'],
        'slug' => 'school-management',
        'category' => 'School',
        'pricing' => [
            'monthly' => ['price' => 49, 'link' => 'https://billing.lozybyte.com/school/monthly'],
            'yearly' => ['price' => 499, 'link' => 'https://billing.lozybyte.com/school/yearly'],
            'lifetime' => ['price' => 1999, 'link' => 'https://billing.lozybyte.com/school/lifetime']
        ],
        'demo_url' => 'https://school-demo.lozybyte.com',
        'features' => [
            'en' => ['Student & Staff Databases', 'Automated Gradebook', 'Online Fee Invoicing', 'Parent Portal Mobile App'],
            'bn' => ['ছাত্র ও শিক্ষক ডাটাবেস', 'স্বয়ংক্রিয় গ্রেডবুক', 'অনলাইন ফি চালান', 'অভিভাবক পোর্টাল মোবাইল অ্যাপ'],
            'ja' => ['自動成績簿', 'オンライン授業料請求', '保護者ポータル用モバイルアプリ', '生徒・スタッフ管理']
        ],
        'screenshots' => ['/images/school-dash-1.jpg', '/images/school-dash-2.jpg']
    ],
    [
        'title' => ['en' => 'Retail POS Engine', 'bn' => 'রিটেইল পিওএস ইঞ্জিন'],
        'slug' => 'retail-pos',
        'category' => 'POS',
        'pricing' => [
            'monthly' => ['price' => 29, 'link' => 'https://billing.lozybyte.com/pos/monthly'],
            'yearly' => ['price' => 299, 'link' => 'https://billing.lozybyte.com/pos/yearly'],
            'lifetime' => ['price' => 999, 'link' => 'https://billing.lozybyte.com/pos/lifetime']
        ],
        'demo_url' => 'https://pos-demo.lozybyte.com',
        'features' => [
            'en' => ['Barcode scanning support', 'Offline sales billing', 'Inventory stock tracking', 'Multi-payment checkout'],
            'bn' => ['বারকোড স্ক্যানিং সাপোর্ট', 'অফলাইন সেলস বিলিং', 'ইনভেন্টরি স্টক ট্র্যাকিং', 'মাল্টি-পেমেন্ট চেকআউট'],
            'ja' => ['オフライン販売請求', '在庫数自動追跡', '多様な決済チェックアウト', 'バーコードスキャン対応']
        ],
        'screenshots' => ['/images/pos-dash-1.jpg', '/images/pos-dash-2.jpg']
    ],
    [
        'title' => ['en' => 'Smart Pharmacy SaaS', 'bn' => 'স্মার্ট ফার্মেসি সাস'],
        'slug' => 'pharmacy-saas',
        'category' => 'Pharmacy',
        'pricing' => [
            'monthly' => ['price' => 35, 'link' => 'https://billing.lozybyte.com/pharmacy/monthly'],
            'yearly' => ['price' => 349, 'link' => 'https://billing.lozybyte.com/pharmacy/yearly'],
            'lifetime' => ['price' => 1200, 'link' => 'https://billing.lozybyte.com/pharmacy/lifetime']
        ],
        'demo_url' => 'https://pharmacy-demo.lozybyte.com',
        'features' => [
            'en' => ['Drug expiry alerts', 'Batch stock management', 'Generic name lookup', 'Supplier payments tracking'],
            'bn' => ['মেয়াদোত্তীর্ণ ড্রাগ অ্যালার্ট', 'ব্যাচ স্টক ম্যানেজমেন্ট', 'জেনেরিক নাম সার্চ', 'সরবরাহকারী পেমেন্ট ট্র্যাকিং'],
            'ja' => ['バッチ在庫管理', 'ジェネリック医薬品検索', 'サプライヤー支払管理', '薬剤有効期限アラート']
        ],
        'screenshots' => ['/images/pharmacy-dash-1.jpg']
    ],
    [
        'title' => ['en' => 'Restaurant Smart Billing', 'bn' => 'রেস্টুরেন্ট স্মার্ট বিলিং'],
        'slug' => 'restaurant-billing',
        'category' => 'Restaurant',
        'pricing' => [
            'monthly' => ['price' => 39, 'link' => 'https://billing.lozybyte.com/restaurant/monthly'],
            'yearly' => ['price' => 399, 'link' => 'https://billing.lozybyte.com/restaurant/yearly'],
            'lifetime' => ['price' => 1499, 'link' => 'https://billing.lozybyte.com/restaurant/lifetime']
        ],
        'demo_url' => 'https://restaurant-demo.lozybyte.com',
        'features' => [
            'en' => ['KOT display screens', 'Table reservation system', 'Menu items modifier builder', 'Dynamic QR code ordering'],
            'bn' => ['কেওটি ডিসপ্লে স্ক্রিন', 'টেবিল রিজার্ভেশন সিস্টেম', 'মেনু আইটেম মডিফায়ার', 'ডাইনামিক কিউআর কোড অর্ডার'],
            'ja' => ['テーブル予約システム', 'メニューオプションビルダー', 'テーブルQRコード発注', 'KOT表示スクリーン']
        ],
        'screenshots' => ['/images/restaurant-dash-1.jpg']
    ],
    [
        'title' => ['en' => 'Inventory Command Center', 'bn' => 'ইনভেন্টরি কমান্ড সেন্টার'],
        'slug' => 'inventory-center',
        'category' => 'Inventory',
        'pricing' => [
            'monthly' => ['price' => 45, 'link' => 'https://billing.lozybyte.com/inventory/monthly'],
            'yearly' => ['price' => 449, 'link' => 'https://billing.lozybyte.com/inventory/yearly'],
            'lifetime' => ['price' => 1500, 'link' => 'https://billing.lozybyte.com/inventory/lifetime']
        ],
        'demo_url' => 'https://inventory-demo.lozybyte.com',
        'features' => [
            'en' => ['Multi-warehouse transfer', 'Purchase orders generator', 'Low stock automatic alerts', 'Serial & SKU barcode tracking'],
            'bn' => ['মাল্টি-গুদাম ট্রান্সফার', 'ক্রয় অর্ডার জেনারেটর', 'কম স্টক অটো অ্যালার্ট', 'সিরিয়াল ও এসকেইউ ট্র্যাকিং'],
            'ja' => ['発注書自動発行', '在庫減少の自動通知', 'シリアル・SKU管理', 'マルチ倉庫転送']
        ],
        'screenshots' => ['/images/inventory-dash-1.jpg']
    ],
    [
        'title' => ['en' => 'Unified Sales CRM', 'bn' => 'ইউনিফাইড সেলস সিআরএম'],
        'slug' => 'sales-crm',
        'category' => 'CRM',
        'pricing' => [
            'monthly' => ['price' => 25, 'link' => 'https://billing.lozybyte.com/crm/monthly'],
            'yearly' => ['price' => 249, 'link' => 'https://billing.lozybyte.com/crm/yearly'],
            'lifetime' => ['price' => 799, 'link' => 'https://billing.lozybyte.com/crm/lifetime']
        ],
        'demo_url' => 'https://crm-demo.lozybyte.com',
        'features' => [
            'en' => ['Kanban pipeline views', 'Email campaign composer', 'Lead scoring algorithms', 'Call notes history tracking'],
            'bn' => ['কানবান পাইপলাইন ভিউ', 'ইমেইল ক্যাম্পেইন কম্পোজার', 'লিড স্কোরিং অ্যালগরিদম', 'কল নোটের ইতিহাস ট্র্যাকিং'],
            'ja' => ['メール配信作成', 'AIリード評価機能', '通話・連絡履歴管理', 'カンバンパイプライン']
        ],
        'screenshots' => ['/images/crm-dash-1.jpg']
    ],
    [
        'title' => ['en' => 'Core HRM Platform', 'bn' => 'কোর এইচআরএম প্ল্যাটফর্ম'],
        'slug' => 'core-hrm',
        'category' => 'HRM',
        'pricing' => [
            'monthly' => ['price' => 59, 'link' => 'https://billing.lozybyte.com/hrm/monthly'],
            'yearly' => ['price' => 599, 'link' => 'https://billing.lozybyte.com/hrm/yearly'],
            'lifetime' => ['price' => 2200, 'link' => 'https://billing.lozybyte.com/hrm/lifetime']
        ],
        'demo_url' => 'https://hrm-demo.lozybyte.com',
        'features' => [
            'en' => ['Attendance & Leave tracker', 'Salary payroll generator', 'Employee Performance metrics', 'Recruitment onboarding portal'],
            'bn' => ['উপস্থিতি ও ছুটি ট্র্যাকার', 'বেতন পেরোল জেনারেটর', 'কর্মী কর্মক্ষমতা পরিমাপ', 'নিয়োগ ও অনবোর্ডিং পোর্টাল'],
            'ja' => ['給与明細自動計算', '従業員業績評価ツール', '採用・入社手続きポータル', '勤怠・休暇管理']
        ],
        'screenshots' => ['/images/hrm-dash-1.jpg']
    ]
];
        foreach ($products as $prod) {
            Product::updateOrCreate(['slug' => $prod['slug']], $prod);
        }

        // 5. Seed Portfolios
        $portfolios = [
            [
                'title' => [
                    'en' => 'Revolutionizing School Systems in Tokyo',
                    'bn' => 'টোকিওতে স্কুল অটোমেশনে বিপ্লব'],
                'slug' => 'revolutionizing-tokyo-schools',
                'client' => 'Tokyo Academic Group',
                'industry' => ['en' => 'Education', 'bn' => 'শিক্ষা'],
                'challenge' => [
                    'en' => 'Managing student attendance, grades, and communication across 15 separate branches was highly inefficient due to disconnected legacy databases.',
                    'bn' => '১৫টি ভিন্ন শাখায় বিচ্ছিন্ন ডাটাবেসের কারণে ছাত্র উপস্থিতি, ফলাফল এবং যোগাযোগ ব্যবস্থা অনেক সময়সাপেক্ষ ছিল।'],
                'solution' => [
                    'en' => 'We deployed a high-performance headless school ERP with a Next.js central manager app and unified real-time dashboard.',
                    'bn' => 'আমরা নেক্সট জেএস দ্বারা চালিত একটি উন্নত সেন্ট্রাল ইআরপি এবং ইউনিফাইড রিয়েল-টাইম ড্যাশবোর্ড ডেপ্লয় করেছি।'],
                'result' => [
                    'en' => 'Reduced administrative overhead by 45%, automated online fee payments, and improved parent-teacher response rates by 60%.',
                    'bn' => 'অ্যাডমিনিস্ট্রেটিভ খরচ ৪৫% হ্রাস পেয়েছে, অনলাইন ফি পেমেন্ট স্বয়ংক্রিয় হয়েছে এবং অভিভাবক-শিক্ষক যোগাযোগ ৬০% বৃদ্ধি পেয়েছে।'],
                'image_path' => '/images/portfolio-school.jpg'
            ],
            [
                'title' => [
                    'en' => 'Scaling Retail Sales in Singapore',
                    'bn' => 'সিঙ্গাপুরে রিটেইল সেলস স্কেল করা'],
                'slug' => 'scaling-singapore-retail',
                'client' => 'Apex Retail Group',
                'industry' => ['en' => 'Retail', 'bn' => 'খুচরা বিক্রয়'],
                'challenge' => [
                    'en' => 'Their existing server crashed during peak holiday seasons, resulting in 20% loss of transaction data and customer dissatisfaction.',
                    'bn' => 'হলিডে সিজনে গ্রাহকদের উপচে পড়া ভিড়ের সময় তাদের আগের সার্ভার ক্র্যাশ করে, ফলে ২০% ট্রানজ্যাকশন ডেটা হারিয়ে যায়।'],
                'solution' => [
                    'en' => 'Built a cloud-based retail POS with local-first database caching and background synchronization capabilities.',
                    'bn' => 'আমরা লোকাল-ফার্স্ট ডেটা ক্যাশিং এবং ব্যাকগ্রাউন্ড সিঙ্ক্রোনাইজেশন সুবিধাযুক্ত একটি ক্লাউড রিটেইল পিওএস তৈরি করেছি।'],
                'result' => [
                    'en' => 'Achieved 100% database uptime during shopping seasons and scaled checkout times to under 3 seconds per client.',
                    'bn' => 'শপিং সিজনে ১০০% আপটাইম নিশ্চিত হয়েছে এবং প্রতিটি চেকআউট সম্পন্ন হতে ৩ সেকেন্ডেরও কম সময় লাগছে।'],
                'image_path' => '/images/portfolio-retail.jpg'
            ]
        ];

        foreach ($portfolios as $port) {
            Portfolio::updateOrCreate(['slug' => $port['slug']], $port);
        }

        // 6. Seed Testimonials
        $testimonials = [
            [
                'name' => 'Hiroshi Tanaka',
                'designation' => ['en' => 'Director of IT', 'bn' => 'আইটি পরিচালক'],
                'company' => 'Tanaka EduCorp',
                'review' => [
                    'en' => 'Lozybyte transformed our operation. Their headless CRM architecture handles thousands of requests smoothly and without lags.',
                    'bn' => 'লজিইবাইট আমাদের কার্যক্রম সম্পূর্ণ বদলে দিয়েছে। তাদের হেডলেস সিআরএম আর্কিটেকচার হাজার হাজার রিকোয়েস্ট অত্যন্ত চমৎকারভাবে হ্যান্ডেল করতে পারে।'],
                'rating' => 5,
                'image_path' => '/images/user-1.jpg'
            ],
            [
                'name' => 'Fahim Rahman',
                'designation' => ['en' => 'Founder & CEO', 'bn' => 'প্রতিষ্ঠাতা ও সিইও'],
                'company' => 'QuickPOS BD',
                'review' => [
                    'en' => 'The flexibility of their theme customizer and translations saved us weeks of development time. Very premium service!',
                    'bn' => 'তাদের থিম কাস্টমাইজেশন এবং সহজে মাল্টি-ল্যাঙ্গুয়েজ অনুবাদ ব্যবহারের সুবিধা আমাদের কয়েক সপ্তাহের ডেভেলপমেন্ট টাইম বাঁচিয়ে দিয়েছে। অসাধারণ সার্ভিস!'],
                'rating' => 5,
                'image_path' => '/images/user-2.jpg'
            ]
        ];

        foreach ($testimonials as $test) {
            Testimonial::updateOrCreate(['name' => $test['name']], $test);
        }

        // 7. Seed Team Members
        $teams = [
            [
                'name' => 'John Doe',
                'role' => ['en' => 'Lead Solutions Architect', 'bn' => 'প্রধান সলিউশন আর্কিটেক্ট'],
                'image_path' => '/images/team-1.jpg',
                'social_links' => ['linkedin' => 'https://linkedin.com/in/johndoe', 'github' => 'https://github.com/johndoe']
            ],
            [
                'name' => 'Shishir Ahmed',
                'role' => ['en' => 'Senior Backend Developer', 'bn' => 'সিনিয়র ব্যাকএন্ড ডেভেলপার'],
                'image_path' => '/images/team-2.jpg',
                'social_links' => ['linkedin' => 'https://linkedin.com/in/shishir', 'github' => 'https://github.com/shishir']
            ]
        ];

        foreach ($teams as $t) {
            Team::updateOrCreate(['name' => $t['name']], $t);
        }

        // 8. Seed Blog Categories & Posts
        $cats = [
            ['name' => ['en' => 'Web Development', 'bn' => 'ওয়েব ডেভেলপমেন্ট'], 'slug' => 'web-development'],
            ['name' => ['en' => 'SaaS', 'bn' => 'সাস'], 'slug' => 'saas'],
            ['name' => ['en' => 'Laravel', 'bn' => 'লারাভেল'], 'slug' => 'laravel'],
            ['name' => ['en' => 'Business Automation', 'bn' => 'বিজনেস অটোমেশন'], 'slug' => 'business-automation'],
            ['name' => ['en' => 'AI', 'bn' => 'এআই'], 'slug' => 'ai'],
        ];

        $categoryModels = [];
        foreach ($cats as $cat) {
            $categoryModels[] = Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        Post::updateOrCreate(
            ['slug' => 'why-headless-cms-future'],
            [
                'category_id' => $categoryModels[1]->id, // SaaS category
                'author_id' => 1, // Super Admin
                'title' => [
                    'en' => 'Why Headless CMS is the Future of Business Apps',
                    'bn' => 'কেন হেডলেস সিএমএস ব্যবসায়িক অ্যাপ্লিকেশনের ভবিষ্যৎ'],
                'content' => [
                    'en' => 'Headless CMS decouples your backend database storage from the presentation layer. By separating Laravel APIs from a Next.js frontend, businesses get maximum load speed, state-of-the-art security, and total flexibility to customize their user interface.',
                    'bn' => 'হেডলেস সিএমএস আপনার ব্যাকএন্ড ডাটাবেস স্টোরেজকে প্রেজেন্টেশন লেয়ার থেকে সম্পূর্ণ আলাদা করে। লারাভেল এপিআই-এর সাথে নেক্সট জেএস ফ্রন্টএন্ড যুক্ত করার ফলে দ্রুত লোড স্পিড, সর্বোচ্চ সিকিউরিটি এবং অসাধারণ ইউজার ইন্টারফেস তৈরি করা সম্ভব হয়।'],
                'image_path' => '/images/blog-headless.jpg',
                'seo' => [
                    'meta_title' => [
                        'en' => 'Why Headless CMS is the Future of Business Apps',
                        'bn' => 'কেন হেডলেস সিএমএস ব্যবসায়িক অ্যাপ্লিকেশনের ভবিষ্যৎ'],
                    'meta_description' => [
                        'en' => 'Understand the core benefits of building web applications with Laravel API and Next.js frontend.',
                        'bn' => 'লারাভেল এপিআই এবং নেক্সট জেএস ফ্রন্টএন্ড দিয়ে কেন ওয়েব অ্যাপ্লিকেশন তৈরি করা উচিত তা জানুন।']
                ]
            ]
        );

        // 9. Seed Navigation Items
        $navItems = [
            ['label' => ['en' => 'Home', 'bn' => 'হোম'], 'url' => '#home', 'order' => 1],
            ['label' => ['en' => 'Services', 'bn' => 'সার্ভিস'], 'url' => '#services', 'order' => 2],
            ['label' => ['en' => 'Products', 'bn' => 'মার্কেটপ্লেস'], 'url' => '#marketplace', 'order' => 3],
            ['label' => ['en' => 'Process', 'bn' => 'পদ্ধতি'], 'url' => '#process', 'order' => 4],
            ['label' => ['en' => 'Story', 'bn' => 'স্টোরি'], 'url' => '#about', 'order' => 5],
            ['label' => ['en' => 'Contact', 'bn' => 'যোগাযোগ'], 'url' => '#contact', 'order' => 6]
        ];

        foreach ($navItems as $nav) {
            NavItem::updateOrCreate(['url' => $nav['url']], $nav);
        }

        // 10. Seed Homepage Sections
        $sections = [
            [
                'key' => 'hero',
                'title' => [
                    'en' => 'We Build Digital Businesses',
                    'bn' => 'আমরা ডিজিটাল ব্যবসা তৈরি করি'],
                'subtitle' => [
                    'en' => 'Custom Software, SaaS Platforms & Enterprise Solutions.',
                    'bn' => 'কাস্টম সফটওয়্যার, সাস প্ল্যাটফর্ম এবং এন্টারপ্রাইজ সমাধান।'],
                'button_text' => [
                    'en' => 'Start Project',
                    'bn' => 'প্রজেক্ট শুরু করুন'],
                'button_url' => '#contact',
                'background_image' => 'https://assets.mixkit.co/videos/preview/mixkit-working-on-a-software-application-interface-43285-large.mp4',
                'sort_order' => 1,
                'visible' => true
            ],
            [
                'key' => 'client_logos',
                'title' => ['en' => 'Trusted by Growing Brands', 'bn' => 'বিশ্বস্ত ব্র্যান্ডসমূহ'],
                'subtitle' => ['en' => 'Companies that rely on our solutions', 'bn' => 'যে সব কোম্পানি আমাদের সমাধানের ওপর ভরসা করে'],
                'sort_order' => 2,
                'visible' => true
            ],
            [
                'key' => 'stats_counter',
                'title' => ['en' => 'Stats Counter', 'bn' => 'পরিসংখ্যান'],
                'statistics' => [
                    ['value' => '100+', 'label' => ['en' => 'Projects Delivered', 'bn' => 'প্রকল্প সম্পন্ন']],
                    ['value' => '50+', 'label' => ['en' => 'Happy Clients', 'bn' => 'সন্তুষ্ট ক্লায়েন্ট']],
                    ['value' => '99.9%', 'label' => ['en' => 'Uptime SLA', 'bn' => 'আপটাইম এসএলএ']],
                    ['value' => '24/7', 'label' => ['en' => 'Support', 'bn' => 'সহায়তা']]
                ],
                'sort_order' => 3,
                'visible' => true
            ],
            [
                'key' => 'services',
                'title' => ['en' => 'Services We Provide', 'bn' => 'আমাদের সার্ভিসসমূহ'],
                'subtitle' => ['en' => 'Innovative Headless Solutions', 'bn' => 'উদ্ভাবনী হেডলেস সলিউশন'],
                'sort_order' => 4,
                'visible' => true
            ],
            [
                'key' => 'service_wizard',
                'title' => ['en' => 'Find the Right Solution', 'bn' => 'সঠিক সমাধান খুঁজুন'],
                'subtitle' => ['en' => 'Answer a few questions and we will guide you', 'bn' => 'কয়েকটি প্রশ্নের উত্তর দিন, আমরা আপনাকে গাইড করব'],
                'sort_order' => 5,
                'visible' => true
            ],
            [
                'key' => 'tech_stack',
                'title' => ['en' => 'Our Technology Stack', 'bn' => 'আমাদের প্রযুক্তি সমূহ'],
                'subtitle' => ['en' => 'Modern & Robust Tools', 'bn' => 'আধুনিক ও শক্তিশালী টুলস'],
                'sort_order' => 6,
                'visible' => true
            ],
            [
                'key' => 'marketplace',
                'title' => ['en' => 'SaaS Product Marketplace', 'bn' => 'সাস প্রোডাক্ট মার্কেটপ্লেস'],
                'subtitle' => ['en' => 'Ready-to-deploy multi-tenant cloud applications', 'bn' => 'সহজেই ডেপ্লয়যোগ্য মাল্টি-টেন্যান্ট ক্লাউড অ্যাপ্লিকেশন'],
                'sort_order' => 7,
                'visible' => true
            ],
            [
                'key' => 'showcase',
                'title' => ['en' => 'Interactive Software Showcase', 'bn' => 'ইন্টারঅ্যাক্টিভ সফটওয়্যার শোকেস'],
                'sort_order' => 8,
                'visible' => true
            ],
            [
                'key' => 'growth_calculator',
                'title' => ['en' => 'ROI & Growth Calculator', 'bn' => 'আরওআই ও গ্রোথ ক্যালকুলেটর'],
                'sort_order' => 9,
                'visible' => true
            ],
            [
                'key' => 'process_timeline',
                'title' => ['en' => 'Our Core Development Process', 'bn' => 'আমাদের মূল উন্নয়ন প্রক্রিয়া'],
                'sort_order' => 10,
                'visible' => true
            ],
            [
                'key' => 'industries',
                'title' => ['en' => 'Industries We Serve', 'bn' => 'যেসব শিল্পে আমরা কাজ করি'],
                'sort_order' => 11,
                'visible' => true
            ],
            [
                'key' => 'before_after',
                'title' => ['en' => 'Visual Proof of Transformation', 'bn' => 'ডিজিটাল রূপান্তরের দৃশ্যমান প্রমাণ'],
                'sort_order' => 12,
                'visible' => true
            ],
            [
                'key' => 'founder_story',
                'title' => ['en' => 'Our Story', 'bn' => 'আমাদের গল্প'],
                'sort_order' => 13,
                'visible' => true
            ],
            [
                'key' => 'case_studies',
                'title' => ['en' => 'Proven Case Studies', 'bn' => 'সফল কেস স্টাডিজ'],
                'sort_order' => 14,
                'visible' => true
            ],
            [
                'key' => 'pricing',
                'title' => ['en' => 'Simple, Transparent Pricing', 'bn' => 'সহজ এবং স্বচ্ছ মূল্যতালিকা'],
                'sort_order' => 15,
                'visible' => true
            ],
            [
                'key' => 'team',
                'title' => ['en' => 'Meet Our Innovators', 'bn' => 'আমাদের উদ্ভাবক দল'],
                'sort_order' => 16,
                'visible' => true
            ],
            [
                'key' => 'blog',
                'title' => ['en' => 'Latest Insights & Articles', 'bn' => 'সাম্প্রতিক ব্লগ ও আর্টিকেল'],
                'sort_order' => 17,
                'visible' => true
            ],
            [
                'key' => 'testimonials',
                'title' => ['en' => 'What Our Clients Say', 'bn' => 'ক্লায়েন্টদের মতামত'],
                'sort_order' => 18,
                'visible' => true
            ],
            [
                'key' => 'contact',
                'title' => ['en' => 'Get a Free Consultation', 'bn' => 'ফ্রি কনসালটেশন নিন'],
                'sort_order' => 19,
                'visible' => true
            ],
            [
                'key' => 'ai_assistant',
                'title' => ['en' => 'AI Lead Assistant', 'bn' => 'এআই লিড অ্যাসিস্ট্যান্ট'],
                'sort_order' => 20,
                'visible' => true
            ]
        ];

        foreach ($sections as $sec) {
            HomepageSection::updateOrCreate(['key' => $sec['key']], $sec);
        }
    }
}
