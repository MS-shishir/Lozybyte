@extends('admin.layouts.app')

@section('page_title', 'Manage Testimonials')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8 text-slate-100">
    <div>
        <h1 class="text-2xl font-bold tracking-tight flex items-center">
            <i class="fa-solid fa-comment-dots text-indigo-500 mr-3"></i> Client Reviews
        </h1>
        <p class="text-xs text-slate-400 mt-1">Manage client testimonials, feedback quotes, star ratings, and video reviews.</p>
    </div>
    <button onclick="toggleModal('modal-add-testimonial')" class="flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-xl transition-all duration-300 shadow-[0_0_20px_rgba(99,102,241,0.3)] hover:shadow-[0_0_25px_rgba(99,102,241,0.5)] transform hover:-translate-y-0.5 shrink-0">
        <i class="fa-solid fa-plus"></i> Add Testimonial
    </button>
</div>

<!-- Search & Filter Controls -->
<div class="relative z-20 bg-[#141C2F]/50 backdrop-blur-md border border-white/[0.06] rounded-3xl p-5 mb-8 flex flex-col md:flex-row gap-4 items-center justify-between shadow-xl">
    <form action="{{ route('admin.testimonials.index') }}" method="GET" class="w-full flex flex-col sm:flex-row gap-4 items-stretch sm:items-center">
        <!-- Search Input -->
        <div class="relative flex-grow">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search client name, company or review content..." 
                class="w-full pl-11 pr-4 py-2.5 bg-slate-950/60 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none placeholder:text-slate-500 transition-colors">
        </div>
        
        <!-- Status Filter -->
        <div class="relative shrink-0 min-w-[160px]">
            <select name="status" class="w-full px-4 py-2.5 bg-slate-950/60 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none appearance-none cursor-pointer transition-colors">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft Only</option>
            </select>
            <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
        </div>

        <!-- Submit and Reset Buttons -->
        <div class="flex items-center gap-2 shrink-0">
            <button type="submit" class="px-5 py-2.5 bg-indigo-600/80 hover:bg-indigo-600 text-white text-sm font-semibold rounded-2xl transition-colors">
                Filter
            </button>
            @if(request()->filled('search') || request()->filled('status'))
            <a href="{{ route('admin.testimonials.index') }}" class="px-5 py-2.5 bg-rose-600/10 text-rose-400 hover:bg-rose-600 hover:text-white text-sm font-semibold rounded-2xl transition-colors">
                Reset
            </a>
            @endif
        </div>
    </form>
</div>

<!-- Testimonials Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    @forelse($testimonials as $testimonial)
    <div class="bg-gradient-to-br from-[#141C2F]/90 to-[#141C2F]/50 backdrop-blur-md border border-white/[0.06] hover:border-indigo-500/30 rounded-3xl p-6 flex flex-col justify-between shadow-xl transition-all duration-300 hover:shadow-2xl hover:shadow-indigo-500/5 hover:-translate-y-1 group relative overflow-hidden">
        <!-- Hover Subtle Background Glow -->
        <div class="absolute -right-16 -top-16 w-36 h-36 bg-indigo-500/10 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>

        <div>
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                    @if($testimonial->image_path)
                    <img src="{{ $testimonial->image_path }}" alt="{{ $testimonial->name }}" class="w-12 h-12 rounded-full object-cover border border-white/[0.08] ring-2 ring-indigo-500/10 group-hover:ring-indigo-500/40 transition-all duration-300 shrink-0">
                    @else
                    <div class="w-12 h-12 bg-gradient-to-tr from-indigo-600 to-purple-600 text-white rounded-full flex items-center justify-center font-bold text-base uppercase shrink-0 border border-white/[0.08]">
                        {{ substr($testimonial->name, 0, 2) }}
                    </div>
                    @endif
                    <div>
                        <h4 class="font-bold text-slate-50 group-hover:text-indigo-400 transition-colors">{{ $testimonial->name }}</h4>
                        <p class="text-xs text-slate-400 mt-0.5 font-medium">{{ $testimonial->designation }} <span class="text-slate-600 mx-1">•</span> <span class="text-slate-400">{{ $testimonial->company }}</span></p>
                    </div>
                </div>
                
                <!-- Rating Badge -->
                <div class="flex items-center gap-1 bg-amber-500/10 border border-amber-500/20 px-2.5 py-1 rounded-full text-amber-400 text-[10px] font-bold shadow-sm shrink-0">
                    <i class="fa-solid fa-star text-[9px]"></i>
                    <span>{{ $testimonial->rating }}.0</span>
                </div>
            </div>
            
            <!-- Quote Section with styling -->
            <div class="relative mt-6 bg-slate-950/40 border border-white/[0.03] rounded-2xl p-4 min-h-[96px] flex flex-col justify-center">
                <i class="fa-solid fa-quote-left absolute top-3 left-3 text-2xl text-indigo-500/[0.06] pointer-events-none"></i>
                <p class="text-xs text-slate-300 italic leading-relaxed relative z-10 pl-2">
                    "{{ $testimonial->review }}"
                </p>
            </div>
        </div>

        <!-- Card Actions Footer -->
        <div class="flex items-center justify-between mt-6 pt-4 border-t border-slate-700/40">
            <div class="flex items-center gap-2">
                <!-- Status Badge -->
                <span class="inline-block px-3 py-1 text-[10px] font-semibold rounded-full {{ $testimonial->status ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 shadow-sm shadow-emerald-500/5' : 'bg-slate-800/80 text-slate-400 border border-slate-700/50' }}">
                    {{ $testimonial->status ? 'Active' : 'Draft' }}
                </span>
                
                <!-- Video Badge (only shows if has video/url) -->
                @if($testimonial->video_url || $testimonial->video_path)
                <span class="inline-flex items-center gap-1 px-3 py-1 text-[10px] font-semibold rounded-full bg-rose-500/10 text-rose-400 border border-rose-500/20" title="Has Video Review">
                    <i class="fa-solid fa-circle-play text-[9px]"></i> Video
                </span>
                @endif
            </div>
            
            <!-- Action Buttons -->
            <div class="flex items-center gap-1">
                <button type="button" onclick="toggleModal('modal-edit-{{ $testimonial->id }}')" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-indigo-400 hover:text-indigo-300 hover:bg-indigo-500/10 text-xs font-semibold transition-all">
                    <i class="fa-solid fa-pen-to-square"></i> Edit
                </button>
                <form action="{{ route('admin.testimonials.delete', $testimonial->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-rose-400 hover:text-rose-300 hover:bg-rose-500/10 text-xs font-semibold transition-all">
                        <i class="fa-solid fa-trash-can"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-2 py-16 text-center bg-slate-900/30 border border-white/[0.06] rounded-3xl shadow-xl flex flex-col items-center justify-center p-6 w-full">
        <div class="w-16 h-16 rounded-full bg-slate-800/50 flex items-center justify-center text-slate-400 mb-4 border border-white/[0.04]">
            <i class="fa-solid fa-comment-dots text-2xl"></i>
        </div>
        <h3 class="text-base font-semibold text-slate-200">No testimonials found</h3>
        <p class="text-xs text-slate-400 max-w-sm mt-1 mb-6">There are no client testimonials matching your search or filters. Click "Add Testimonial" to write one.</p>
        <button onclick="toggleModal('modal-add-testimonial')" class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold rounded-xl transition-all duration-300">
            <i class="fa-solid fa-plus"></i> Add Testimonial
        </button>
    </div>
    @endforelse
</div>

<!-- Pagination Links -->
@if($testimonials->hasPages())
<div class="mt-8 bg-[#141C2F]/30 border border-white/[0.05] rounded-2xl p-4 flex items-center justify-center shadow-lg">
    {{ $testimonials->appends(request()->query())->links() }}
</div>
@endif


<!-- Edit Modals -->
@foreach($testimonials as $t)
<div id="modal-edit-{{ $t->id }}" class="fixed inset-0 bg-slate-950/50/80 backdrop-blur-sm z-50 flex items-center justify-center p-6 hidden">
    <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="h-16 flex items-center justify-between px-6 border-b border-slate-700/50">
            <h4 class="font-bold text-slate-50 flex items-center">
                <i class="fa-solid fa-pen-to-square text-indigo-500 mr-2"></i> Edit Client Review
            </h4>
            <button onclick="toggleModal('modal-edit-{{ $t->id }}')" class="text-slate-400 hover:text-slate-200 text-lg"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form action="{{ route('admin.testimonials.update', $t->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6" onsubmit="handleFormSubmit(this)">
            @csrf
            @method('PUT')

            <!-- Name & Company & Rating & Status -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Client Name</label>
                    <input type="text" name="name" required value="{{ old('name', $t->name) }}"
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Company</label>
                    <input type="text" name="company" required value="{{ old('company', $t->company) }}"
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Rating (1 to 5)</label>
                    <select name="rating" required class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                        @foreach([5,4,3,2,1] as $r)
                        <option value="{{ $r }}" @if($t->rating == $r) selected @endif>{{ $r }} Stars</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center pt-8">
                    <input type="checkbox" name="status" id="test-status-{{ $t->id }}" class="w-5 h-5 text-indigo-600 border-slate-700/50 rounded" @if($t->status) checked @endif>
                    <label for="test-status-{{ $t->id }}" class="ml-2.5 text-sm font-semibold text-slate-300">Set Active</label>
                </div>
            </div>

            <!-- Profile Image Upload -->
            <div class="grid grid-cols-1 gap-6 pt-4 border-t border-slate-700/50/60">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Profile Image (Avatar)</label>
                    <input type="file" name="image" accept="image/*"
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none file:mr-4 file:py-1.5 file:px-4 file:rounded-xl file:border-0 file:bg-indigo-600/20 file:text-indigo-300 file:text-xs file:font-semibold hover:file:bg-indigo-600/30">
                    @if($t->image_path)
                    <div class="flex items-center gap-3 mt-3">
                        <img src="{{ $t->image_path }}" alt="Current avatar" class="w-16 h-16 rounded-xl object-cover border border-white/10">
                        <span class="text-[10px] text-slate-500">Current avatar — upload a new image to replace it.</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Designation EN, BN -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-700/50/60">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Designation (English)</label>
                    <input type="text" name="designation_en" required value="{{ old('designation_en', $t->getTranslation('designation', 'en')) }}"
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Designation (Bangla)</label>
                    <input type="text" name="designation_bn" required value="{{ old('designation_bn', $t->getTranslation('designation', 'bn')) }}"
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                </div>
            </div>

            <!-- Review EN, BN -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-700/50/60">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Review quote (EN)</label>
                    <textarea name="review_en" rows="4" required class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-xs outline-none">{{ old('review_en', $t->getTranslation('review', 'en')) }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Review quote (BN)</label>
                    <textarea name="review_bn" rows="4" required class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-xs outline-none">{{ old('review_bn', $t->getTranslation('review', 'bn')) }}</textarea>
                </div>
            </div>

            <!-- Video Review Upload -->
            <div class="grid grid-cols-1 gap-6 pt-4 border-t border-slate-700/50/60">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Video Review (Upload MP4 / WebM)</label>
                    <input type="file" name="video" accept="video/*"
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none file:mr-4 file:py-1.5 file:px-4 file:rounded-xl file:border-0 file:bg-indigo-600/20 file:text-indigo-300 file:text-xs file:font-semibold hover:file:bg-indigo-600/30">
                    @if($t->video_path)
                    <div class="flex items-center gap-3 mt-3">
                        <video src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($t->video_path) }}" class="w-40 rounded-xl border border-white/10" controls></video>
                        <span class="text-[10px] text-slate-500">Current video — upload a new file to replace.</span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-700/50">
                <button type="button" onclick="toggleModal('modal-edit-{{ $t->id }}')" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl transition-all">Cancel</button>
                <button type="submit" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-slate-50 text-xs font-semibold rounded-xl transition-all shadow-[0_0_15px_rgba(99,102,241,0.2)]">Update Review</button>
            </div>
        </form>
    </div>
</div>
@endforeach

<!-- Modal: Add Testimonial -->
<div id="modal-add-testimonial" class="fixed inset-0 bg-slate-950/50/80 backdrop-blur-sm z-50 flex items-center justify-center p-6 hidden">
    <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="h-16 flex items-center justify-between px-6 border-b border-slate-700/50">
            <h4 class="font-bold text-slate-50 flex items-center">
                <i class="fa-solid fa-comment-dots text-indigo-500 mr-2"></i> Add Client Review
            </h4>
            <button onclick="toggleModal('modal-add-testimonial')" class="text-slate-400 hover:text-slate-200 text-lg"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6" onsubmit="handleFormSubmit(this)">
            @csrf

            <!-- Name & Company & Rating & Status -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Client Name</label>
                    <input type="text" name="name" required placeholder="e.g. John Doe"
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Company</label>
                    <input type="text" name="company" required placeholder="e.g. Tanaka Inc."
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Rating (1 to 5)</label>
                    <select name="rating" required class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                        <option value="5">5 Stars</option>
                        <option value="4">4 Stars</option>
                        <option value="3">3 Stars</option>
                        <option value="2">2 Stars</option>
                        <option value="1">1 Star</option>
                    </select>
                </div>
                <div class="flex items-center pt-8">
                    <input type="checkbox" name="status" id="test-status" class="w-5 h-5 text-indigo-600 border-slate-700/50 rounded" checked>
                    <label for="test-status" class="ml-2.5 text-sm font-semibold text-slate-300">Set Active</label>
                </div>
            </div>

            <!-- Profile Image Upload -->
            <div class="grid grid-cols-1 gap-6 pt-4 border-t border-slate-700/50/60">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Profile Image (Avatar)</label>
                    <input type="file" name="image" accept="image/*"
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none file:mr-4 file:py-1.5 file:px-4 file:rounded-xl file:border-0 file:bg-indigo-600/20 file:text-indigo-300 file:text-xs file:font-semibold hover:file:bg-indigo-600/30">
                </div>
            </div>

            <!-- Designation EN, BN, JA -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-700/50/60">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Designation (English)</label>
                    <input type="text" name="designation_en" placeholder="e.g. Founder & CEO" required
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Designation (Bangla)</label>
                    <input type="text" name="designation_bn" placeholder="e.g. প্রতিষ্ঠাতা ও সিইও" required
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                </div>
                
            </div>

            <!-- Review EN, BN, JA -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-700/50/60">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Review quote (EN)</label>
                    <textarea name="review_en" rows="4" required class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-xs outline-none"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Review quote (BN)</label>
                    <textarea name="review_bn" rows="4" required class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-xs outline-none"></textarea>
                </div>
                
            </div>

            <!-- Video Review Upload -->
            <div class="grid grid-cols-1 gap-6 pt-4 border-t border-slate-700/50/60">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Video Review (Upload MP4 / WebM)</label>
                    <input type="file" name="video" accept="video/*"
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none file:mr-4 file:py-1.5 file:px-4 file:rounded-xl file:border-0 file:bg-indigo-600/20 file:text-indigo-300 file:text-xs file:font-semibold hover:file:bg-indigo-600/30">
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-700/50">
                <button type="button" onclick="toggleModal('modal-add-testimonial')" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl transition-all">Cancel</button>
                <button type="submit" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-slate-50 text-xs font-semibold rounded-xl transition-all shadow-[0_0_15px_rgba(99,102,241,0.2)]">Save Review</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.toggle('hidden');
    }

    function handleFormSubmit(form) {
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            
            // Retain original width so the button does not shrink when spinner is added
            const rect = submitBtn.getBoundingClientRect();
            submitBtn.style.width = rect.width + 'px';
            
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Processing...
            `;
        }
        return true;
    }
</script>
@endsection
