@extends('admin.layouts.app')

@section('page_title', 'Blog & Categories System')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Left Column: Blog Posts (2/3 width) -->
    <div class="lg:col-span-2 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 text-slate-100">
            <div>
                <h3 class="text-xl font-bold flex items-center">
                    <i class="fa-solid fa-blog text-indigo-500 mr-2"></i> Articles
                </h3>
                <p class="text-xs text-slate-400 mt-1">Write, publish, and organize your company blog posts and insights.</p>
            </div>
            <button onclick="toggleModal('modal-add-post')" class="flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold rounded-xl transition-all duration-300 shadow-[0_0_15px_rgba(99,102,241,0.25)] hover:shadow-[0_0_20px_rgba(99,102,241,0.4)] transform hover:-translate-y-0.5 shrink-0">
                <i class="fa-solid fa-plus"></i> Add Article
            </button>
        </div>

        <!-- Search & Filter Controls -->
        <div class="relative z-20 bg-[#141C2F]/50 backdrop-blur-md border border-white/[0.06] rounded-3xl p-4 flex flex-col sm:flex-row gap-3 items-center justify-between shadow-lg">
            <form action="{{ route('admin.blog.index') }}" method="GET" class="w-full flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                <!-- Search Input -->
                <div class="relative flex-grow">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search articles or slug..." 
                        class="w-full pl-9 pr-4 py-2 bg-slate-950/60 border border-slate-700/50 focus:border-indigo-500 rounded-xl text-slate-200 text-xs outline-none placeholder:text-slate-500 transition-colors">
                </div>
                
                <!-- Status Filter -->
                <div class="relative shrink-0 min-w-[140px]">
                    <select name="status" class="w-full pl-4 pr-8 py-2 bg-slate-950/60 border border-slate-700/50 focus:border-indigo-500 rounded-xl text-slate-200 text-xs outline-none appearance-none cursor-pointer transition-colors">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft Only</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                </div>

                <!-- Submit and Reset Buttons -->
                <div class="flex items-center gap-2 shrink-0">
                    <button type="submit" class="px-4 py-2 bg-indigo-600/80 hover:bg-indigo-600 text-white text-xs font-semibold rounded-xl transition-colors">
                        Filter
                    </button>
                    @if(request()->filled('search') || request()->filled('status'))
                    <a href="{{ route('admin.blog.index') }}" class="px-4 py-2 bg-rose-600/10 text-rose-400 hover:bg-rose-600 hover:text-white text-xs font-semibold rounded-xl transition-colors">
                        Reset
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="bg-gradient-to-br from-[#141C2F]/90 to-[#141C2F]/50 backdrop-blur-md border border-white/[0.06] rounded-3xl p-6 shadow-xl overflow-hidden relative">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="border-b border-white/[0.08] text-slate-400 text-xs font-semibold uppercase tracking-wider">
                            <th class="py-3 px-4">Article</th>
                            <th class="py-3 px-4">Category</th>
                            <th class="py-3 px-4 text-right">Status & Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.04] text-slate-300">
                        @forelse($posts as $post)
                        <tr class="align-middle group hover:bg-white/[0.02] transition-all">
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    @if($post->image_path)
                                    <img src="{{ $post->image_path }}" alt="{{ $post->title }}" class="w-12 h-12 rounded-xl object-cover border border-white/[0.08] shadow-md shrink-0">
                                    @else
                                    <div class="w-12 h-12 bg-gradient-to-tr from-slate-800 to-slate-900 text-slate-400 rounded-xl flex items-center justify-center shrink-0 border border-white/[0.08]">
                                        <i class="fa-solid fa-image text-sm"></i>
                                    </div>
                                    @endif
                                    <div>
                                        <h5 class="font-bold text-slate-100 text-sm mb-0.5 group-hover:text-indigo-400 transition-colors">{{ $post->title }}</h5>
                                        <span class="block text-[10px] text-slate-500 font-mono">Slug: {{ $post->slug }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-block px-2.5 py-1 text-xs font-semibold rounded-full bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                    {{ $post->category->name }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <span class="inline-block px-2.5 py-0.5 text-[9px] font-bold rounded-full {{ $post->status ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-slate-800 text-slate-500' }}">
                                        {{ $post->status ? 'Active' : 'Draft' }}
                                    </span>
                                    <form action="{{ route('admin.blog.delete', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 rounded-xl transition-all" title="Delete Post">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-16 px-4 text-center">
                                <div class="w-12 h-12 rounded-full bg-slate-850 flex items-center justify-center text-slate-400 mx-auto mb-3 border border-white/[0.04]">
                                    <i class="fa-solid fa-blog text-lg"></i>
                                </div>
                                <h4 class="text-sm font-semibold text-slate-350">No articles found</h4>
                                <p class="text-xs text-slate-500 mt-1">There are no posts matching your selection. Add a new article to get started.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination Links -->
            @if($posts->hasPages())
            <div class="mt-6 pt-4 border-t border-white/[0.06]">
                {{ $posts->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Right Column: Categories Manager (1/3 width) -->
    <div class="space-y-6">
        <div>
            <h3 class="text-xl font-bold text-slate-100 flex items-center">
                <i class="fa-solid fa-tags text-indigo-500 mr-2"></i> Categories
            </h3>
            <p class="text-xs text-slate-400 mt-1 font-medium">Add and delete blog categories.</p>
        </div>
        
        <!-- Add Category Block -->
        <div class="bg-gradient-to-br from-[#141C2F]/90 to-[#141C2F]/50 backdrop-blur-md border border-white/[0.06] rounded-3xl p-6 shadow-xl space-y-4">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-white/[0.06] pb-3 flex items-center gap-1.5">
                <i class="fa-solid fa-plus-circle text-indigo-400"></i> Add New Category
            </h4>
            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4" onsubmit="handleFormSubmit(this)">
                @csrf
                <div>
                    <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Slug (Unique)</label>
                    <input type="text" name="slug" placeholder="e.g. web-dev" required
                        class="block w-full px-3 py-2 bg-slate-950/60 border border-slate-700/50 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl text-slate-200 text-xs outline-none transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Name (English)</label>
                    <input type="text" name="name_en" placeholder="e.g. Web Development" required
                        class="block w-full px-3 py-2 bg-slate-950/60 border border-slate-700/50 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl text-slate-200 text-xs outline-none transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Name (Bangla)</label>
                    <input type="text" name="name_bn" placeholder="e.g. ওয়েব ডেভেলপমেন্ট" required
                        class="block w-full px-3 py-2 bg-slate-950/60 border border-slate-700/50 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl text-slate-200 text-xs outline-none transition-all">
                </div>
                
                <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold rounded-xl transition-all shadow-[0_0_12px_rgba(99,102,241,0.2)]">
                    Create Category
                </button>
            </form>
        </div>

        <!-- Categories List -->
        <div class="bg-gradient-to-br from-[#141C2F]/90 to-[#141C2F]/50 backdrop-blur-md border border-white/[0.06] rounded-3xl p-6 shadow-xl space-y-4">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-white/[0.06] pb-3 flex items-center gap-1.5">
                <i class="fa-solid fa-list-check text-indigo-400"></i> Active Categories
            </h4>
            <div class="divide-y divide-white/[0.04] max-h-[300px] overflow-y-auto pr-1">
                @forelse($categories as $category)
                <div class="flex items-center justify-between py-3 hover:bg-white/[0.02] px-2 rounded-xl transition-all">
                    <div>
                        <span class="text-xs font-bold text-slate-200">{{ $category->name }}</span>
                        <span class="block text-[9px] text-slate-500 font-mono mt-0.5">{{ $category->slug }}</span>
                    </div>
                    <form action="{{ route('admin.categories.delete', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-500/10 rounded-xl transition-all" title="Delete Category">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </form>
                </div>
                @empty
                <div class="py-6 text-center text-slate-500 text-xs">
                    <i class="fa-solid fa-tags block text-lg mb-1.5"></i>
                    No categories listed.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Modal: Add Post -->
<div id="modal-add-post" class="fixed inset-0 bg-slate-950/50/80 backdrop-blur-sm z-50 flex items-center justify-center p-6 hidden">
    <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl w-full max-w-4xl max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="h-16 flex items-center justify-between px-6 border-b border-slate-700/50">
            <h4 class="font-bold text-slate-50 flex items-center">
                <i class="fa-solid fa-blog text-indigo-500 mr-2"></i> Create Blog Article
            </h4>
            <button onclick="toggleModal('modal-add-post')" class="text-slate-400 hover:text-slate-200 text-lg"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6" onsubmit="handleFormSubmit(this)">
            @csrf

            <!-- Category & Slug & Status & Image -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Category</label>
                    <select name="category_id" required class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Slug (Unique)</label>
                    <input type="text" name="slug" placeholder="e.g. headless-cms-tutorial" required
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Post Image</label>
                    <input type="file" name="image" class="block w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600/10 file:text-indigo-400 hover:file:bg-indigo-600/20">
                </div>
                <div class="flex items-center pt-8">
                    <input type="checkbox" name="status" id="post-status" class="w-5 h-5 text-indigo-600 border-slate-700/50 rounded" checked>
                    <label for="post-status" class="ml-2.5 text-sm font-semibold text-slate-300">Set Active</label>
                </div>
            </div>

            <!-- Title EN, BN, JA -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-700/50/60">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Title (English)</label>
                    <input type="text" name="title_en" required
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Title (Bangla)</label>
                    <input type="text" name="title_bn" required
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                </div>
            </div>

            <!-- Content EN, BN, JA -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-700/50/60">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Post Content (English)</label>
                    <textarea name="content_en" rows="8" required class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-xs font-mono outline-none"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Post Content (Bangla)</label>
                    <textarea name="content_bn" rows="8" required class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-xs font-mono outline-none"></textarea>
                </div>
            </div>

            <!-- SEO Config details for post -->
            <div class="pt-4 border-t border-slate-700/50/60 space-y-4">
                <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Google Search Ranking (SEO) Meta</span>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">SEO Title (EN)</label>
                        <input type="text" name="seo_title_en" class="block w-full px-3 py-2 bg-slate-950/50 border border-slate-850 rounded-xl text-slate-200 text-xs outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">SEO Title (BN)</label>
                        <input type="text" name="seo_title_bn" class="block w-full px-3 py-2 bg-slate-950/50 border border-slate-850 rounded-xl text-slate-200 text-xs outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Meta Desc (EN)</label>
                        <textarea name="seo_desc_en" rows="2" class="block w-full px-3 py-2 bg-slate-950/50 border border-slate-850 rounded-xl text-slate-200 text-xs outline-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-505 mb-1">Meta Desc (BN)</label>
                        <textarea name="seo_desc_bn" rows="2" class="block w-full px-3 py-2 bg-slate-950/50 border border-slate-850 rounded-xl text-slate-200 text-xs outline-none"></textarea>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-700/50">
                <button type="button" onclick="toggleModal('modal-add-post')" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl transition-all">Cancel</button>
                <button type="submit" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-slate-50 text-xs font-semibold rounded-xl transition-all shadow-[0_0_15px_rgba(99,102,241,0.2)]">Publish Article</button>
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
