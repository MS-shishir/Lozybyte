<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Named rate limiters — stricter than the old flat 60/min.
        RateLimiter::for('api', fn (Request $request) =>
            Limit::perMinute((int) env('API_RATE_LIMIT', 60))->by($request->ip())
        );

        RateLimiter::for('contact', fn (Request $request) =>
            Limit::perMinutes(10, (int) env('CONTACT_RATE_LIMIT', 3))->by($request->ip())
        );

        RateLimiter::for('login', fn (Request $request) =>
            Limit::perMinutes(15, (int) env('LOGIN_RATE_LIMIT', 5))->by($request->ip() . '|' . $request->input('email'))
        );
    }
}
