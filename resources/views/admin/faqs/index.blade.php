@extends('admin.layouts.app')

@section('page_title', 'Frequently Asked Questions')

@section('content')
<div class="max-w-6xl mx-auto p-4 md:p-8">
    
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-50 tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-circle-question text-indigo-500"></i>
                Frequently Asked Questions
            </h1>
            <p class="text-slate-400 text-xs mt-1">Manage localized questions and answers shown on the public landing page.</p>
        </div>
        <a href="{{ route('admin.faqs.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-500 hover:bg-indigo-600 active:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-lg shadow-indigo-500/10 transition-colors">
            <i class="fa-solid fa-plus text-[10px]"></i> Add New FAQ
        </a>
    </div>
   

    {{-- Table Card --}}
    <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-2xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead>
                    <tr class="border-b border-slate-700/50 bg-[#0d1322]/50 text-slate-400 text-xs font-semibold uppercase tracking-wider">
                        <th class="py-4 px-6">Question (EN)</th>
                        <th class="py-4 px-6">Question (BN)</th>
                        <th class="py-4 px-6 text-center w-24">Sort Order</th>
                        <th class="py-4 px-6 text-center w-28">Status</th>
                        <th class="py-4 px-6 text-center w-36">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40">
                    @forelse($faqs as $faq)
                        <tr class="hover:bg-[#1c2742]/30 transition-colors">
                            <td class="py-4 px-6 font-medium text-slate-200">
                                {{ $faq->question['en'] ?? '' }}
                            </td>
                            <td class="py-4 px-6 text-slate-400 font-light">
                                {{ $faq->question['bn'] ?? '-' }}
                            </td>
                            <td class="py-4 px-6 text-center text-slate-300 font-mono text-xs">
                                {{ $faq->sort_order }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($faq->status)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[10px] font-medium">
                                        <span class="w-1 h-1 rounded-full bg-emerald-400 animate-pulse"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-rose-500/10 text-rose-500 border border-rose-500/20 text-[10px] font-medium">
                                        <span class="w-1 h-1 rounded-full bg-rose-500"></span> Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.faqs.edit', $faq) }}" 
                                       class="w-8 h-8 rounded-lg bg-indigo-500/10 border border-indigo-500/20 hover:bg-indigo-500 hover:text-white text-indigo-400 flex items-center justify-center transition-all"
                                       title="Edit FAQ">
                                        <i class="fa-solid fa-pen-to-square text-[11px]"></i>
                                    </a>
                                    <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" class="inline" onsubmit="return confirm('Delete this FAQ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="w-8 h-8 rounded-lg bg-rose-500/10 border border-rose-500/20 hover:bg-rose-500 hover:text-white text-rose-400 flex items-center justify-center transition-all"
                                                title="Delete FAQ">
                                            <i class="fa-solid fa-trash-can text-[11px]"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 px-6 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="fa-solid fa-circle-info text-2xl opacity-40"></i>
                                    <p class="text-xs">No FAQs created yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($faqs->hasPages())
            <div class="px-6 py-4 border-t border-slate-700/50 bg-[#0d1322]/20">
                {{ $faqs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
