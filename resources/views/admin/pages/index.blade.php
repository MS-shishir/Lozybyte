@extends('admin.layouts.app')

@section('page_title', 'Manage Custom Pages')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <h3 class="text-lg font-bold text-slate-100"><i class="fa-solid fa-file-lines text-indigo-500 mr-2"></i> Dynamic Content Pages</h3>
    <button onclick="openAddModal()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-slate-50 text-xs font-semibold rounded-xl transition-all shadow-[0_0_15px_rgba(99,102,241,0.2)] shrink-0 self-start sm:self-auto">
        <i class="fa-solid fa-plus mr-1"></i> Add Custom Page
    </button>
</div>

<!-- Filters & Search -->
<div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl p-6 mb-8 shadow-lg">
    <form action="{{ route('admin.pages.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
        <div class="flex-1 w-full">
            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Search Pages</label>
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title or slug..."
                    class="block w-full pl-10 pr-4 py-2.5 bg-slate-950/50/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                <div class="absolute left-3.5 top-3.5 text-slate-500 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
            </div>
        </div>
        <div class="w-full md:w-48">
            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Status</label>
            <select name="status" class="block w-full px-4 py-2.5 bg-slate-950/50/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Hidden</option>
            </select>
        </div>
        <div class="flex gap-2 w-full md:w-auto">
            <button type="submit" class="flex-1 md:flex-none px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold rounded-2xl transition-colors">
                Filter
            </button>
            <a href="{{ route('admin.pages.index') }}" class="flex-1 md:flex-none px-5 py-2.5 border border-slate-700/50 hover:bg-[#141C2F]/80 backdrop-blur-md text-slate-400 hover:text-slate-300 text-xs font-bold rounded-2xl text-center transition-colors">
                Clear
            </a>
        </div>
    </form>
</div>

<!-- Pages Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    @forelse($pages as $page)
    <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl p-6 flex flex-col justify-between shadow-lg">
        <div>
            <div class="flex items-start justify-between">
                <div class="overflow-hidden">
                    <h4 class="font-bold text-slate-100 truncate text-base">{{ $page->title }}</h4>
                    <span class="inline-block mt-1 font-mono text-[10px] text-indigo-400">/[slug]: /{{ $page->slug }}</span>
                </div>
                <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold rounded-full shrink-0 {{ $page->status ? 'bg-emerald-500/10 text-emerald-400' : 'bg-slate-800 text-slate-500' }}">
                    {{ $page->status ? 'Published' : 'Hidden' }}
                </span>
            </div>

            @if($page->banner_image)
            <div class="mt-4 h-24 w-full rounded-2xl overflow-hidden border border-slate-700/50">
                <img src="{{ $page->banner_image }}" alt="Banner" class="w-full h-full object-cover">
            </div>
            @endif

            <p class="text-xs text-slate-400 mt-4 leading-relaxed line-clamp-3">
                {{ strip_tags($page->content) }}
            </p>

            <div class="mt-4 p-3 bg-slate-950/50/50/40 rounded-2xl border border-slate-700/50/60 grid grid-cols-2 gap-2 text-[10px] text-slate-400">
                <div>
                    <span class="block font-bold text-slate-500 uppercase tracking-wider mb-0.5">SEO Title (EN)</span>
                    <span class="truncate block font-medium">{{ $page->seo['meta_title']['en'] ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="block font-bold text-slate-500 uppercase tracking-wider mb-0.5">SEO Desc (EN)</span>
                    <span class="truncate block font-medium">{{ $page->seo['meta_description']['en'] ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="block font-bold text-slate-500 uppercase tracking-wider mb-0.5">OG Title (EN)</span>
                    <span class="truncate block font-medium text-indigo-400">{{ $page->seo['og_title']['en'] ?? 'Not set' }}</span>
                </div>
                <div>
                    <span class="block font-bold text-slate-500 uppercase tracking-wider mb-0.5">OG Image</span>
                    @if(!empty($page->seo['og_image']))
                        <img src="{{ $page->seo['og_image'] }}" alt="OG" class="h-6 w-auto rounded object-cover border border-slate-700/50">
                    @else
                        <span class="text-slate-600 font-medium">Not set</span>
                    @endif
                </div>
            </div>

        </div>

        <div class="flex items-center justify-between mt-6 pt-4 border-t border-slate-700/50/60">
            <span class="text-[10px] text-slate-500 font-medium">Created: {{ $page->created_at->format('M d, Y') }}</span>
            <div class="flex items-center space-x-2">
                <button onclick='openEditModal({!! json_encode($page) !!})' class="px-3 py-1.5 bg-slate-800 hover:bg-indigo-600 hover:text-white text-slate-300 text-xs font-semibold rounded-xl transition-colors">
                    Edit
                </button>
                <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this page?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3 py-1.5 bg-rose-600/10 hover:bg-rose-600 text-rose-400 hover:text-slate-50 text-xs font-semibold rounded-xl transition-colors">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-2 py-12 text-center text-slate-500 bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl">
        <i class="fa-solid fa-file-lines text-4xl block mb-2"></i>
        No custom pages found. Click "Add Custom Page" to create one.
    </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $pages->links() }}
</div>

<!-- Modal: Add / Edit Page -->
<div id="modal-page" class="fixed inset-0 bg-slate-950/50/50/80 backdrop-blur-sm z-50 flex items-center justify-center p-6 hidden">
    <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl w-full max-w-4xl max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="h-16 flex items-center justify-between px-6 border-b border-slate-700/50">
            <h4 id="modal-title" class="font-bold text-slate-50 flex items-center">
                <i class="fa-solid fa-file-lines text-indigo-500 mr-2"></i> Add Page Content
            </h4>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-200 text-lg"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form id="modal-form" action="{{ route('admin.pages.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            <input type="hidden" name="_method" id="form-method" value="POST">

            <!-- Slug & Status & Banner Image -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Slug (URL identifier)</label>
                    <input type="text" name="slug" id="field-slug" placeholder="e.g. privacy-policy, terms-conditions" required
                        class="block w-full px-4 py-2.5 bg-slate-950/50/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Banner Image</label>
                    <input type="file" name="banner_image" class="block w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-indigo-600/10 file:text-indigo-400 hover:file:bg-indigo-600/20">
                    <span id="banner-preview-text" class="text-[10px] text-slate-500 mt-1 block truncate"></span>
                </div>
                <div class="flex items-center pt-8">
                    <input type="checkbox" name="status" id="field-status" class="w-5 h-5 text-indigo-600 border-slate-700/50 rounded focus:ring-indigo-500" checked>
                    <label for="field-status" class="ml-2.5 text-sm font-semibold text-slate-300">Publish immediately</label>
                </div>
            </div>

            <!-- Page Title EN, BN, JA -->
            <div class="space-y-4 pt-3 border-t border-slate-700/50/60">
                <h5 class="text-xs font-bold text-slate-300 uppercase tracking-widest">Page Title</h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">English</label>
                        <input type="text" name="title_en" id="field-title-en" required
                            class="block w-full px-4 py-2.5 bg-slate-950/50/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Bangla</label>
                        <input type="text" name="title_bn" id="field-title-bn"
                            class="block w-full px-4 py-2.5 bg-slate-950/50/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                    </div>
                    <div>
                        

                    </div>
                </div>
            </div>

            <!-- Page Content EN, BN, JA -->
            <div class="space-y-4 pt-3 border-t border-slate-700/50/60">
                <h5 class="text-xs font-bold text-slate-300 uppercase tracking-widest">Page Body Content (Supports HTML)</h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">English</label>
                        <textarea name="content_en" id="field-content-en" rows="8" required
                            class="block w-full px-4 py-3 bg-slate-950/50/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-xs font-mono outline-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Bangla</label>
                        <textarea name="content_bn" id="field-content-bn" rows="8"
                            class="block w-full px-4 py-3 bg-slate-950/50/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-xs font-mono outline-none"></textarea>
                    </div>
                    <div>
                        

                    </div>
                </div>
            </div>

            <!-- SEO Configuration -->
            <div class="space-y-4 pt-3 border-t border-slate-700/50/60">
                <h5 class="text-xs font-bold text-slate-300 uppercase tracking-widest"><i class="fa-solid fa-magnifying-glass-chart text-indigo-500 mr-1.5"></i> Page SEO Configuration</h5>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Meta Title (EN)</label>
                        <input type="text" name="seo_title_en" id="field-seo-title-en"
                            class="block w-full px-3.5 py-2 bg-slate-950/50/50 border border-slate-700/50 focus:border-indigo-500 rounded-xl text-slate-200 text-xs outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Meta Title (BN)</label>
                        <input type="text" name="seo_title_bn" id="field-seo-title-bn"
                            class="block w-full px-3.5 py-2 bg-slate-950/50/50 border border-slate-700/50 focus:border-indigo-500 rounded-xl text-slate-200 text-xs outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Meta Title</label>

                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Meta Description (EN)</label>
                        <textarea name="seo_desc_en" id="field-seo-desc-en" rows="2"
                            class="block w-full px-3.5 py-2 bg-slate-950/50/50 border border-slate-700/50 focus:border-indigo-500 rounded-xl text-slate-200 text-xs outline-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Meta Description (BN)</label>
                        <textarea name="seo_desc_bn" id="field-seo-desc-bn" rows="2"
                            class="block w-full px-3.5 py-2 bg-slate-950/50/50 border border-slate-700/50 focus:border-indigo-500 rounded-xl text-slate-200 text-xs outline-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Meta Description</label>

                    </div>
                </div>
            </div>

            <!-- Open Graph (OG) Configuration -->
            <div class="space-y-4 pt-3 border-t border-slate-700/50/60">
                <h5 class="text-xs font-bold text-slate-300 uppercase tracking-widest"><i class="fa-solid fa-share-nodes text-indigo-500 mr-1.5"></i> Open Graph (OG) Control</h5>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">OG Title (EN)</label>
                        <input type="text" name="og_title_en" id="field-og-title-en"
                            class="block w-full px-3.5 py-2 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-xl text-slate-200 text-xs outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">OG Title (BN)</label>
                        <input type="text" name="og_title_bn" id="field-og-title-bn"
                            class="block w-full px-3.5 py-2 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-xl text-slate-200 text-xs outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">OG Description (EN)</label>
                        <textarea name="og_desc_en" id="field-og-desc-en" rows="2"
                            class="block w-full px-3.5 py-2 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-xl text-slate-200 text-xs outline-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">OG Description (BN)</label>
                        <textarea name="og_desc_bn" id="field-og-desc-bn" rows="2"
                            class="block w-full px-3.5 py-2 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-xl text-slate-200 text-xs outline-none"></textarea>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">OG Image <span class="text-slate-500 normal-case font-normal">(Upload new to replace existing)</span></label>
                    <input type="file" name="og_image" accept="image/*" class="block w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-indigo-600/10 file:text-indigo-400 hover:file:bg-indigo-600/20">
                    <span id="og-image-preview-text" class="text-[10px] text-emerald-400 mt-1 block font-medium"></span>
                    <img id="og-image-preview-img" src="" alt="Current OG Image" class="hidden mt-2 h-24 rounded-xl border border-slate-700/50 object-cover">
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-700/50">
                <button type="button" onclick="closeModal()" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl transition-all">Cancel</button>
                <button type="submit" id="btn-submit" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-slate-50 text-xs font-semibold rounded-xl transition-all shadow-[0_0_15px_rgba(99,102,241,0.2)]">Save Page</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('modal-title').innerHTML = '<i class="fa-solid fa-file-lines text-indigo-500 mr-2"></i> Add Page Content';
        document.getElementById('form-method').value = 'POST';
        document.getElementById('modal-form').action = "{{ route('admin.pages.store') }}";
        document.getElementById('banner-preview-text').innerText = '';
        document.getElementById('og-image-preview-text').innerText = '';

        // Clear OG image preview
        const ogPreviewImg = document.getElementById('og-image-preview-img');
        if (ogPreviewImg) ogPreviewImg.classList.add('hidden');

        // Reset all inputs
        document.getElementById('field-slug').value = '';
        document.getElementById('field-status').checked = true;
        document.getElementById('field-title-en').value = '';
        document.getElementById('field-title-bn').value = '';
        document.getElementById('field-content-en').value = '';
        document.getElementById('field-content-bn').value = '';
        document.getElementById('field-seo-title-en').value = '';
        document.getElementById('field-seo-title-bn').value = '';
        document.getElementById('field-seo-desc-en').value = '';
        document.getElementById('field-seo-desc-bn').value = '';
        document.getElementById('field-og-title-en').value = '';
        document.getElementById('field-og-title-bn').value = '';
        document.getElementById('field-og-desc-en').value = '';
        document.getElementById('field-og-desc-bn').value = '';

        document.getElementById('modal-page').classList.remove('hidden');
    }

    function openEditModal(page) {
        document.getElementById('modal-title').innerHTML = '<i class="fa-solid fa-file-lines text-indigo-500 mr-2"></i> Edit Page Content';
        document.getElementById('form-method').value = 'POST';
        document.getElementById('modal-form').action = "/admin/pages/" + page.id;
        document.getElementById('banner-preview-text').innerText = page.banner_image ? 'Current: ' + page.banner_image : '';

        // Fill basic fields
        document.getElementById('field-slug').value = page.slug || '';
        document.getElementById('field-status').checked = !!page.status;

        // Parse Title translations (stored as JSON column)
        let titles = {};
        try { titles = typeof page.title === 'object' ? page.title : JSON.parse(page.title || '{}'); } catch(e) {}
        document.getElementById('field-title-en').value = titles.en || page.title || '';
        document.getElementById('field-title-bn').value = titles.bn || '';

        // Parse Content translations
        let contents = {};
        try { contents = typeof page.content === 'object' ? page.content : JSON.parse(page.content || '{}'); } catch(e) {}
        document.getElementById('field-content-en').value = contents.en || page.content || '';
        document.getElementById('field-content-bn').value = contents.bn || '';

        // Parse SEO & OG values
        const seo = page.seo || {};
        const seoTitles = seo.meta_title || {};
        const seoDescs = seo.meta_description || {};
        const ogTitles = seo.og_title || {};
        const ogDescs = seo.og_description || {};

        document.getElementById('field-seo-title-en').value = seoTitles.en || '';
        document.getElementById('field-seo-title-bn').value = seoTitles.bn || '';
        document.getElementById('field-seo-desc-en').value = seoDescs.en || '';
        document.getElementById('field-seo-desc-bn').value = seoDescs.bn || '';

        document.getElementById('field-og-title-en').value = ogTitles.en || '';
        document.getElementById('field-og-title-bn').value = ogTitles.bn || '';
        document.getElementById('field-og-desc-en').value = ogDescs.en || '';
        document.getElementById('field-og-desc-bn').value = ogDescs.bn || '';

        // Show current OG image preview
        const ogImg = seo.og_image || null;
        document.getElementById('og-image-preview-text').innerText = ogImg ? 'Current OG Image set (upload new to replace)' : '';
        const ogPreviewImg = document.getElementById('og-image-preview-img');
        if (ogImg && ogPreviewImg) {
            ogPreviewImg.src = ogImg;
            ogPreviewImg.classList.remove('hidden');
        } else if (ogPreviewImg) {
            ogPreviewImg.classList.add('hidden');
        }

        document.getElementById('modal-page').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modal-page').classList.add('hidden');
    }
</script>
@endsection
