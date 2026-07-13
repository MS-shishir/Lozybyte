<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Visit;
use App\Models\Lead;

Artisan::command('inspire', function () {
    $this->comment(Illuminate\Foundation\Inspiring::quote());
})->purpose('Display an inspiring quote');

// Data retention policy cleanup scheduler
Schedule::call(function () {
    // 1. Retention policy: delete visitor tracking records older than 365 days
    Visit::where('created_at', '<', now()->subDays(365))->delete();

    // 2. Retention policy: delete contact/quotation leads older than 730 days
    Lead::where('created_at', '<', now()->subDays(730))->delete();
})->daily();
