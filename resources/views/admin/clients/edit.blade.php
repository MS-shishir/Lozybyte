@extends('admin.layouts.app')

@section('page_title', 'Edit Client Logo')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-8 text-slate-100">
        <h3 class="text-lg font-bold"><i class="fa-solid fa-pen-to-square text-indigo-500 mr-2"></i> Edit Client Logo</h3>
        <a href="{{ route('admin.clients.index') }}" class="px-3 py-1.5 rounded-md border border-white/10 bg-transparent hover:bg-white/5 text-[13px] font-medium text-slate-300 transition-colors">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Back to List
        </a>
    </div>

    <div class="bg-[#000000] border border-white/[0.08] rounded-xl p-8 shadow-xl">
        <form action="{{ route('admin.clients.update', $client) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[13px] font-medium text-slate-400 mb-2">Client Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required value="{{ old('name', $client->name) }}"
                        class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-white/30 rounded-lg text-slate-200 text-[13px] outline-none transition-colors hover:border-white/20">
                </div>
                <div>
                    <label class="block text-[13px] font-medium text-slate-400 mb-2">Website URL</label>
                    <input type="url" name="url" value="{{ old('url', $client->url) }}" placeholder="e.g., https://novalabs.com" 
                        class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-white/30 rounded-lg text-slate-200 text-[13px] outline-none transition-colors hover:border-white/20">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[13px] font-medium text-slate-400 mb-2">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $client->sort_order) }}" 
                        class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-white/30 rounded-lg text-slate-200 text-[13px] outline-none transition-colors hover:border-white/20">
                </div>
                <div>
                    <label class="block text-[13px] font-medium text-slate-400 mb-2">Status</label>
                    <div class="flex items-center h-10">
                        <label class="inline-flex items-center cursor-pointer select-none">
                            <input type="checkbox" name="status" value="1" {{ $client->status ? 'checked' : '' }} class="sr-only peer">
                            <div class="relative w-11 h-6 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            <span class="ms-3 text-sm font-medium text-slate-300">Active</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                <div class="md:col-span-2">
                    <label class="block text-[13px] font-medium text-slate-400 mb-2">Client Logo Image</label>
                    <div class="flex justify-center px-6 pt-4 pb-4 border-2 border-white/10 border-dashed rounded-lg hover:border-white/20 transition-colors relative">
                        <div class="space-y-1 text-center">
                            <i class="fa-regular fa-image text-2xl text-slate-500 mb-1 block"></i>
                            <div class="flex text-sm text-slate-400 justify-center">
                                <label class="relative cursor-pointer bg-transparent rounded-md font-semibold text-indigo-400 hover:text-indigo-300 focus-within:outline-none">
                                    <span>Upload new logo</span>
                                    <input type="file" name="logo" class="sr-only" accept="image/*">
                                </label>
                            </div>
                            <p class="text-xs text-slate-500">PNG, SVG, JPG up to 2MB</p>
                        </div>
                    </div>
                </div>
                
                @if($client->logo_path)
                <div class="bg-[#0a0a0a] rounded-lg border border-white/5 p-4 flex flex-col items-center justify-center h-full min-h-[100px]">
                    <span class="text-[10px] text-slate-500 uppercase tracking-wider font-semibold mb-2 block">Current Logo</span>
                    <img src="{{ preg_match('#^(https?://|/)#', $client->logo_path) ? $client->logo_path : asset('storage/' . $client->logo_path) }}" alt="{{ $client->name }}" class="max-h-12 max-w-full object-contain">
                </div>
                @endif
            </div>

            <div class="pt-4 border-t border-white/[0.08] flex items-center justify-end gap-3">
                <a href="{{ route('admin.clients.index') }}" class="px-4 py-2 border border-white/10 rounded-md hover:bg-white/5 text-[13px] font-medium text-slate-300 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2 bg-white hover:bg-slate-200 text-black text-[13px] font-medium rounded-md shadow-sm transition-colors">
                    Update Client
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
