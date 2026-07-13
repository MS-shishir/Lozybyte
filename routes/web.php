<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\NavController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\PricingPlanController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\HomepageSectionController;
use App\Http\Controllers\Admin\IndustryController;
use App\Http\Controllers\Api\ApiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Welcome Route (Fallback)
Route::get('/', function () {
    return view('admin.auth.login');
});

// Admin Authentication
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login.post')->middleware('throttle:login');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Admin Dashboard & Management (Requires Auth)
    Route::group(['middleware' => 'auth'], function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('live-editor', [DashboardController::class, 'liveEditor'])->name('live-editor');
        Route::get('profile', [DashboardController::class, 'editProfile'])->name('profile');
        Route::post('profile', [DashboardController::class, 'updateProfile'])->name('profile.update');

        // --- SUPER ADMIN & CONTENT MANAGER ROUTES ---
        Route::group(['middleware' => 'role:super_admin,content_manager'], function () {
            // Global Settings & Homepage builder
            Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
            Route::post('settings', [SettingController::class, 'update'])->name('settings.update');

            // Navigation Links CRUD
            Route::get('navbar', [NavController::class, 'index'])->name('navbar.index');
            Route::post('navbar', [NavController::class, 'store'])->name('navbar.store');
            Route::post('navbar/{navItem}', [NavController::class, 'update'])->name('navbar.update');
            Route::delete('navbar/{navItem}', [NavController::class, 'destroy'])->name('navbar.destroy');

            // Pages CRUD
            Route::get('pages', [PageController::class, 'index'])->name('pages.index');
            Route::post('pages', [PageController::class, 'store'])->name('pages.store');
            Route::post('pages/{page}', [PageController::class, 'update'])->name('pages.update');
            Route::delete('pages/{page}', [PageController::class, 'destroy'])->name('pages.destroy');

            // Media Manager
            Route::get('media', [MediaController::class, 'index'])->name('media.index');
            Route::post('media', [MediaController::class, 'store'])->name('media.store');
            Route::delete('media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');

            // Analytics Dashboard
            Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

            // Services CRUD
            Route::get('services', [ServiceController::class, 'index'])->name('services.index');
            Route::get('services/create', [ServiceController::class, 'create'])->name('services.create');
            Route::post('services', [ServiceController::class, 'store'])->name('services.store');
            Route::get('services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
            Route::post('services/{service}', [ServiceController::class, 'update'])->name('services.update');
            Route::delete('services/{service}', [ServiceController::class, 'destroy'])->name('services.delete');

            // Industries CRUD
            Route::get('industries', [IndustryController::class, 'index'])->name('industries.index');
            Route::post('industries', [IndustryController::class, 'store'])->name('industries.store');
            Route::post('industries/{industry}', [IndustryController::class, 'update'])->name('industries.update');
            Route::delete('industries/{industry}', [IndustryController::class, 'destroy'])->name('industries.delete');

            // Portfolio CRUD
            Route::get('portfolios', [PortfolioController::class, 'index'])->name('portfolios.index');
            Route::get('portfolios/create', [PortfolioController::class, 'create'])->name('portfolios.create');
            Route::post('portfolios', [PortfolioController::class, 'store'])->name('portfolios.store');
            Route::get('portfolios/{portfolio}/edit', [PortfolioController::class, 'edit'])->name('portfolios.edit');
            Route::post('portfolios/{portfolio}', [PortfolioController::class, 'update'])->name('portfolios.update');
            Route::delete('portfolios/{portfolio}', [PortfolioController::class, 'destroy'])->name('portfolios.delete');

            // Testimonials CRUD
            Route::get('testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');
            Route::get('testimonials/create', [TestimonialController::class, 'create'])->name('testimonials.create');
            Route::post('testimonials', [TestimonialController::class, 'store'])->name('testimonials.store');
            Route::get('testimonials/{testimonial}/edit', [TestimonialController::class, 'edit'])->name('testimonials.edit');
            Route::put('testimonials/{testimonial}', [TestimonialController::class, 'update'])->name('testimonials.update');
            Route::delete('testimonials/{testimonial}', [TestimonialController::class, 'destroy'])->name('testimonials.delete');

            // Team CRUD
            Route::get('teams', [TeamController::class, 'index'])->name('teams.index');
            Route::get('teams/create', [TeamController::class, 'create'])->name('teams.create');
            Route::post('teams', [TeamController::class, 'store'])->name('teams.store');
            Route::get('teams/{team}/edit', [TeamController::class, 'edit'])->name('teams.edit');
            Route::post('teams/{team}', [TeamController::class, 'update'])->name('teams.update');
            Route::delete('teams/{team}', [TeamController::class, 'destroy'])->name('teams.delete');

            // Blog CRUD
            Route::get('blog', [BlogController::class, 'index'])->name('blog.index');
            Route::get('blog/create', [BlogController::class, 'create'])->name('blog.create');
            Route::post('blog', [BlogController::class, 'store'])->name('blog.store');
            Route::get('blog/{post}/edit', [BlogController::class, 'edit'])->name('blog.edit');
            Route::post('blog/{post}', [BlogController::class, 'update'])->name('blog.update');
            Route::delete('blog/{post}', [BlogController::class, 'destroy'])->name('blog.delete');

            // Blog Categories
            Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
            Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.delete');

            // FAQs
            Route::resource('faqs', FaqController::class)->except(['show']);

            // Pricing Plans
            Route::resource('pricing', PricingPlanController::class)->except(['show']);

            // Clients
            Route::resource('clients', ClientController::class)->except(['show']);

            // Founder & Vision Custom Route
            Route::get('founder-and-vision', [HomepageSectionController::class, 'editFounderVision'])->name('founder-and-vision.edit');

            // Homepage Sections
            Route::resource('homepage-sections', HomepageSectionController::class)->except(['show']);
        });

        // --- SUPER ADMIN, CONTENT MANAGER & SAAS MANAGER ROUTES ---
        Route::group(['middleware' => 'role:super_admin,content_manager,saas_manager'], function () {
            // SaaS Products CRUD
            Route::get('products', [ProductController::class, 'index'])->name('products.index');
            Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
            Route::post('products', [ProductController::class, 'store'])->name('products.store');
            Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
            Route::post('products/{product}', [ProductController::class, 'update'])->name('products.update');
            Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.delete');
        });

        // --- SUPER ADMIN & SALES MANAGER ROUTES ---
        Route::group(['middleware' => 'role:super_admin,sales_manager'], function () {
            // Leads Dashboard
            Route::get('leads', [LeadController::class, 'index'])->name('leads.index');
            Route::get('leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
            Route::post('leads/{lead}', [LeadController::class, 'update'])->name('leads.update');
            Route::delete('leads/{lead}', [LeadController::class, 'destroy'])->name('leads.delete');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Headless REST JSON API Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'api', 'middleware' => ['throttle:api']], function () {
    Route::get('settings', [ApiController::class, 'getSettings']);
    Route::get('homepage', [ApiController::class, 'getHomepage']);
    Route::get('captcha', [ApiController::class, 'getCaptcha']);
    Route::get('services', [ApiController::class, 'getServices']);
    Route::get('industries', [ApiController::class, 'getIndustries']);
    Route::get('products', [ApiController::class, 'getProducts']);
    Route::get('portfolios', [ApiController::class, 'getPortfolios']);
    Route::get('testimonials', [ApiController::class, 'getTestimonials']);
    Route::get('teams', [ApiController::class, 'getTeam']);
    Route::get('categories', [ApiController::class, 'getCategories']);
    Route::get('posts', [ApiController::class, 'getPosts']);
    Route::get('posts/{slug}', [ApiController::class, 'getPostBySlug']);

    // Dynamic CMS Navigation, Sections, and Pages
    Route::get('nav-items', [ApiController::class, 'getNavItems']);
    Route::get('homepage-sections', [ApiController::class, 'getHomepageSections']);
    Route::get('pages', [ApiController::class, 'getPages']);
    Route::get('pages/{slug}', [ApiController::class, 'getPageBySlug']);
    
    // Missing Modules API Endpoints
    Route::get('faqs', [ApiController::class, 'getFaqs']);
    Route::get('pricing', [ApiController::class, 'getPricing']);
    Route::get('clients', [ApiController::class, 'getClients']);

    // Client-side Visitor Tracking Analytics (POST - bypass CSRF)
    Route::post('analytics/track', [ApiController::class, 'trackVisit'])
        ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

    // Form Lead Capture Submissions (POST - bypass CSRF, strict throttle)
    Route::post('leads', [ApiController::class, 'submitLead'])
        ->middleware('throttle:contact')
        ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

    // AI Assistant Product Recommender (POST - bypass CSRF)
    Route::post('ai-recommend', [ApiController::class, 'aiRecommend'])
        ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

    // Public Visual Element Styles Endpoint
    Route::get('element-styles', [ApiController::class, 'getElementStyles']);

    // Optimized media proxy — resizes + converts to WebP on the fly.
    Route::get('media/optimized/{path}', function (Request $request, $path) {
        $fullPath = storage_path('app/public/' . $path);

        if (!file_exists($fullPath)) {
            abort(404);
        }

        $width = (int) $request->query('w', 1920);
        $height = (int) $request->query('h', 1080);
        $quality = (int) $request->query('q', 80);
        $format = $request->query('format', 'webp');
        $fit = $request->query('fit', 'cover');

        // Clamp dimensions
        $width = max(1, min($width, 2400));
        $height = max(1, min($height, 2400));
        $quality = max(10, min($quality, 95));

        try {
            $image = \Intervention\Image\Facades\Image::read($fullPath);

            if ($fit === 'cover') {
                $image->fit($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            } else {
                $image->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            if ($format === 'webp') {
                $encoded = $image->toWebp($quality);
                $contentType = 'image/webp';
            } elseif ($format === 'avif') {
                $encoded = $image->toAvif($quality);
                $contentType = 'image/avif';
            } else {
                $encoded = $image->encode('jpg', $quality);
                $contentType = 'image/jpeg';
            }

            return response($encoded, 200)
                ->header('Content-Type', $contentType)
                ->header('Cache-Control', 'public, max-age=31536000, immutable')
                ->header('Accept-Ranges', 'bytes');
        } catch (\Throwable $e) {
            abort(500, 'Image processing failed.');
        }
    })->where('path', '.*');

    // Admin Visual Editor API Routes (Token Auth)
    Route::post('admin/login', [\App\Http\Controllers\Api\AdminApiController::class, 'login'])->middleware('throttle:login');
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('admin/check-auth', [\App\Http\Controllers\Api\AdminApiController::class, 'checkAuth']);
        Route::post('admin/logout', [\App\Http\Controllers\Api\AdminApiController::class, 'logout']);
        Route::post('admin/save-element', [\App\Http\Controllers\Api\AdminApiController::class, 'saveElement']);
        Route::post('admin/media/upload', [\App\Http\Controllers\Api\AdminApiController::class, 'uploadMedia']);
        Route::get('admin/media', [\App\Http\Controllers\Api\AdminApiController::class, 'listMedia']);
        Route::delete('admin/media/{id}', [\App\Http\Controllers\Api\AdminApiController::class, 'deleteMedia']);
    });
});
