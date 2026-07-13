@extends('admin.layouts.app')

@section('page_title', 'Add Client Logo')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-8 text-slate-100">
        <h3 class="text-lg font-bold"><i class="fa-solid fa-plus text-indigo-500 mr-2"></i> Add Client Logo</h3>
        <a href="{{ route('admin.clients.index') }}" class="px-3 py-1.5 rounded-md border border-white/10 bg-transparent hover:bg-white/5 text-[13px] font-medium text-slate-300 transition-colors">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Back to List
        </a>
    </div>

    <div class="bg-[#000000] border border-white/[0.08] rounded-xl p-8 shadow-xl">
        <form action="{{ route('admin.clients.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[13px] font-medium text-slate-400 mb-2">Client Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="e.g., NovaLabs" 
                        class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-white/30 rounded-lg text-slate-200 text-[13px] outline-none transition-colors hover:border-white/20">
                </div>
                <div>
                    <label class="block text-[13px] font-medium text-slate-400 mb-2">Website URL</label>
                    <input type="url" name="url" placeholder="e.g., https://novalabs.com" 
                        class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-white/30 rounded-lg text-slate-200 text-[13px] outline-none transition-colors hover:border-white/20">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[13px] font-medium text-slate-400 mb-2">Sort Order</label>
                    <input type="number" name="sort_order" value="0" 
                        class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-white/30 rounded-lg text-slate-200 text-[13px] outline-none transition-colors hover:border-white/20">
                </div>
                <div>
                    <label class="block text-[13px] font-medium text-slate-400 mb-2">Status</label>
                    <div class="flex items-center h-10">
                        <label class="inline-flex items-center cursor-pointer select-none">
                            <input type="checkbox" name="status" value="1" checked class="sr-only peer">
                            <div class="relative w-11 h-6 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            <span class="ms-3 text-sm font-medium text-slate-300">Active</span>
                        </label>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-[13px] font-medium text-slate-400 mb-2">Client Logo Image</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-white/10 border-dashed rounded-lg hover:border-white/20 transition-colors relative">
                    <div class="space-y-1 text-center">
                        <i class="fa-regular fa-image text-3xl text-slate-500 mb-2 block"></i>
                        <div class="flex text-sm text-slate-400 justify-center">
                            <label class="relative cursor-pointer bg-transparent rounded-md font-semibold text-indigo-400 hover:text-indigo-300 focus-within:outline-none">
                                <span>Upload a file</span>
                                <input type="file" name="logo" class="sr-only" accept="image/*">
                            </label>
                        </div>
                        <p class="text-xs text-slate-500">PNG, SVG, JPG up to 2MB</p>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-white/[0.08] flex items-center justify-end gap-3">
                <a href="{{ route('admin.clients.index') }}" class="px-4 py-2 border border-white/10 rounded-md hover:bg-white/5 text-[13px] font-medium text-slate-300 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2 bg-white hover:bg-slate-200 text-black text-[13px] font-medium rounded-md shadow-sm transition-colors">
                    Save Client
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.querySelector('input[type="file"]').addEventListener('change', function(e) {
        const textLabel = this.closest('div').querySelector('span');
        if (this.files.length) {
            textLabel.textContent = this.files[0].name;
            textLabel.className = "text-emerald-400 font-semibold";
        }
    });
</script>
@endsection
