@extends('admin.layouts.app')

@section('page_title', $homepage_section->key === 'founder_story' ? 'Founder & Vision Video CMS' : 'Edit Homepage Section')

@section('content')
<div class="max-w-4xl mx-auto mb-12">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8 text-slate-100">
        <div>
            <h3 class="text-lg font-bold">
                <i class="fa-solid {{ $homepage_section->key === 'founder_story' ? 'fa-video text-indigo-500' : 'fa-rectangle-list text-indigo-500' }} mr-2"></i> 
                {{ $homepage_section->key === 'founder_story' ? 'Founder & Vision Video Upload' : 'Edit Homepage Section' }}
            </h3>
            <p class="text-xs text-slate-400 mt-1">
                {{ $homepage_section->key === 'founder_story' ? 'Upload or update the video and play cover image displayed on your website.' : 'Modify section details, text translations, and upload media assets.' }}
            </p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl transition-all">
            <i class="fa-solid fa-arrow-left mr-1"></i> Back to Dashboard
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.homepage-sections.update', $homepage_section) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Hidden Fields for Validation -->
        <input type="hidden" name="key" value="{{ $homepage_section->key }}">
        <input type="hidden" name="sort_order" value="{{ $homepage_section->sort_order }}">
        @if($homepage_section->visible)
            <input type="hidden" name="visible" value="active">
        @endif

        @if($homepage_section->key !== 'founder_story')
        <!-- Section 1: Key & Basic Info (Only for non-founder sections) -->
        <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl p-6 shadow-lg space-y-6">
            <h4 class="text-sm font-bold text-indigo-400 border-b border-slate-700/50 pb-3"><i class="fa-solid fa-key mr-2"></i> Section Key & Metadata</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Section Key (Slug)</label>
                    <input type="text" readonly value="{{ $homepage_section->key }}" required
                        class="block w-full px-4 py-2.5 bg-slate-900 border border-slate-850 rounded-2xl text-slate-500 text-sm outline-none cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ $homepage_section->sort_order }}" required
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                </div>
            </div>

            <div class="flex items-center pt-2">
                <input type="checkbox" name="visible" id="sec-visible" class="w-5 h-5 text-indigo-600 border-slate-700/50 rounded bg-slate-950" value="active" {{ $homepage_section->visible ? 'checked' : '' }}>
                <label for="sec-visible" class="ml-2.5 text-sm font-semibold text-slate-300">Visible (Active on Home page)</label>
            </div>
        </div>

        <!-- Section 2: Text Translations (Only for non-founder sections) -->
        <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl p-6 shadow-lg space-y-6">
            <h4 class="text-sm font-bold text-indigo-400 border-b border-slate-700/50 pb-3"><i class="fa-solid fa-language mr-2"></i> Content Translations</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Title (English)</label>
                    <input type="text" name="title_en" value="{{ $homepage_section->title['en'] ?? '' }}"
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Title (Bangla)</label>
                    <input type="text" name="title_bn" value="{{ $homepage_section->title['bn'] ?? '' }}"
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Subtitle (English)</label>
                    <input type="text" name="subtitle_en" value="{{ $homepage_section->subtitle['en'] ?? '' }}"
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Subtitle (Bangla)</label>
                    <input type="text" name="subtitle_bn" value="{{ $homepage_section->subtitle['bn'] ?? '' }}"
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Description (English)</label>
                    <textarea name="description_en" rows="3"
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-xs outline-none">{{ $homepage_section->description['en'] ?? '' }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Description (Bangla)</label>
                    <textarea name="description_bn" rows="3"
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-xs outline-none">{{ $homepage_section->description['bn'] ?? '' }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Button Text (EN)</label>
                    <input type="text" name="button_text_en" value="{{ $homepage_section->button_text['en'] ?? '' }}"
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Button Text (BN)</label>
                    <input type="text" name="button_text_bn" value="{{ $homepage_section->button_text['bn'] ?? '' }}"
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Button Target URL</label>
                    <input type="text" name="button_url" value="{{ $homepage_section->button_url }}"
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                </div>
            </div>
        </div>
        @endif

        <!-- Media Uploads (Required for all, but styled specifically for Founder video if key matches) -->
        <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl p-6 shadow-lg space-y-6">
            <h4 class="text-sm font-bold text-indigo-400 border-b border-slate-700/50 pb-3"><i class="fa-solid fa-photo-film mr-2"></i> Media & Assets Upload</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Thumbnail Upload -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
                            {{ $homepage_section->key === 'founder_story' ? 'Watch Video Thumbnail (Image)' : 'Background Image' }}
                        </label>
                        <input type="file" name="background_image" accept="image/*"
                            class="block w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600/10 file:text-indigo-400 hover:file:bg-indigo-600/20">
                        <p class="text-[10px] text-slate-500 mt-1.5">This image displays as a cover before clicking the play button.</p>
                    </div>
                    @if($homepage_section->background_image) 
                    <div class="p-3 bg-slate-950/30 border border-slate-800 rounded-2xl inline-block">
                        <img src="{{ preg_match('#^(https?://|/)#', $homepage_section->background_image) ? $homepage_section->background_image : asset('storage/' . $homepage_section->background_image) }}" 
                            class="max-h-36 rounded-xl border border-slate-700 object-cover shadow-md"> 
                    </div>
                    @endif
                </div>

                <!-- Video Upload -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
                            {{ $homepage_section->key === 'founder_story' ? 'Founder Message Video File (MP4)' : 'Main Image' }}
                        </label>
                        <input type="file" name="main_image" accept="{{ $homepage_section->key === 'founder_story' ? 'video/*' : 'image/*' }}"
                            class="block w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600/10 file:text-indigo-400 hover:file:bg-indigo-600/20">
                        <p class="text-[10px] text-slate-500 mt-1.5">Select your MP4/WebM video message file (maximum 20MB).</p>
                    </div>
                    @if($homepage_section->main_image) 
                    <div class="p-3 bg-slate-950/30 border border-slate-800 rounded-2xl inline-block">
                        @if($homepage_section->key === 'founder_story')
                            <video src="{{ preg_match('#^(https?://|/)#', $homepage_section->main_image) ? $homepage_section->main_image : asset('storage/' . $homepage_section->main_image) }}" 
                                class="max-h-36 rounded-xl border border-slate-700 shadow-md" controls></video>
                        @else
                            <img src="{{ preg_match('#^(https?://|/)#', $homepage_section->main_image) ? $homepage_section->main_image : asset('storage/' . $homepage_section->main_image) }}" 
                                class="max-h-36 rounded-xl border border-slate-700 object-cover shadow-md"> 
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Submit Panel -->
        <div class="pt-4 border-t border-slate-800 flex items-center justify-end gap-3">
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 border border-slate-750 text-slate-400 hover:text-slate-200 hover:bg-slate-800/40 rounded-xl text-xs font-semibold transition-all">Cancel</a>
            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-slate-50 text-xs font-bold rounded-xl shadow-[0_0_15px_rgba(99,102,241,0.2)] transition-all">
                Save & Update Video
            </button>
        </div>
    </form>
</div>
@endsection
