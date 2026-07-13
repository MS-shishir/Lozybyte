@extends('admin.layouts.app')

@section('page_title', 'Media Manager')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <h3 class="text-lg font-bold text-slate-100"><i class="fa-solid fa-photo-film text-indigo-500 mr-2"></i> Media & Assets Manager</h3>
    
    <!-- Upload Area Form Trigger -->
    <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-2 shrink-0 self-start sm:self-auto">
        @csrf
        <label class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-slate-50 text-xs font-semibold rounded-xl transition-all shadow-[0_0_15px_rgba(99,102,241,0.2)] cursor-pointer">
            <i class="fa-solid fa-cloud-arrow-up mr-1"></i> Upload Image File
            <input type="file" name="file" onchange="this.form.submit()" class="hidden" accept="image/*">
        </label>
    </form>
</div>

<!-- Search & Filtering -->
<div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl p-6 mb-8 shadow-lg">
    <form action="{{ route('admin.media.index') }}" method="GET" class="flex gap-4 items-end">
        <div class="flex-1 w-full">
            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Search Assets</label>
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by filename..."
                    class="block w-full pl-10 pr-4 py-2.5 bg-slate-950/50/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                <div class="absolute left-3.5 top-3.5 text-slate-500 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
            </div>
        </div>
        <button type="submit" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold rounded-2xl transition-colors shrink-0">
            Filter
        </button>
        <a href="{{ route('admin.media.index') }}" class="px-5 py-2.5 border border-slate-700/50 hover:bg-[#141C2F]/80 backdrop-blur-md text-slate-400 hover:text-slate-300 text-xs font-bold rounded-2xl text-center transition-colors shrink-0">
            Clear
        </a>
    </form>
</div>

<!-- Media Files Grid -->
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6 mb-6">
    @forelse($mediaFiles as $media)
    <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl overflow-hidden shadow-lg group relative flex flex-col justify-between">
        <!-- Thumbnail Frame -->
        <div class="aspect-square bg-slate-950/50/50 flex items-center justify-center overflow-hidden p-2 relative">
            <img src="{{ $media->file_path }}" alt="{{ $media->filename }}" class="max-w-full max-h-full object-contain rounded-xl group-hover:scale-105 transition-transform duration-300">
            
            <!-- Quick Actions Overlay -->
            <div class="absolute inset-0 bg-slate-950/50/50/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center space-x-2 px-2">
                <button onclick="copyToClipboard('{{ $media->file_path }}')" title="Copy URL path"
                    class="p-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs transition-transform hover:scale-110">
                    <i class="fa-solid fa-copy"></i>
                </button>
                <a href="{{ $media->file_path }}" target="_blank" title="View Fullscreen"
                    class="p-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-xs transition-transform hover:scale-110">
                    <i class="fa-solid fa-up-right-from-square"></i>
                </a>
            </div>
        </div>

        <!-- Details -->
        <div class="p-4 border-t border-slate-700/50 bg-[#141C2F]/80 backdrop-blur-md flex flex-col justify-between flex-1">
            <div class="overflow-hidden mb-3">
                <span class="block text-xs font-semibold text-slate-200 truncate" title="{{ $media->filename }}">{{ $media->filename }}</span>
                <span class="block text-[9px] text-slate-500 font-mono mt-0.5">{{ number_format($media->file_size / 1024, 1) }} KB · {{ explode('/', $media->file_type)[1] ?? 'File' }}</span>
            </div>

            <!-- Delete Form -->
            <form action="{{ route('admin.media.destroy', $media->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this media file permanently? This cannot be undone!')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-1.5 bg-rose-600/10 hover:bg-rose-600 text-rose-400 hover:text-white text-[10px] font-bold rounded-xl transition-colors">
                    <i class="fa-solid fa-trash-can mr-1"></i> Delete
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-full py-16 text-center text-slate-500 bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl">
        <i class="fa-solid fa-images text-5xl block mb-2 text-slate-700"></i>
        No uploads found. Select "Upload Image File" above to start populating your media library.
    </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $mediaFiles->links() }}
</div>

<!-- Quick JS Toast notification script for copying -->
<div id="toast" class="fixed bottom-6 right-6 px-4 py-2.5 bg-emerald-600 text-slate-50 text-xs font-semibold rounded-xl shadow-2xl z-50 transition-all opacity-0 pointer-events-none translate-y-2">
    <i class="fa-solid fa-circle-check mr-2"></i> File path copied to clipboard successfully!
</div>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            const toast = document.getElementById('toast');
            toast.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-2');
            toast.classList.add('opacity-100');
            
            setTimeout(function() {
                toast.classList.add('opacity-0', 'pointer-events-none', 'translate-y-2');
                toast.classList.remove('opacity-100');
            }, 2500);
        }, function(err) {
            console.error('Could not copy text: ', err);
        });
    }
</script>
@endsection
