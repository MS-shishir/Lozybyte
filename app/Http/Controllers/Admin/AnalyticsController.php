<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Visit;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->input('range', '7days');
        $startDate = match ($range) {
            'today' => today(),
            'yesterday' => today()->subDay(),
            '30days' => now()->subDays(30),
            default => now()->subDays(7), // 7days default
        };

        // 1. KPI Cards
        $totalVisits = Visit::where('created_at', '>=', $startDate)->count();
        
        $uniqueVisitors = Visit::where('created_at', '>=', $startDate)
            ->distinct('visitor_id')
            ->count('visitor_id');

        // Returning visitors: visitor_id has visited in more than one session
        $returningVisitors = Visit::where('created_at', '>=', $startDate)
            ->select('visitor_id')
            ->groupBy('visitor_id')
            ->having(DB::raw('count(distinct session_id)'), '>', 1)
            ->get()
            ->count();

        $todaysVisitors = Visit::whereDate('created_at', today())
            ->distinct('visitor_id')
            ->count('visitor_id');

        // Live visitors: active in last 5 minutes
        $liveVisitors = Visit::where('created_at', '>=', now()->subMinutes(5))
            ->distinct('visitor_id')
            ->count('visitor_id');

        // 2. Timeline chart data (visitors per day)
        $chartData = Visit::where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(distinct visitor_id) as unique_visits'), DB::raw('count(id) as total_visits'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // 3. Breakdown Tables
        // Top Visited Pages
        $popularPages = Visit::where('created_at', '>=', $startDate)
            ->select('url', DB::raw('count(id) as page_views'), DB::raw('count(distinct visitor_id) as unique_page_views'))
            ->groupBy('url')
            ->orderBy('page_views', 'desc')
            ->take(10)
            ->get();

        // Browser counts
        $browsers = Visit::where('created_at', '>=', $startDate)
            ->select('browser', DB::raw('count(distinct visitor_id) as count'))
            ->groupBy('browser')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();

        // OS counts
        $operatingSystems = Visit::where('created_at', '>=', $startDate)
            ->select('os', DB::raw('count(distinct visitor_id) as count'))
            ->groupBy('os')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();

        // Device counts
        $devices = Visit::where('created_at', '>=', $startDate)
            ->select('device', DB::raw('count(distinct visitor_id) as count'))
            ->groupBy('device')
            ->orderBy('count', 'desc')
            ->get();

        // Country counts
        $countries = Visit::where('created_at', '>=', $startDate)
            ->select('country', DB::raw('count(distinct visitor_id) as count'))
            ->groupBy('country')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get();

        // Exit pages (last page visited in a session)
        $exitPages = DB::table('visits as v1')
            ->select('v1.url', DB::raw('count(v1.id) as count'))
            ->where('v1.created_at', '>=', $startDate)
            ->whereRaw('v1.id = (SELECT max(id) FROM visits WHERE session_id = v1.session_id)')
            ->groupBy('v1.url')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get();

        // Traffic Sources
        $trafficSources = Visit::where('created_at', '>=', $startDate)
            ->select('traffic_source', DB::raw('count(id) as count'))
            ->groupBy('traffic_source')
            ->orderBy('count', 'desc')
            ->get();

        // 4. Recent visits list (paginated)
        $recentVisits = Visit::latest()->paginate(25);

        return view('admin.analytics.index', compact(
            'totalVisits',
            'uniqueVisitors',
            'returningVisitors',
            'todaysVisitors',
            'liveVisitors',
            'chartData',
            'popularPages',
            'browsers',
            'operatingSystems',
            'devices',
            'countries',
            'exitPages',
            'trafficSources',
            'recentVisits',
            'range'
        ));
    }
}
