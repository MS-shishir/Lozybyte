@extends('admin.layouts.app')

@section('page_title', 'Manage Clients')

@section('content')
<div class="flex items-center justify-between mb-8 text-slate-100">
    <h3 class="text-lg font-bold"><i class="fa-solid fa-circle-nodes text-indigo-500 mr-2"></i> Client Logos</h3>
    <a href="{{ route('admin.clients.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-slate-50 text-xs font-semibold rounded-xl transition-all shadow-[0_0_15px_rgba(99,102,241,0.2)] flex items-center">
        <i class="fa-solid fa-plus mr-1.5"></i> Add Client
    </a>
</div>

<!-- Clients Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
    @forelse($clients as $client)
    <div class="bg-[#000000] border border-white/[0.08] hover:border-white/20 rounded-xl p-5 flex flex-col justify-between shadow-lg transition-all duration-300">
        <div>
            <!-- Client Logo / Emblem Card -->
            <div class="h-24 bg-[#0a0a0a] rounded-lg border border-white/5 flex items-center justify-center p-4 mb-4 relative overflow-hidden group">
                @if($client->logo_path)
                <img src="{{ preg_match('#^(https?://|/)#', $client->logo_path) ? $client->logo_path : asset('storage/' . $client->logo_path) }}" alt="{{ $client->name }}" class="max-h-16 max-w-full object-contain filter grayscale opacity-60 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-300">
                @else
                <div class="flex flex-col items-center justify-center text-center">
                    <div class="w-10 h-10 bg-indigo-600/10 text-indigo-400 rounded-lg flex items-center justify-center font-bold text-lg mb-1.5">
                        {{ substr($client->name, 0, 2) }}
                    </div>
                    <span class="text-xs text-slate-500 font-mono">No Image</span>
                </div>
                @endif
            </div>

            <!-- Client Info -->
            <div class="space-y-1">
                <h4 class="font-semibold text-slate-100 truncate text-[15px]" title="{{ $client->name }}">{{ $client->name }}</h4>
                <div class="flex items-center justify-between text-xs pt-1">
                    <span class="text-slate-400">Order: {{ $client->sort_order }}</span>
                    @if($client->url)
                    <a href="{{ $client->url }}" target="_blank" rel="noopener noreferrer" class="text-indigo-400 hover:text-indigo-300 flex items-center gap-1">
                        <i class="fa-solid fa-link text-[10px]"></i> Link
                    </a>
                    @else
                    <span class="text-slate-600 font-mono text-[10px]">No Link</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between mt-6 pt-4 border-t border-white/[0.08]">
            <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold rounded-full {{ $client->status ? 'bg-emerald-500/10 text-emerald-400' : 'bg-slate-800 text-slate-500' }}">
                {{ $client->status ? 'Active' : 'Inactive' }}
            </span>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.clients.edit', $client) }}" class="text-indigo-400 hover:text-indigo-300 text-xs font-semibold">
                    <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                </a>
                <form action="{{ route('admin.clients.destroy', $client) }}" method="POST" onsubmit="return confirm('Delete this client logo?')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-rose-500 hover:text-rose-400 text-xs font-semibold">
                        <i class="fa-solid fa-trash-can mr-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-16 text-center text-slate-500 bg-[#000000] border border-white/[0.08] rounded-xl">
        <i class="fa-solid fa-circle-nodes text-5xl block mb-3 text-slate-600"></i>
        <p class="text-sm font-medium mb-1">No client logos added yet</p>
        <p class="text-xs text-slate-600 mb-4">Add your first corporate client or partner brand</p>
        <a href="{{ route('admin.clients.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-slate-50 text-xs font-semibold rounded-xl transition-all shadow-[0_0_15px_rgba(99,102,241,0.2)]">
            <i class="fa-solid fa-plus mr-1.5"></i> Add Client
        </a>
    </div>
    @endforelse
</div>
@endsection
