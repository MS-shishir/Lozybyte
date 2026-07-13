@extends('admin.layouts.app')

@section('page_title', 'Visitor Analytics')

@section('content')
<!-- Chart.js CDN for beautiful interactive line graphs -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Filter Headers -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <h3 class="text-lg font-bold text-slate-100"><i class="fa-solid fa-chart-line text-indigo-500 mr-2"></i> Real-time Traffic Metrics</h3>
    
    <!-- Time range filter -->
    <div class="flex bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-2xl p-1 shadow-lg shrink-0">
        <a href="{{ route('admin.analytics.index', ['range' => 'today']) }}" class="px-4 py-1.5 rounded-xl text-xs font-semibold {{ $range === 'today' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-slate-200' }}">Today</a>
        <a href="{{ route('admin.analytics.index', ['range' => 'yesterday']) }}" class="px-4 py-1.5 rounded-xl text-xs font-semibold {{ $range === 'yesterday' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-slate-200' }}">Yesterday</a>
        <a href="{{ route('admin.analytics.index', ['range' => '7days']) }}" class="px-4 py-1.5 rounded-xl text-xs font-semibold {{ $range === '7days' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-slate-200' }}">Last 7 Days</a>
        <a href="{{ route('admin.analytics.index', ['range' => '30days']) }}" class="px-4 py-1.5 rounded-xl text-xs font-semibold {{ $range === '30days' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-slate-200' }}">Last 30 Days</a>
    </div>
</div>

<!-- KPI Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
    
    <!-- Live Visitors -->
    <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl p-6 relative overflow-hidden group shadow-lg">
        <div class="absolute -right-3 -bottom-3 text-rose-500/10 text-8xl group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-tower-broadcast"></i>
        </div>
        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-2">Live Visitors (5m)</span>
        <h3 class="text-3xl font-bold text-slate-50 mb-1 flex items-center">
            <span class="w-3 h-3 bg-rose-500 rounded-full animate-ping mr-2.5 inline-block shrink-0"></span>
            {{ $liveVisitors }}
        </h3>
        <p class="text-xs text-rose-400 font-medium">Real-time active users</p>
    </div>

    <!-- Today's Visitors -->
    <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl p-6 relative overflow-hidden group shadow-lg">
        <div class="absolute -right-3 -bottom-3 text-emerald-500/10 text-8xl group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-calendar-day"></i>
        </div>
        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-2">Today's Visitors</span>
        <h3 class="text-3xl font-bold text-slate-50 mb-1">{{ $todaysVisitors }}</h3>
        <p class="text-xs text-emerald-400 font-medium">Unique views today</p>
    </div>

    <!-- Unique Visitors -->
    <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl p-6 relative overflow-hidden group shadow-lg">
        <div class="absolute -right-3 -bottom-3 text-indigo-500/10 text-8xl group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-users"></i>
        </div>
        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-2">Unique Visitors</span>
        <h3 class="text-3xl font-bold text-slate-50 mb-1">{{ $uniqueVisitors }}</h3>
        <p class="text-xs text-indigo-400 font-medium">For selected period</p>
    </div>

    <!-- Returning Visitors -->
    <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl p-6 relative overflow-hidden group shadow-lg">
        <div class="absolute -right-3 -bottom-3 text-cyan-500/10 text-8xl group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-rotate-left"></i>
        </div>
        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-2">Returning Visitors</span>
        <h3 class="text-3xl font-bold text-slate-50 mb-1">{{ $returningVisitors }}</h3>
        <p class="text-xs text-cyan-400 font-medium">Loyal returning audience</p>
    </div>

    <!-- Total Pageviews -->
    <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl p-6 relative overflow-hidden group shadow-lg">
        <div class="absolute -right-3 -bottom-3 text-amber-500/10 text-8xl group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-eye"></i>
        </div>
        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-2">Total Page Views</span>
        <h3 class="text-3xl font-bold text-slate-50 mb-1">{{ $totalVisits }}</h3>
        <p class="text-xs text-amber-400 font-medium">Total server hits logged</p>
    </div>
</div>

<!-- Chart Section -->
<div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl p-6 mb-8 shadow-lg">
    <h4 class="font-bold text-slate-100 flex items-center mb-6">
        <i class="fa-solid fa-chart-area text-indigo-500 mr-2"></i>
        Traffic Trends Timeline
    </h4>
    <div class="h-80 w-full relative">
        <canvas id="trafficChart" class="w-full h-full"></canvas>
    </div>
</div>

<!-- Breakdown Tables -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    
    <!-- Top Visited Pages -->
    <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl p-6 shadow-lg">
        <h4 class="font-bold text-slate-100 flex items-center mb-6 border-b border-slate-700/50 pb-3">
            <i class="fa-solid fa-file-invoice text-indigo-500 mr-2"></i> Top Visited Pages
        </h4>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-slate-700/50 text-slate-500 font-bold uppercase tracking-wider">
                        <th class="py-2.5 px-3">Page URL</th>
                        <th class="py-2.5 px-3 text-center">Views</th>
                        <th class="py-2.5 px-3 text-center">Uniques</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40 text-slate-300">
                    @forelse($popularPages as $page)
                    <tr>
                        <td class="py-3 px-3 font-mono text-indigo-400 truncate max-w-xs">{{ $page->url }}</td>
                        <td class="py-3 px-3 text-center font-bold">{{ $page->page_views }}</td>
                        <td class="py-3 px-3 text-center font-semibold text-slate-400">{{ $page->unique_page_views }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-8 text-center text-slate-500">No page views tracked yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Exit Pages -->
    <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl p-6 shadow-lg">
        <h4 class="font-bold text-slate-100 flex items-center mb-6 border-b border-slate-700/50 pb-3">
            <i class="fa-solid fa-arrow-right-from-bracket text-indigo-500 mr-2"></i> Top Exit Pages
        </h4>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-slate-700/50 text-slate-500 font-bold uppercase tracking-wider">
                        <th class="py-2.5 px-3">Exit URL</th>
                        <th class="py-2.5 px-3 text-center">Session Exits</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40 text-slate-300">
                    @forelse($exitPages as $exit)
                    <tr>
                        <td class="py-3 px-3 font-mono text-rose-400 truncate max-w-xs">{{ $exit->url }}</td>
                        <td class="py-3 px-3 text-center font-bold">{{ $exit->count }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="py-8 text-center text-slate-500">No exit points recorded.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Device, OS, Browser, Country tables -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Browsers -->
    <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl p-6 shadow-lg">
        <h5 class="font-bold text-slate-100 flex items-center mb-4 border-b border-slate-700/50 pb-2.5">
            <i class="fa-brands fa-chrome text-amber-500 mr-2"></i> Browsers
        </h5>
        <div class="space-y-3.5">
            @forelse($browsers as $browser)
            <div class="flex items-center justify-between text-xs">
                <span class="font-semibold text-slate-300">{{ $browser->browser ?: 'Unknown' }}</span>
                <span class="font-bold text-indigo-400">{{ $browser->count }}</span>
            </div>
            @empty
            <span class="text-xs text-slate-500">No data</span>
            @endforelse
        </div>
    </div>

    <!-- Operating Systems -->
    <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl p-6 shadow-lg">
        <h5 class="font-bold text-slate-100 flex items-center mb-4 border-b border-slate-700/50 pb-2.5">
            <i class="fa-solid fa-laptop text-blue-500 mr-2"></i> Operating Systems
        </h5>
        <div class="space-y-3.5">
            @forelse($operatingSystems as $os)
            <div class="flex items-center justify-between text-xs">
                <span class="font-semibold text-slate-300">{{ $os->os ?: 'Unknown' }}</span>
                <span class="font-bold text-indigo-400">{{ $os->count }}</span>
            </div>
            @empty
            <span class="text-xs text-slate-500">No data</span>
            @endforelse
        </div>
    </div>

    <!-- Device Types -->
    <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl p-6 shadow-lg">
        <h5 class="font-bold text-slate-100 flex items-center mb-4 border-b border-slate-700/50 pb-2.5">
            <i class="fa-solid fa-mobile-screen-button text-emerald-500 mr-2"></i> Devices
        </h5>
        <div class="space-y-3.5">
            @forelse($devices as $device)
            <div class="flex items-center justify-between text-xs">
                <span class="font-semibold text-slate-300 capitalize">{{ $device->device ?: 'Desktop' }}</span>
                <span class="font-bold text-indigo-400">{{ $device->count }}</span>
            </div>
            @empty
            <span class="text-xs text-slate-500">No data</span>
            @endforelse
        </div>
    </div>

    <!-- Traffic Sources -->
    <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl p-6 shadow-lg">
        <h5 class="font-bold text-slate-100 flex items-center mb-4 border-b border-slate-700/50 pb-2.5">
            <i class="fa-solid fa-arrows-split-up-and-left text-cyan-500 mr-2"></i> Traffic Sources
        </h5>
        <div class="space-y-3.5">
            @forelse($trafficSources as $source)
            <div class="flex items-center justify-between text-xs">
                <span class="font-semibold text-slate-300 capitalize">{{ $source->traffic_source ?: 'Direct' }}</span>
                <span class="font-bold text-indigo-400">{{ $source->count }}</span>
            </div>
            @empty
            <span class="text-xs text-slate-500">No data</span>
            @endforelse
        </div>
    </div>
</div>

<!-- Geographical Breakdown & Recent Logs -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    
    <!-- Countries -->
    <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl p-6 shadow-lg">
        <h4 class="font-bold text-slate-100 flex items-center mb-6 border-b border-slate-700/50 pb-3">
            <i class="fa-solid fa-earth-americas text-indigo-500 mr-2"></i> Countries
        </h4>
        <div class="space-y-4">
            @forelse($countries as $country)
            <div class="flex items-center justify-between text-xs">
                <span class="font-semibold text-slate-300 flex items-center">
                    <i class="fa-solid fa-location-dot text-indigo-500/50 mr-2"></i>
                    {{ $country->country ?: 'Unknown Location' }}
                </span>
                <span class="font-bold text-indigo-400">{{ $country->count }}</span>
            </div>
            @empty
            <div class="text-center py-6 text-slate-500 text-xs">No geographic logs found.</div>
            @endforelse
        </div>
    </div>

    <!-- Recent Logs -->
    <div class="lg:col-span-2 bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl p-6 shadow-lg">
        <h4 class="font-bold text-slate-100 flex items-center mb-6 border-b border-slate-700/50 pb-3">
            <i class="fa-solid fa-list-check text-indigo-500 mr-2"></i> Recent Traffic Hits Log
        </h4>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-slate-700/50 text-slate-500 font-bold uppercase tracking-wider">
                        <th class="py-2.5 px-3">IP Address</th>
                        <th class="py-2.5 px-3">Location</th>
                        <th class="py-2.5 px-3">Details</th>
                        <th class="py-2.5 px-3">Visited URL</th>
                        <th class="py-2.5 px-3 text-right">Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40 text-slate-300">
                    @forelse($recentVisits as $visit)
                    <tr class="hover:bg-slate-800/5 transition-colors">
                        <td class="py-3 px-3 font-mono text-slate-200">{{ $visit->ip_address }}</td>
                        <td class="py-3 px-3">
                            <span class="font-medium block text-slate-300">{{ $visit->country ?: 'Unknown' }}</span>
                            <span class="text-[10px] text-slate-500 block">{{ $visit->city ?: 'Unknown' }}</span>
                        </td>
                        <td class="py-3 px-3 text-slate-400">
                            <span class="block">{{ $visit->browser }} ({{ $visit->os }})</span>
                            <span class="text-[9px] text-slate-500 capitalize">{{ $visit->device }}</span>
                        </td>
                        <td class="py-3 px-3 font-mono text-indigo-400 truncate max-w-xs" title="{{ $visit->url }}">{{ $visit->url }}</td>
                        <td class="py-3 px-3 text-right font-medium text-slate-500 whitespace-nowrap">{{ $visit->created_at->diffForHumans() }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-500">No visits logged.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $recentVisits->links() }}
        </div>
    </div>
</div>

<script>
    // Build Chart.js line graph
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('trafficChart').getContext('2d');
        const rawData = {!! json_encode($chartData) !!};

        const labels = rawData.map(item => item.date);
        const uniqueVisits = rawData.map(item => item.unique_visits);
        const totalVisits = rawData.map(item => item.total_visits);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Unique Visitors',
                        data: uniqueVisits,
                        borderColor: '#6366f1', // Indigo accent
                        backgroundColor: 'rgba(99, 102, 241, 0.05)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#6366f1',
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Total Page Views',
                        data: totalVisits,
                        borderColor: '#06b6d4', // Cyan accent
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        fill: false,
                        tension: 0.35,
                        pointBackgroundColor: '#06b6d4',
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: '#94a3b8', // slate-400
                            font: {
                                family: 'Outfit',
                                size: 12
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.04)'
                        },
                        ticks: {
                            color: '#94a3b8',
                            font: { family: 'Outfit' }
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.04)'
                        },
                        ticks: {
                            color: '#94a3b8',
                            font: { family: 'Outfit' },
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
