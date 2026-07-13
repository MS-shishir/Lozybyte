@extends('admin.layouts.app')

@section('page_title', 'Edit Case Study')

@section('content')
<div class="max-w-4xl mx-auto mb-12">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8 text-slate-100">
        <div>
            <h3 class="text-lg font-bold"><i class="fa-solid fa-folder-open text-indigo-500 mr-2"></i> Edit Case Study</h3>
            <p class="text-xs text-slate-400 mt-1">Modify the dynamic client case study information.</p>
        </div>
        <a href="{{ route('admin.portfolios.index') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl transition-all">
            <i class="fa-solid fa-arrow-left mr-1"></i> Back to List
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.portfolios.update', $portfolio->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <!-- Section 1: Basic Info -->
        <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl p-6 shadow-lg space-y-6">
            <h4 class="text-sm font-bold text-indigo-400 border-b border-slate-700/50 pb-3"><i class="fa-solid fa-circle-info mr-2"></i> Basic Information</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Client Name</label>
                    <input type="text" name="client" placeholder="e.g. Green Garden Restaurant" required value="{{ old('client', $portfolio->client) }}"
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Slug (Unique URL)</label>
                    <input type="text" name="slug" placeholder="e.g. green-garden-ordering" required value="{{ old('slug', $portfolio->slug) }}"
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Title (English)</label>
                    <input type="text" name="title_en" required value="{{ old('title_en', $portfolio->getTranslation('title', 'en')) }}"
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Title (Bangla)</label>
                    <input type="text" name="title_bn" required value="{{ old('title_bn', $portfolio->getTranslation('title', 'bn')) }}"
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Industry (English)</label>
                    <input type="text" name="industry_en" placeholder="e.g. Food & Beverage" required value="{{ old('industry_en', $portfolio->getTranslation('industry', 'en')) }}"
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Industry (Bangla)</label>
                    <input type="text" name="industry_bn" placeholder="e.g. খাদ্য ও পানীয়" required value="{{ old('industry_bn', $portfolio->getTranslation('industry', 'bn')) }}"
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Featured Banner Image (Optional)</label>
                    <input type="file" name="image" class="block w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600/10 file:text-indigo-400 hover:file:bg-indigo-600/20">
                </div>
                <div class="flex items-center pt-4 md:pt-0">
                    <input type="checkbox" name="status" id="port-status" class="w-5 h-5 text-indigo-600 border-slate-700/50 rounded bg-slate-950" {{ $portfolio->status ? 'checked' : '' }}>
                    <label for="port-status" class="ml-2.5 text-sm font-semibold text-slate-300">Set Active (Visible on site)</label>
                </div>
            </div>

            @if($portfolio->image_path)
            <div class="mt-4 p-4 bg-slate-950/30 border border-slate-800 rounded-2xl flex items-center gap-4">
                <span class="text-xs font-semibold text-slate-400">Current Banner:</span>
                <img src="{{ preg_match('#^(https?://|/)#', $portfolio->image_path) ? $portfolio->image_path : asset('storage/' . $portfolio->image_path) }}" class="h-14 rounded-lg border border-slate-700 object-cover">
            </div>
            @endif
        </div>

        <!-- Section 2: Branding & Styling -->
        @php
            $meta = $portfolio->meta ?? [];
            $color = $meta['color'] ?? '#f43f5e';
            $logoColor = $meta['logo_color'] ?? '#10b981';
            $logoText = $meta['logo_text'] ?? '';
            $logoIcon = $meta['logo_icon'] ?? 'Award';
            $duration = $meta['duration'] ?? '';
            $team = $meta['team'] ?? '';
            $launched = $meta['launched'] ?? '';
            $tagEn = $meta['tag']['en'] ?? '';
            $tagBn = $meta['tag']['bn'] ?? '';
            $statsEn = $meta['stats']['en'] ?? ['', '', ''];
            $statsBn = $meta['stats']['bn'] ?? ['', '', ''];
            $tech = $meta['tech'] ?? ['', '', '', '', '', ''];
            $metrics = $meta['metrics'] ?? [];
        @endphp

        <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl p-6 shadow-lg space-y-6">
            <h4 class="text-sm font-bold text-indigo-400 border-b border-slate-700/50 pb-3"><i class="fa-solid fa-palette mr-2"></i> Branding & Layout Style</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Primary Brand Color (Hex)</label>
                    <div class="flex gap-3">
                        <input type="color" name="color" value="{{ $color }}" class="h-10 w-12 rounded-xl bg-transparent border border-slate-700/50 cursor-pointer">
                        <input type="text" id="color_hex" value="{{ $color }}" placeholder="#f43f5e" class="block flex-1 px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none" oninput="document.getElementsByName('color')[0].value = this.value">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Logo/Emblem Color (Hex)</label>
                    <div class="flex gap-3">
                        <input type="color" name="logo_color" value="{{ $logoColor }}" class="h-10 w-12 rounded-xl bg-transparent border border-slate-700/50 cursor-pointer">
                        <input type="text" id="logo_color_hex" value="{{ $logoColor }}" placeholder="#10b981" class="block flex-1 px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none" oninput="document.getElementsByName('logo_color')[0].value = this.value">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Logo Text (Emblem Text)</label>
                    <input type="text" name="logo_text" placeholder="e.g. GREEN GARDEN" value="{{ old('logo_text', $logoText) }}"
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Logo Emblem Icon</label>
                    <select name="logo_icon" class="block w-full px-4 py-2.5 bg-slate-950 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                        <option value="Utensils" {{ $logoIcon == 'Utensils' ? 'selected' : '' }}>Utensils (Restaurant/Food)</option>
                        <option value="ShoppingCart" {{ $logoIcon == 'ShoppingCart' ? 'selected' : '' }}>ShoppingCart (E-Commerce)</option>
                        <option value="Cloud" {{ $logoIcon == 'Cloud' ? 'selected' : '' }}>Cloud (SaaS/IT)</option>
                        <option value="Heart" {{ $logoIcon == 'Heart' ? 'selected' : '' }}>Heart (Healthcare/Medical)</option>
                        <option value="GraduationCap" {{ $logoIcon == 'GraduationCap' ? 'selected' : '' }}>GraduationCap (Education)</option>
                        <option value="TrendingUp" {{ $logoIcon == 'TrendingUp' ? 'selected' : '' }}>TrendingUp (Corporate/Finance)</option>
                        <option value="Send" {{ $logoIcon == 'Send' ? 'selected' : '' }}>Send (Marketing)</option>
                        <option value="Database" {{ $logoIcon == 'Database' ? 'selected' : '' }}>Database (Tech)</option>
                        <option value="Award" {{ $logoIcon == 'Award' ? 'selected' : '' }}>Award (Achievement)</option>
                        <option value="Users" {{ $logoIcon == 'Users' ? 'selected' : '' }}>Users (Community)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Project Tag Line (English)</label>
                    <input type="text" name="tag_en" placeholder="e.g. Website + Online Ordering System" value="{{ old('tag_en', $tagEn) }}"
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Project Tag Line (Bangla)</label>
                    <input type="text" name="tag_bn" placeholder="e.g. ওয়েবসাইট + অনলাইন অর্ডারিং সিস্টেম" value="{{ old('tag_bn', $tagBn) }}"
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Duration</label>
                    <input type="text" name="duration" placeholder="e.g. 6 Weeks" value="{{ old('duration', $duration) }}"
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Team Size</label>
                    <input type="text" name="team" placeholder="e.g. 4 Members" value="{{ old('team', $team) }}"
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Launched Date</label>
                    <input type="text" name="launched" placeholder="e.g. Mar 2024" value="{{ old('launched', $launched) }}"
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                </div>
            </div>
        </div>

        <!-- Section 3: The Challenge (Problem) -->
        <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl p-6 shadow-lg space-y-6">
            <h4 class="text-sm font-bold text-indigo-400 border-b border-slate-700/50 pb-3"><i class="fa-solid fa-triangle-exclamation mr-2"></i> The Challenge (Problem)</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Problem Description (EN)</label>
                    <textarea name="challenge_en" rows="3" required class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-xs outline-none">{{ $portfolio->getTranslation('challenge', 'en') }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Problem Description (BN)</label>
                    <textarea name="challenge_bn" rows="3" required class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-xs outline-none">{{ $portfolio->getTranslation('challenge', 'bn') }}</textarea>
                </div>
            </div>

            <!-- Stats Bullet Points -->
            <div class="space-y-4 pt-4 border-t border-slate-700/50">
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Problem Statistics (Up to 3 bullet points)</span>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <label class="text-[10px] text-indigo-400 uppercase font-bold">English Bullets</label>
                        <input type="text" name="stats_en[]" value="{{ $statsEn[0] ?? '' }}" placeholder="Bullet 1" class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-xs outline-none">
                        <input type="text" name="stats_en[]" value="{{ $statsEn[1] ?? '' }}" placeholder="Bullet 2" class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-xs outline-none">
                        <input type="text" name="stats_en[]" value="{{ $statsEn[2] ?? '' }}" placeholder="Bullet 3" class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-xs outline-none">
                    </div>
                    <div class="space-y-3">
                        <label class="text-[10px] text-indigo-400 uppercase font-bold">Bangla Bullets</label>
                        <input type="text" name="stats_bn[]" value="{{ $statsBn[0] ?? '' }}" placeholder="বুলেট ১" class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-xs outline-none">
                        <input type="text" name="stats_bn[]" value="{{ $statsBn[1] ?? '' }}" placeholder="বুলেট ২" class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-xs outline-none">
                        <input type="text" name="stats_bn[]" value="{{ $statsBn[2] ?? '' }}" placeholder="বুলেট ৩" class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-xs outline-none">
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 4: The Solution -->
        <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl p-6 shadow-lg space-y-6">
            <h4 class="text-sm font-bold text-indigo-400 border-b border-slate-700/50 pb-3"><i class="fa-solid fa-screwdriver-wrench mr-2"></i> The Custom Solution</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Solution Description (EN)</label>
                    <textarea name="solution_en" rows="3" required class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-xs outline-none">{{ $portfolio->getTranslation('solution', 'en') }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Solution Description (BN)</label>
                    <textarea name="solution_bn" rows="3" required class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-xs outline-none">{{ $portfolio->getTranslation('solution', 'bn') }}</textarea>
                </div>
            </div>

            <!-- Tech Badges -->
            <div class="space-y-4 pt-4 border-t border-slate-700/50">
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Technologies Used (Add up to 6 technology badges)</span>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @for($i=0; $i<6; $i++)
                    <input type="text" name="tech[]" value="{{ $tech[$i] ?? '' }}" placeholder="Tech {{ $i+1 }}" class="block w-full px-4 py-2 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-xs outline-none">
                    @endfor
                </div>
            </div>
        </div>

        <!-- Section 5: The Result -->
        <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl p-6 shadow-lg space-y-6">
            <h4 class="text-sm font-bold text-indigo-400 border-b border-slate-700/50 pb-3"><i class="fa-solid fa-circle-check mr-2"></i> The Measurable Result</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Result Description (EN)</label>
                    <textarea name="result_en" rows="3" required class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-xs outline-none">{{ $portfolio->getTranslation('result', 'en') }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Result Description (BN)</label>
                    <textarea name="result_bn" rows="3" required class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-xs outline-none">{{ $portfolio->getTranslation('result', 'bn') }}</textarea>
                </div>
            </div>

            <!-- Metrics -->
            <div class="space-y-4 pt-4 border-t border-slate-700/50">
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Project Outcomes / Metrics (Up to 3 metrics)</span>
                
                <div class="grid grid-cols-1 gap-6">
                    @for($m=0; $m<3; $m++)
                    @php
                        $metric = $metrics[$m] ?? [];
                        $mLabel = $metric['label'] ?? '';
                        $mValue = $metric['value'] ?? '';
                        $mIcon = $metric['icon'] ?? 'ShoppingCart';
                    @endphp
                    <!-- Metric {{ $m+1 }} -->
                    <div class="p-4 bg-slate-950/50 border border-slate-700/30 rounded-2xl space-y-4">
                        <span class="text-[10px] text-indigo-400 uppercase font-bold">Metric {{ $m+1 }}</span>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="text-[10px] text-slate-400 block mb-1">Label</label>
                                <input type="text" name="metrics[{{ $m }}][label]" value="{{ $mLabel }}" placeholder="e.g. Orders/Revenue" class="block w-full px-3 py-1.5 bg-slate-900 border border-slate-700/50 rounded-xl text-slate-200 text-xs outline-none">
                            </div>
                            <div>
                                <label class="text-[10px] text-slate-400 block mb-1">Value</label>
                                <input type="text" name="metrics[{{ $m }}][value]" value="{{ $mValue }}" placeholder="e.g. +340%" class="block w-full px-3 py-1.5 bg-slate-900 border border-slate-700/50 rounded-xl text-slate-200 text-xs outline-none">
                            </div>
                            <div>
                                <label class="text-[10px] text-slate-400 block mb-1">Icon</label>
                                <select name="metrics[{{ $m }}][icon]" class="block w-full px-3 py-1.5 bg-slate-900 border border-slate-700/50 rounded-xl text-slate-200 text-xs outline-none">
                                    <option value="ShoppingCart" {{ $mIcon == 'ShoppingCart' ? 'selected' : '' }}>ShoppingCart (Sales)</option>
                                    <option value="Database" {{ $mIcon == 'Database' ? 'selected' : '' }}>Database (Revenue/Data)</option>
                                    <option value="Calendar" {{ $mIcon == 'Calendar' ? 'selected' : '' }}>Calendar (Bookings/Schedule)</option>
                                    <option value="Award" {{ $mIcon == 'Award' ? 'selected' : '' }}>Award (Quality/Rating)</option>
                                    <option value="Users" {{ $mIcon == 'Users' ? 'selected' : '' }}>Users (Engagement)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Submit Panel -->
        <div class="pt-4 border-t border-slate-800 flex items-center justify-end gap-3">
            <a href="{{ route('admin.portfolios.index') }}" class="px-4 py-2 border border-slate-750 text-slate-400 hover:text-slate-200 hover:bg-slate-800/40 rounded-xl text-xs font-semibold transition-all">Cancel</a>
            <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-slate-50 text-xs font-bold rounded-xl shadow-[0_0_15px_rgba(99,102,241,0.2)] transition-all">Update Case Study</button>
        </div>
    </form>
</div>
@endsection
