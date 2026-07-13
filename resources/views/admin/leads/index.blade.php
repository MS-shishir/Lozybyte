@extends('admin.layouts.app')

@section('page_title', 'Leads Inbox')

@section('content')
<!-- Filter Panel -->
<div class="relative z-20 bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl p-6 mb-8 shadow-lg">
    <form action="{{ route('admin.leads.index') }}" method="GET" class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-4">
            <!-- Status Filter -->
            <div>
                <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Filter Status</label>
                <select name="status" onchange="this.form.submit()" class="status-select bg-slate-950/50 border border-slate-700/50 rounded-xl px-4 py-2 text-xs font-semibold text-slate-300 outline-none">
                    <option value="">All Statuses</option>
                    <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
                    <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>Contacted</option>
                    <option value="converted" {{ request('status') === 'converted' ? 'selected' : '' }}>Converted</option>
                    <option value="lost" {{ request('status') === 'lost' ? 'selected' : '' }}>Lost</option>
                </select>
            </div>
            <!-- Source Filter -->
            <div>
                <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Filter Source</label>
                <select name="source" onchange="this.form.submit()" class="bg-slate-950/50 border border-slate-700/50 rounded-xl px-4 py-2 text-xs font-semibold text-slate-300 outline-none">
                    <option value="">All Sources</option>
                    <option value="contact_form" {{ request('source') === 'contact_form' ? 'selected' : '' }}>Contact Form</option>
                    <option value="ai_assistant" {{ request('source') === 'ai_assistant' ? 'selected' : '' }}>AI Lead Assistant</option>
                    <option value="quotation" {{ request('source') === 'quotation' ? 'selected' : '' }}>Quotation Form</option>
                </select>
            </div>
        </div>

        <div>
            @if(request()->filled('status') || request()->filled('source'))
            <a href="{{ route('admin.leads.index') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-xs font-semibold text-slate-300 rounded-xl transition-all">
                Clear Filters
            </a>
            @endif
        </div>
    </form>
</div>

<!-- Leads Table -->
<div class="relative z-10 bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl p-8 shadow-lg">
    <div class="overflow-x-auto" style="min-height: 350px;">
        <table class="w-full text-left text-sm border-collapse">
            <thead>
                <tr class="border-b border-slate-700/50 text-slate-400 text-xs font-semibold uppercase tracking-wider">
                    <th class="py-3 px-4">Client Detail</th>
                    <th class="py-3 px-4">Submission details</th>
                    <th class="py-3 px-4">Message / Query</th>
                    <th class="py-3 px-4">Action & Log Notes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800 text-slate-300">
                @forelse($leads as $lead)
                <tr class="align-top group hover:bg-slate-950/50/20 transition-all">
                    <!-- Client Name / Contact -->
                    <td class="py-5 px-4">
                        <h5 class="font-bold text-slate-100 text-sm mb-1">{{ $lead->name }}</h5>
                        <p class="text-xs text-slate-400"><i class="fa-solid fa-envelope mr-1.5 text-slate-500"></i>{{ $lead->email }}</p>
                        <p class="text-xs text-slate-400 mt-0.5"><i class="fa-solid fa-phone mr-1.5 text-slate-500"></i>{{ $lead->phone ?? 'No Phone' }}</p>
                    </td>

                    <!-- Service / Source -->
                    <td class="py-5 px-4">
                        <span class="block font-medium text-xs text-indigo-400 mb-1.5">{{ $lead->service ?? 'General Inquiry' }}</span>
                        <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold rounded-full bg-slate-800 text-slate-300 capitalize">
                            {{ str_replace('_', ' ', $lead->source) }}
                        </span>
                    </td>

                    <!-- Message Body -->
                    <td class="py-5 px-4 max-w-xs md:max-w-md">
                        <p class="text-xs leading-relaxed text-slate-300 whitespace-pre-wrap">{{ $lead->message ?? 'No text message provided.' }}</p>
                        <span class="text-[10px] text-slate-500 block mt-2">Received: {{ $lead->created_at->format('M d, Y - h:i A') }}</span>
                    </td>

                    <!-- Action and Status Form -->
                    <td class="py-5 px-4 w-72">
                        <form action="{{ route('admin.leads.update', $lead->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <div class="flex items-center gap-2">
                                <div class="flex-1">
                                    <select name="status" onchange="this.form.submit()" class="status-select bg-slate-950/50 border border-slate-700/50 text-slate-300 text-xs rounded-xl px-3 py-1.5 font-semibold focus:border-indigo-500 focus:ring-0 outline-none w-full">
                                        <option value="new" {{ $lead->status === 'new' ? 'selected' : '' }}>New</option>
                                        <option value="contacted" {{ $lead->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                                        <option value="converted" {{ $lead->status === 'converted' ? 'selected' : '' }}>Converted</option>
                                        <option value="lost" {{ $lead->status === 'lost' ? 'selected' : '' }}>Lost</option>
                                    </select>
                                </div>
                                <button type="submit" class="px-3 py-2 bg-indigo-600 hover:bg-indigo-500 rounded-xl text-slate-50 transition-all text-xs font-semibold flex items-center gap-1.5 shadow-md shadow-indigo-600/10 active:scale-95 shrink-0" title="Save Status & Notes">
                                    <i class="fa-solid fa-floppy-disk text-[10px]"></i> Save
                                </button>
                            </div>
                            <div class="relative group">
                                <span class="absolute left-3 top-2.5 text-slate-500 group-focus-within:text-indigo-400 transition-colors pointer-events-none">
                                    <i class="fa-regular fa-comment-dots text-xs"></i>
                                </span>
                                <textarea name="notes" rows="2" class="block w-full pl-9 pr-3 py-2 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/20 rounded-xl text-slate-300 placeholder-slate-600 text-xs outline-none transition-all resize-none" placeholder="Enter follow-up logs...">{{ $lead->notes }}</textarea>
                            </div>
                        </form>

                        <!-- Action helper and Delete button -->
                        <div class="mt-2.5 flex items-center justify-between px-1">
                            <span class="text-[10px] text-slate-500 flex items-center gap-1.5">
                                @if($lead->notes)
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span class="text-slate-400">Has log notes</span>
                                @else
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-600"></span>
                                    <span class="text-slate-500">No notes yet</span>
                                @endif
                            </span>
                            <form action="{{ route('admin.leads.delete', $lead->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this lead?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[10px] font-semibold text-rose-500 hover:text-rose-400 transition-colors flex items-center gap-1">
                                    <i class="fa-solid fa-trash-can text-[9px]"></i> Delete Lead
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-12 px-4 text-center text-slate-500">
                        <i class="fa-solid fa-envelope-open text-4xl block mb-2"></i>
                        No leads matched these settings in the database.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $leads->links() }}
    </div>
</div>
@endsection
