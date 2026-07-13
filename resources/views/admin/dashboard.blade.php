@extends('admin.layouts.app')

@section('page_title', 'Dashboard Overview')

@section('content')
<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mb-8">
    
    <!-- Stat 1: New Leads -->
    <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-2xl p-6 relative overflow-hidden group shadow-lg">
        <div class="absolute -right-3 -bottom-3 text-emerald-500/10 text-8xl group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-envelope-open-text"></i>
        </div>
        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-2">New Leads</span>
        <h3 class="text-3xl font-bold text-slate-50 mb-1">{{ $stats['new_leads'] }}</h3>
        <p class="text-xs text-emerald-400 flex items-center">
            <i class="fa-solid fa-circle text-[6px] mr-1.5 animate-pulse"></i> Needs Attention
        </p>
    </div>

    <!-- Stat 2: Total Leads -->
    <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-2xl p-6 relative overflow-hidden group shadow-lg">
        <div class="absolute -right-3 -bottom-3 text-slate-500/10 text-8xl group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-inbox"></i>
        </div>
        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-2">Total Leads</span>
        <h3 class="text-3xl font-bold text-slate-50 mb-1">{{ $stats['total_leads'] }}</h3>
        <p class="text-xs text-slate-400">Captured in inbox</p>
    </div>

    <!-- Stat 3: SaaS Products -->
    <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-2xl p-6 relative overflow-hidden group shadow-lg">
        <div class="absolute -right-3 -bottom-3 text-indigo-500/10 text-8xl group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-box-open"></i>
        </div>
        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-2">SaaS Products</span>
        <h3 class="text-3xl font-bold text-slate-50 mb-1">{{ $stats['total_products'] }}</h3>
        <p class="text-xs text-indigo-400">Listed in Marketplace</p>
    </div>

    <!-- Stat 4: Case Studies -->
    <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-2xl p-6 relative overflow-hidden group shadow-lg">
        <div class="absolute -right-3 -bottom-3 text-cyan-500/10 text-8xl group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-folder-open"></i>
        </div>
        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-2">Case Studies</span>
        <h3 class="text-3xl font-bold text-slate-50 mb-1">{{ $stats['total_portfolios'] }}</h3>
        <p class="text-xs text-cyan-400">Portfolio entries</p>
    </div>

    <!-- Stat 5: Blog Posts -->
    <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-2xl p-6 relative overflow-hidden group shadow-lg">
        <div class="absolute -right-3 -bottom-3 text-amber-500/10 text-8xl group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-blog"></i>
        </div>
        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-2">Blog Posts</span>
        <h3 class="text-3xl font-bold text-slate-50 mb-1">{{ $stats['total_posts'] }}</h3>
        <p class="text-xs text-amber-400">SEO & Marketing articles</p>
    </div>

    <!-- Stat 6: Today's Visitors -->
    <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-2xl p-6 relative overflow-hidden group shadow-lg">
        <div class="absolute -right-3 -bottom-3 text-indigo-500/10 text-8xl group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-users"></i>
        </div>
        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-2">Today's Visitors</span>
        <h3 class="text-3xl font-bold text-indigo-400 mb-1">{{ $stats['today_visitors'] }}</h3>
        <p class="text-xs text-indigo-350 hover:underline">
            <a href="{{ route('admin.analytics.index') }}" class="flex items-center gap-1.5">
                Full Analytics <i class="fa-solid fa-chart-line text-[10px]"></i>
            </a>
        </p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Left Column: Recent Leads -->
    <div class="lg:col-span-2 bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-2xl p-6 shadow-lg">
        <div class="flex items-center justify-between mb-6">
            <h4 class="font-bold text-slate-100 flex items-center">
                <i class="fa-solid fa-envelope-open-text text-indigo-500 mr-2"></i>
                Recent Client Enquiries
            </h4>
            <a href="{{ route('admin.leads.index') }}" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300">
                View All <i class="fa-solid fa-angle-right ml-1"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead>
                    <tr class="border-b border-slate-700/50 text-slate-400 text-xs font-semibold uppercase tracking-wider">
                        <th class="py-3 px-4">Visitor</th>
                        <th class="py-3 px-4">Contact</th>
                        <th class="py-3 px-4">Source</th>
                        <th class="py-3 px-4">Target / Product</th>
                        <th class="py-3 px-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 text-slate-300">
                    @forelse($recentLeads as $lead)
                    <tr>
                        <td class="py-4 px-4 font-semibold text-slate-200">{{ $lead->name }}</td>
                        <td class="py-4 px-4">
                            <span class="block text-slate-400 text-xs">{{ $lead->email }}</span>
                            <span class="block text-slate-400 text-xs">{{ $lead->phone ?? 'N/A' }}</span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="inline-block px-2 py-0.5 text-xs font-medium rounded-full bg-slate-800 text-slate-300 capitalize">
                                {{ str_replace('_', ' ', $lead->source) }}
                            </span>
                        </td>
                        <td class="py-4 px-4 font-medium text-indigo-400">{{ $lead->service ?? 'General Enquiry' }}</td>
                        <td class="py-4 px-4">
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full
                                @if($lead->status === 'new') bg-emerald-500/10 text-emerald-400
                                @elseif($lead->status === 'contacted') bg-amber-500/10 text-amber-400
                                @elseif($lead->status === 'converted') bg-indigo-500/10 text-indigo-400
                                @else bg-slate-800 text-slate-400 @endif capitalize">
                                {{ $lead->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 px-4 text-center text-slate-500">
                            <i class="fa-solid fa-inbox text-3xl block mb-2"></i>
                            No lead captures found in the database.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right Column: Quick Commands -->
    <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-2xl p-6 shadow-lg">
        <h4 class="font-bold text-slate-100 flex items-center mb-6">
            <i class="fa-solid fa-bolt text-indigo-500 mr-2"></i>
            Quick CMS Actions
        </h4>
        
        <div class="space-y-4">
            <!-- Action 1: Create Product -->
            @if(in_array(auth()->user()->role, ['super_admin', 'saas_manager']))
            <a href="{{ route('admin.products.create') }}" class="group flex items-center p-4 border border-slate-700/50 hover:border-indigo-500 bg-slate-950/50/50 hover:bg-[#141C2F]/80 backdrop-blur-md rounded-2xl transition-all">
                <div class="w-10 h-10 rounded-xl bg-indigo-600/10 text-indigo-400 group-hover:bg-indigo-600 group-hover:text-slate-50 flex items-center justify-center text-lg transition-all shrink-0">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <div class="ml-4 overflow-hidden">
                    <h5 class="text-sm font-semibold text-slate-200 group-hover:text-slate-100">Add SaaS Product</h5>
                    <p class="text-xs text-slate-400 truncate">List new software on marketplace</p>
                </div>
            </a>
            @endif

            <!-- Action 2: Settings -->
            @if(in_array(auth()->user()->role, ['super_admin', 'content_manager']))
            <a href="{{ route('admin.settings.edit') }}" class="group flex items-center p-4 border border-slate-700/50 hover:border-indigo-500 bg-slate-950/50/50 hover:bg-[#141C2F]/80 backdrop-blur-md rounded-2xl transition-all">
                <div class="w-10 h-10 rounded-xl bg-cyan-600/10 text-cyan-400 group-hover:bg-cyan-600 group-hover:text-slate-50 flex items-center justify-center text-lg transition-all shrink-0">
                    <i class="fa-solid fa-palette"></i>
                </div>
                <div class="ml-4 overflow-hidden">
                    <h5 class="text-sm font-semibold text-slate-200 group-hover:text-slate-100">Theme & Settings</h5>
                    <p class="text-xs text-slate-400 truncate">Alter dynamic primary/secondary colors</p>
                </div>
            </a>

            <!-- Action 3: Write Blog -->
            <a href="{{ route('admin.blog.create') }}" class="group flex items-center p-4 border border-slate-700/50 hover:border-indigo-500 bg-slate-950/50/50 hover:bg-[#141C2F]/80 backdrop-blur-md rounded-2xl transition-all">
                <div class="w-10 h-10 rounded-xl bg-amber-600/10 text-amber-400 group-hover:bg-amber-600 group-hover:text-slate-50 flex items-center justify-center text-lg transition-all shrink-0">
                    <i class="fa-solid fa-pen-nib"></i>
                </div>
                <div class="ml-4 overflow-hidden">
                    <h5 class="text-sm font-semibold text-slate-200 group-hover:text-slate-100">Publish Blog Post</h5>
                    <p class="text-xs text-slate-400 truncate">Create a new article for google SEO ranking</p>
                </div>
            </a>
            @endif
        </div>
    </div>
</div>

<!-- Security Edit Logs / Audit Trails -->
<div class="mt-8 bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-2xl p-6 shadow-lg">
    <div class="flex items-center justify-between mb-6">
        <h4 class="font-bold text-slate-100 flex items-center">
            <i class="fa-solid fa-shield-halved text-emerald-500 mr-2"></i>
            Live Editor — Security Audit Logs
        </h4>
        <span class="text-xs px-2.5 py-1 rounded-lg bg-emerald-500/10 text-emerald-400 font-bold uppercase tracking-wider">
            Active Guard
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse">
            <thead>
                <tr class="border-b border-slate-700/50 text-slate-400 text-xs font-semibold uppercase tracking-wider">
                    <th class="py-3 px-4">Timestamp</th>
                    <th class="py-3 px-4">Administrator</th>
                    <th class="py-3 px-4">Modified Component</th>
                    <th class="py-3 px-4">Action</th>
                    <th class="py-3 px-4">Network Info</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800 text-slate-300">
                @forelse($recentLogs as $log)
                <tr class="hover:bg-slate-800/20 transition-colors">
                    <td class="py-3.5 px-4 text-xs font-mono text-slate-400">
                        {{ $log->created_at->setTimezone('Asia/Dhaka')->format('Y-m-d h:i:s A') }}
                    </td>
                    <td class="py-3.5 px-4 font-semibold text-slate-200">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-indigo-600/30 border border-indigo-500/30 flex items-center justify-center text-[10px] text-indigo-300 font-bold uppercase">
                                {{ substr($log->user_name, 0, 1) }}
                            </div>
                            <div>
                                <span class="block text-xs text-slate-200">{{ $log->user_name }}</span>
                                <span class="block text-[9px] text-slate-500 font-bold uppercase tracking-wide">ID: #{{ $log->user_id ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="py-3.5 px-4 text-xs">
                        <span class="font-mono bg-slate-900 border border-slate-800 px-2 py-1 rounded text-indigo-300">
                            {{ $log->element_key }}
                        </span>
                    </td>
                    <td class="py-3.5 px-4 text-xs">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                            {{ $log->action }}
                        </span>
                    </td>
                    <td class="py-3.5 px-4">
                        <div class="text-[10px] font-mono text-slate-400">
                            <span class="block text-slate-300">IP: {{ $log->ip_address }}</span>
                            <span class="block text-[9px] text-slate-500 truncate max-w-[200px]" title="{{ $log->user_agent }}">
                                {{ $log->user_agent }}
                            </span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 px-4 text-center text-slate-500">
                        <i class="fa-solid fa-shield-halved text-3xl block mb-2 text-slate-600"></i>
                        No edit actions have been logged yet. Changes made in the editor will appear here automatically.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
