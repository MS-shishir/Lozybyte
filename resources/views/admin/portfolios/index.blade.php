@extends('admin.layouts.app')

@section('page_title', 'Case Studies (Portfolio)')

@section('content')
<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8 text-slate-100">
    <div>
        <h3 class="text-xl font-extrabold tracking-tight flex items-center">
            <i class="fa-solid fa-folder-open text-indigo-500 mr-3"></i> Case Studies CMS
        </h3>
        <p class="text-xs text-slate-400 mt-1">Manage and customize your corporate client case studies, brand colors, and metrics.</p>
    </div>
    <a href="{{ route('admin.portfolios.create') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-slate-50 text-xs font-bold rounded-2xl transition-all shadow-[0_0_20px_rgba(99,102,241,0.3)] hover:scale-[1.02] flex items-center gap-1.5 self-start sm:self-center">
        <i class="fa-solid fa-plus text-xs"></i> Add Case Study
    </a>
</div>

<!-- Portfolios Grid / List -->
<div class="space-y-8 mb-12">
    @forelse($portfolios as $portfolio)
    @php
        $meta = $portfolio->meta ?? [];
        $brandColor = $meta['color'] ?? '#6366f1';
        $logoColor = $meta['logo_color'] ?? '#06b6d4';
        $logoText = $meta['logo_text'] ?? $portfolio->client;
        $logoIcon = $meta['logo_icon'] ?? 'TrendingUp';
        $duration = $meta['duration'] ?? '6 Weeks';
        $team = $meta['team'] ?? '4 Members';
        $launched = $meta['launched'] ?? 'Mar 2024';
        $tag = is_array($meta['tag'] ?? null) ? ($meta['tag']['en'] ?? $portfolio->industry) : ($meta['tag'] ?? $portfolio->industry);
    @endphp
    
    <!-- Case Study Card -->
    <div class="group relative bg-[#0d1527]/70 backdrop-blur-md border border-slate-800/60 rounded-3xl overflow-hidden shadow-xl hover:shadow-[0_0_35px_rgba(99,102,241,0.06)] hover:border-slate-700/50 transition-all duration-300">
        
        <!-- Brand Accent Strip -->
        <div class="absolute top-0 left-0 right-0 h-[3px]" style="background-color: {{ $brandColor }}"></div>
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 p-6">
            
            <!-- Column 1: Featured Image / Brand Showcase (4 Cols) -->
            <div class="lg:col-span-4 flex flex-col justify-between space-y-4">
                <div class="relative rounded-2xl overflow-hidden aspect-video lg:aspect-auto lg:h-48 border border-white/5 bg-slate-950">
                    @if($portfolio->image_path)
                    <img src="{{ preg_match('#^(https?://|/)#', $portfolio->image_path) ? $portfolio->image_path : asset('storage/' . $portfolio->image_path) }}" 
                        alt="{{ $portfolio->client }}" 
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                    <!-- Fallback Sleek Gradient Card -->
                    <div class="w-full h-full bg-gradient-to-br from-slate-900 to-indigo-950/40 flex flex-col items-center justify-center p-6 text-center">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-600/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 mb-2">
                            <i class="fa-solid fa-folder-open text-lg"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $portfolio->client }}</span>
                    </div>
                    @endif
                    
                    <!-- Industry Badge -->
                    <span class="absolute top-3 left-3 px-2.5 py-1 text-[10px] font-bold rounded-xl bg-slate-950/80 backdrop-blur-md text-slate-200 border border-white/10 uppercase tracking-wider">
                        {{ $portfolio->industry }}
                    </span>
                </div>

                <!-- Client Brand Info -->
                <div class="p-4 bg-slate-950/30 border border-slate-850 rounded-2xl flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center border font-bold text-xs" 
                        style="background-color: {{ $logoColor }}15; border-color: {{ $logoColor }}30; color: {{ $logoColor }}">
                        <i class="fa-solid fa-crown"></i>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest">Client Emblem</span>
                        <span class="text-xs font-black text-slate-200 uppercase tracking-wider" style="color: {{ $logoColor }}">{{ $logoText }}</span>
                    </div>
                </div>
            </div>

            <!-- Column 2: Core Details (5 Cols) -->
            <div class="lg:col-span-5 space-y-4">
                <div>
                    <h4 class="text-lg font-extrabold text-slate-100 leading-snug group-hover:text-indigo-400 transition-colors duration-200">{{ $portfolio->title }}</h4>
                    <p class="text-[11px] text-slate-400 mt-1 font-mono">Slug: <span class="text-slate-300">{{ $portfolio->slug }}</span></p>
                </div>

                <!-- Challenge, Solution, Result Tabs -->
                <div class="space-y-3 pt-3 border-t border-slate-800/60">
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-lg bg-rose-500/10 border border-rose-500/20 flex items-center justify-center text-rose-400 shrink-0 mt-0.5">
                            <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                        </div>
                        <div>
                            <span class="block text-[10px] font-extrabold text-rose-400 uppercase tracking-widest">Problem / Challenge</span>
                            <p class="text-xs text-slate-400 leading-relaxed mt-0.5">{{ Str::limit($portfolio->challenge, 140) }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-lg bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 shrink-0 mt-0.5">
                            <i class="fa-solid fa-screwdriver-wrench text-[10px]"></i>
                        </div>
                        <div>
                            <span class="block text-[10px] font-extrabold text-indigo-400 uppercase tracking-widest">Custom Solution</span>
                            <p class="text-xs text-slate-400 leading-relaxed mt-0.5">{{ Str::limit($portfolio->solution, 140) }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0 mt-0.5">
                            <i class="fa-solid fa-circle-check text-[10px]"></i>
                        </div>
                        <div>
                            <span class="block text-[10px] font-extrabold text-emerald-400 uppercase tracking-widest">Outcome / Result</span>
                            <p class="text-xs text-slate-400 leading-relaxed mt-0.5">{{ Str::limit($portfolio->result, 140) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Column 3: Metrics & Actions Sidebar (3 Cols) -->
            <div class="lg:col-span-3 flex flex-col justify-between border-t lg:border-t-0 lg:border-l border-slate-800/80 pt-6 lg:pt-0 lg:pl-6">
                <!-- Meta Info List -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Status</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 text-[9px] font-black uppercase rounded-full {{ $portfolio->status ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-slate-800 text-slate-500 border border-slate-700/50' }}">
                            {{ $portfolio->status ? 'Active' : 'Draft' }}
                        </span>
                    </div>

                    <div class="space-y-2 pt-2 border-t border-slate-800/60">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500 flex items-center gap-1.5"><i class="fa-regular fa-clock text-slate-600"></i> Duration</span>
                            <span class="font-bold text-slate-300">{{ $duration }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500 flex items-center gap-1.5"><i class="fa-regular fa-user text-slate-600"></i> Team</span>
                            <span class="font-bold text-slate-300">{{ $team }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500 flex items-center gap-1.5"><i class="fa-regular fa-paper-plane text-slate-600"></i> Launched</span>
                            <span class="font-bold text-slate-300">{{ $launched }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500 flex items-center gap-1.5"><i class="fa-solid fa-palette text-slate-600"></i> Accent</span>
                            <span class="inline-block w-4 h-4 rounded-full border border-white/10" style="background-color: {{ $brandColor }}"></span>
                        </div>
                    </div>
                </div>

                <!-- Card Actions -->
                <div class="flex items-center gap-2 pt-6 lg:pt-0">
                    <a href="{{ route('admin.portfolios.edit', $portfolio->id) }}" 
                        class="flex-1 text-center py-2 bg-indigo-600/10 hover:bg-indigo-600 text-indigo-400 hover:text-slate-50 text-[10px] font-extrabold uppercase tracking-wider rounded-xl border border-indigo-500/20 hover:border-indigo-600 transition-all">
                        <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                    </a>
                    <form action="{{ route('admin.portfolios.delete', $portfolio->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this case study permanently?')" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                            class="w-full py-2 bg-rose-600/10 hover:bg-rose-600 text-rose-400 hover:text-slate-50 text-[10px] font-extrabold uppercase tracking-wider rounded-xl border border-rose-500/20 hover:border-rose-650 transition-all">
                            <i class="fa-solid fa-trash-can mr-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
    @empty
    <!-- Empty State -->
    <div class="py-16 text-center bg-[#0d1527]/70 backdrop-blur-md border border-slate-800/60 rounded-3xl">
        <div class="w-16 h-16 rounded-full bg-slate-900 border border-slate-800/80 flex items-center justify-center text-slate-600 mx-auto mb-4">
            <i class="fa-solid fa-folder-open text-2xl"></i>
        </div>
        <h4 class="text-slate-200 font-bold text-base">No case studies found</h4>
        <p class="text-xs text-slate-500 mt-1 max-w-xs mx-auto">Create your first premium client case study and make it live on your digital business website.</p>
        <a href="{{ route('admin.portfolios.create') }}" class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-slate-50 text-xs font-bold rounded-xl transition-all shadow-[0_0_15px_rgba(99,102,241,0.2)]">
            <i class="fa-solid fa-plus"></i> Create Case Study
        </a>
    </div>
    @endforelse
</div>

<!-- Pagination (Optional but good) -->
<div class="mt-6">
    {{ $portfolios->links() }}
</div>
@endsection
