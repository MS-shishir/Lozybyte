<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use App\Models\Setting;
use App\Models\Service;
use App\Models\Product;
use App\Models\Portfolio;
use App\Models\Testimonial;
use App\Models\Team;
use App\Models\Category;
use App\Models\Post;
use App\Models\Lead;
use App\Models\NavItem;
use App\Models\HomepageSection;
use App\Models\Page;
use App\Models\Visit;
use App\Models\Faq;
use App\Models\PricingPlan;
use App\Models\Client;
use App\Models\Industry;
use App\Http\Resources\SettingResource;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\IndustryResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\PortfolioResource;
use App\Http\Resources\TestimonialResource;
use App\Http\Resources\TeamResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\NavItemResource;
use App\Http\Resources\HomepageSectionResource;
use App\Http\Resources\PageResource;
use App\Http\Resources\FaqResource;
use App\Http\Resources\PricingPlanResource;
use App\Http\Resources\ClientResource;

class ApiController extends Controller
{
    public function __construct()
    {
        // Negotiate language from header or query param
        $lang = request()->header('Accept-Language', request()->query('lang', 'en'));
        if (in_array($lang, ['en', 'bn'])) {
            App::setLocale($lang);
        }
    }

    /**
     * Cache helper — keeps public responses fast and cheap under load.
     */
    private function cached(string $key, int $minutes, callable $callback)
    {
        return Cache::remember($key, now()->addMinutes($minutes), $callback);
    }

    public function getSettings()
    {
        $settings = $this->cached('settings_public', 60, fn () => Setting::first());
        if (!$settings) {
            return response()->json(['data' => null, 'message' => 'Settings not configured yet.'], 200);
        }
        return new SettingResource($settings);
    }

    public function getServices()
    {
        $services = $this->cached('services_public', 30, function () {
            return Service::where('status', true)->get();
        });
        return ServiceResource::collection($services);
    }

    public function getIndustries()
    {
        $industries = $this->cached('industries_public', 30, function () {
            return Industry::where('status', true)->orderBy('sort_order', 'asc')->get();
        });
        return IndustryResource::collection($industries);
    }

    public function getProducts()
    {
        $products = $this->cached('products_public', 30, function () {
            return Product::where('status', true)->get();
        });
        return ProductResource::collection($products);
    }

    public function getPortfolios()
    {
        $portfolios = $this->cached('portfolios_public', 30, function () {
            return Portfolio::where('status', true)->get();
        });
        return PortfolioResource::collection($portfolios);
    }

    public function getTestimonials()
    {
        $testimonials = $this->cached('testimonials_public', 30, function () {
            return Testimonial::where('status', true)->get();
        });
        return TestimonialResource::collection($testimonials);
    }

    public function getTeam()
    {
        $team = $this->cached('team_public', 30, function () {
            return Team::where('status', true)->get();
        });
        return TeamResource::collection($team);
    }

    public function getPosts(Request $request)
    {
        $posts = $this->cached('posts_public_' . md5($request->fullUrl()), 15, function () use ($request) {
            $query = Post::with('category', 'author:id,name')
                ->where('status', true);

            if ($request->filled('category')) {
                $query->whereHas('category', function ($q) use ($request) {
                    $q->where('slug', $request->category);
                });
            }

            return $query->latest()->paginate(10);
        });
        return PostResource::collection($posts);
    }

    public function getPostBySlug($slug)
    {
        $post = $this->cached("post_{$slug}", 30, function () use ($slug) {
            return Post::with('category', 'author:id,name')
                ->where('slug', $slug)
                ->where('status', true)
                ->firstOrFail();
        });
        return new PostResource($post);
    }

    public function getCategories()
    {
        $categories = $this->cached('categories_public', 30, function () {
            return Category::withCount(['posts' => function ($query) {
                $query->where('status', true);
            }])->get();
        });
        return CategoryResource::collection($categories);
    }

    /**
     * Single aggregated endpoint — replaces the 12 individual homepage calls.
     */
    public function getHomepage(Request $request)
    {
        $lang = $request->query('lang', 'en');

        $data = $this->cached("homepage_v1_{$lang}", 30, function () use ($request) {
            return [
                'settings'         => $this->resolve(SettingResource::class, Setting::first()),
                'services'         => $this->resolve(ServiceResource::class, Service::where('status', true)->get()),
                'industries'       => $this->resolve(IndustryResource::class, Industry::where('status', true)->orderBy('sort_order')->get()),
                'products'         => $this->resolve(ProductResource::class, Product::where('status', true)->get()),
                'portfolios'       => $this->resolve(PortfolioResource::class, Portfolio::where('status', true)->get()),
                'testimonials'     => $this->resolve(TestimonialResource::class, Testimonial::where('status', true)->get()),
                'team'             => $this->resolve(TeamResource::class, Team::where('status', true)->get()),
                'posts'            => $this->resolve(PostResource::class, Post::with('category', 'author:id,name')->where('status', true)->latest()->limit(12)->get()),
                'homepageSections' => $this->resolve(HomepageSectionResource::class, HomepageSection::orderBy('sort_order')->get()),
                'pricing'          => $this->resolve(PricingPlanResource::class, PricingPlan::where('status', true)->orderBy('sort_order')->get()),
                'clients'          => $this->resolve(ClientResource::class, Client::where('status', true)->orderBy('sort_order')->get()),
                'faqs'             => $this->resolve(FaqResource::class, Faq::where('status', true)->orderBy('sort_order')->get()),
                'navItems'         => $this->resolve(NavItemResource::class, NavItem::where('status', true)->whereNull('parent_id')
                    ->with(['children' => fn ($q) => $q->where('status', true)->orderBy('order')])
                    ->orderBy('order')->get()),
                'categories'       => $this->resolve(CategoryResource::class, Category::withCount(['posts' => fn ($q) => $q->where('status', true)])->get()),
            ];
        });

        return response()->json(['data' => $data]);
    }

    /**
     * Convert a resource (or collection) into a plain array for aggregation.
     */
    private function resolve(string $resourceClass, $model)
    {
        if ($model === null) {
            return null;
        }
        if ($model instanceof \Illuminate\Database\Eloquent\Collection || $model instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator) {
            return $resourceClass::collection($model)->resolve(request());
        }
        return (new $resourceClass($model))->resolve(request());
    }

    /**
     * Lightweight self-contained math CAPTCHA (no third-party service).
     */
    public function getCaptcha()
    {
        $a = random_int(1, 9);
        $b = random_int(1, 9);
        $token = Crypt::encryptString(json_encode([
            'answer' => $a + $b,
            'exp'    => now()->addMinutes(10)->timestamp,
        ]));

        return response()->json([
            'token'    => $token,
            'question' => "{$a} + {$b} = ?",
        ]);
    }

    public function submitLead(Request $request)
    {
        // Honeypot — bots fill hidden fields. Silently accept, never persist.
        if ($request->filled('hp_field')) {
            return response()->json([
                'success' => true,
                'message' => 'Lead captured successfully!',
            ], 201);
        }

        // CAPTCHA verification
        $token = $request->input('captcha_token');
        $answer = $request->input('captcha_answer');
        if (empty($token) || empty($answer)) {
            return response()->json(['success' => false, 'message' => 'CAPTCHA verification is required.'], 422);
        }
        try {
            $data = json_decode(Crypt::decryptString($token), true, 512, JSON_THROW_ON_ERROR);
            if (empty($data['exp']) || $data['exp'] < time() || (int) ($data['answer'] ?? -1) !== (int) $answer) {
                throw new \Exception('invalid');
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'CAPTCHA verification failed. Please try again.'], 422);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'source' => 'nullable|string|max:50',
            'service' => 'nullable|string|max:50',
            'message' => 'nullable|string|max:3000',
        ]);

        $lead = Lead::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Lead captured successfully!',
            'lead' => $lead
        ], 201);
    }

    public function aiRecommend(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:500'
        ]);

        $query = strtolower($request->input('query'));
        $products = Product::where('status', true)->get();

        $recommendedProduct = null;
        $highestScore = 0;

        // Simple and robust keyword matching algorithm
        $rules = [
            'school-management' => ['school', 'college', 'student', 'teacher', 'education', 'classroom', 'grade', 'admission', 'স্কুল', 'শিক্ষা', 'ছাত্র', '学校', '教育', '生徒'],
            'retail-pos' => ['pos', 'billing', 'invoice', 'checkout', 'shop', 'retail', 'sales', 'cashier', 'বিক্রয়', 'দোকান', 'ক্যাশ', 'レジ', '販売', '小売'],
            'pharmacy-saas' => ['pharmacy', 'medicine', 'drug', 'prescription', 'healthcare', 'ফার্মেসি', 'ওষুধ', 'ঔষধ', '薬局', '薬', '処方'],
            'restaurant-billing' => ['restaurant', 'cafe', 'food', 'table', 'menu', 'kot', 'ordering', 'রেস্টুরেন্ট', 'খাবার', 'মেনু', 'レストラン', 'カフェ', 'フード', '注文'],
            'inventory-center' => ['inventory', 'stock', 'warehouse', 'sku', 'purchase', 'supplier', 'ইনভেন্টরি', 'স্টক', 'গুদাম', '在庫', '倉庫', '仕入'],
            'sales-crm' => ['crm', 'customer', 'lead', 'pipeline', 'campaign', 'sales team', 'সিআরএম', 'গ্রাহক', 'যোগাযোগ', '顧客', 'リード', '営業'],
            'core-hrm' => ['hrm', 'hr', 'payroll', 'employee', 'attendance', 'salary', 'leave', 'এইচআর', 'কর্মচারী', 'বেতন', '人事', '給与', '勤怠', '社員']
        ];

        foreach ($products as $prod) {
            $slug = $prod->slug;
            if (isset($rules[$slug])) {
                $score = 0;
                foreach ($rules[$slug] as $kw) {
                    if (str_contains($query, $kw)) {
                        $score += 3; // exact word match weight
                    }
                }
                if ($score > $highestScore) {
                    $highestScore = $score;
                    $recommendedProduct = $prod;
                }
            }
        }

        if ($recommendedProduct && $highestScore > 0) {
            $lang = App::getLocale();
            $pName = $recommendedProduct->title; // Automatically translates using trait

            // Format dynamic response based on local language
            if ($lang === 'bn') {
                $msg = "আমাদের সিস্টেম আপনার জন্য আমাদের '{$pName}' প্রোডাক্টটি সুপারিশ করছে! এতে রয়েছে আকর্ষণীয় ফিচার ও সহজ পেমেন্ট সুবিধা।";
            } else {
                $msg = "Based on your requirements, I highly recommend our '{$pName}'. It includes full features, a live demo, and flexible pricing.";
            }

            return response()->json([
                'success' => true,
                'found' => true,
                'message' => $msg,
                'product' => [
                    'title' => $pName,
                    'slug' => $recommendedProduct->slug,
                    'category' => $recommendedProduct->category,
                    'pricing' => $recommendedProduct->pricing,
                    'demo_url' => $recommendedProduct->demo_url,
                    'features' => $recommendedProduct->features, // translates using trait
                ]
            ]);
        }

        // Default response when no matching products are found
        if (App::getLocale() === 'bn') {
            $msg = "আমি আপনার রিকোয়েস্ট বুঝতে পারছি। আপনার জন্য উপযুক্ত একটি সফটওয়্যার খুঁজে পেতে আমাদের একজন প্রতিনিধি সাহায্য করতে পারেন। অনুগ্রহ করে আপনার নাম ও যোগাযোগ নম্বর প্রদান করুন।";
        } elseif (App::getLocale() === 'ja') {
            $msg = "ご要望を理解しました。最適なソリューションを提案するため、詳細をお伺いできますか？よろしければお名前とご連絡先を入力してください。";
        } else {
            $msg = "I see! To better assist you and custom-tailor the perfect software solution, could you share your name and email/phone number so our sales specialist can reach out?";
        }

        return response()->json([
            'success' => true,
            'found' => false,
            'message' => $msg,
            'product' => null
        ]);
    }

    public function getNavItems()
    {
        $items = $this->cached('navitems_public', 30, function () {
            return NavItem::where('status', true)
                ->whereNull('parent_id')
                ->with(['children' => function ($q) {
                    $q->where('status', true)->orderBy('order', 'asc');
                }])
                ->orderBy('order', 'asc')
                ->get();
        });
        return NavItemResource::collection($items);
    }

    public function getHomepageSections()
    {
        $sections = $this->cached('homepage_sections_public', 30, function () {
            return HomepageSection::orderBy('sort_order', 'asc')->get();
        });
        return HomepageSectionResource::collection($sections);
    }

    public function getPages()
    {
        $pages = $this->cached('pages_public', 30, function () {
            return Page::where('status', true)->get(['id', 'title', 'slug', 'banner_image', 'seo']);
        });
        return PageResource::collection($pages);
    }

    public function getPageBySlug($slug)
    {
        $page = $this->cached("page_{$slug}", 30, function () use ($slug) {
            return Page::where('slug', $slug)
                ->where('status', true)
                ->firstOrFail();
        });
        return new PageResource($page);
    }

    public function getFaqs()
    {
        $faqs = $this->cached('faqs_public', 30, function () {
            return Faq::where('status', true)->orderBy('sort_order', 'asc')->get();
        });
        return FaqResource::collection($faqs);
    }

    public function getPricing()
    {
        $plans = $this->cached('pricing_public', 30, function () {
            return PricingPlan::where('status', true)->orderBy('sort_order', 'asc')->get();
        });
        return PricingPlanResource::collection($plans);
    }

    public function getClients()
    {
        $clients = $this->cached('clients_public', 30, function () {
            return Client::where('status', true)->orderBy('sort_order', 'asc')->get();
        });
        return ClientResource::collection($clients);
    }

    public function trackVisit(Request $request)
    {
        $visitorId = $request->input('visitor_id');
        $sessionId = $request->input('session_id');

        if (!$visitorId || !$sessionId) {
            return response()->json(['error' => 'Missing tracking identifiers'], 400);
        }

        $ip = $request->ip();
        $userAgent = $request->header('User-Agent');

        // Parse User Agent
        $parsedAgent = $this->parseUserAgent($userAgent);

        // Resolve Geolocation
        $geo = $this->resolveGeoIP($ip);

        $visit = Visit::create([
            'visitor_id' => $visitorId,
            'session_id' => $sessionId,
            'ip_address' => $ip,
            'country' => $geo['country'] ?? 'Local/Unknown',
            'city' => $geo['city'] ?? 'Local/Unknown',
            'browser' => $parsedAgent['browser'],
            'os' => $parsedAgent['os'],
            'device' => $parsedAgent['device'],
            'url' => $request->input('url', '/'),
            'referrer' => $request->input('referrer'),
            'traffic_source' => $this->determineTrafficSource($request->input('referrer'))
        ]);

        return response()->json(['success' => true, 'visit' => $visit], 201);
    }

    private function parseUserAgent($ua)
    {
        $browser = 'Other';
        $os = 'Other';
        $device = 'desktop';

        if (empty($ua)) {
            return compact('browser', 'os', 'device');
        }

        // Detect OS
        if (preg_match('/windows|win32/i', $ua)) {
            $os = 'Windows';
        } elseif (preg_match('/macintosh|mac os x/i', $ua)) {
            $os = 'macOS';
        } elseif (preg_match('/android/i', $ua)) {
            $os = 'Android';
            $device = 'mobile';
        } elseif (preg_match('/iphone|ipad|ipod/i', $ua)) {
            $os = 'iOS';
            $device = preg_match('/ipad/i', $ua) ? 'tablet' : 'mobile';
        } elseif (preg_match('/linux/i', $ua)) {
            $os = 'Linux';
        }

        // Detect Device Type (fallback check)
        if ($device === 'desktop') {
            if (preg_match('/mobile|phone|opera mini|iemobile/i', $ua)) {
                $device = 'mobile';
            } elseif (preg_match('/tablet|playbook|kindle/i', $ua)) {
                $device = 'tablet';
            }
        }

        // Detect Browser
        if (preg_match('/msie|trident/i', $ua)) {
            $browser = 'Internet Explorer';
        } elseif (preg_match('/firefox/i', $ua)) {
            $browser = 'Firefox';
        } elseif (preg_match('/chrome/i', $ua)) {
            $browser = 'Chrome';
            if (preg_match('/edge|edg/i', $ua)) {
                $browser = 'Edge';
            } elseif (preg_match('/opr/i', $ua)) {
                $browser = 'Opera';
            }
        } elseif (preg_match('/safari/i', $ua)) {
            $browser = 'Safari';
        }

        return compact('browser', 'os', 'device');
    }

    private function resolveGeoIP($ip)
    {
        if ($ip === '127.0.0.1' || $ip === '::1' || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
            return ['country' => 'Localhost', 'city' => 'Localhost'];
        }

        try {
            $ctx = stream_context_create(['http' => ['timeout' => 1.5]]);
            $response = @file_get_contents("http://ip-api.com/json/{$ip}", false, $ctx);
            if ($response) {
                $data = json_decode($response, true);
                if (isset($data['status']) && $data['status'] === 'success') {
                    return [
                        'country' => $data['country'] ?? 'Unknown',
                        'city' => $data['city'] ?? 'Unknown'
                    ];
                }
            }
        } catch (\Exception $e) {
            // Fallback
        }

        return ['country' => 'Unknown', 'city' => 'Unknown'];
    }

    private function determineTrafficSource($referrer)
    {
        if (empty($referrer)) {
            return 'Direct';
        }

        $host = parse_url($referrer, PHP_URL_HOST);
        if (empty($host)) {
            return 'Direct';
        }

        if (preg_match('/google|bing|yahoo|duckduckgo|baidu/i', $host)) {
            return 'Search Engine';
        }

        if (preg_match('/facebook|twitter|t.co|instagram|linkedin|reddit|youtube/i', $host)) {
            return 'Social Media';
        }

        return 'Referral';
    }

    public function getElementStyles()
    {
        $elements = \App\Models\EditableElement::all();
        $styles = [];
        foreach ($elements as $el) {
            $styles[$el->element_key] = [
                'content' => $el->content,
                'styles' => $el->styles ?: [],
                'settings' => $el->settings ?: [],
                'metadata' => $el->metadata ?: [],
            ];
        }
        return response()->json([
            'success' => true,
            'styles' => $styles
        ]);
    }
}
