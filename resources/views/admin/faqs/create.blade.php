@extends('admin.layouts.app')

@section('page_title', 'Add New FAQ')

@section('content')
<div class="max-w-4xl mx-auto p-4 md:p-8">

    {{-- Back Link & Header --}}
    <div class="mb-8">
        <a href="{{ route('admin.faqs.index') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-indigo-400 font-semibold mb-4 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Back to FAQs List
        </a>
        <h1 class="text-2xl font-extrabold text-slate-50 tracking-tight flex items-center gap-2">
            <i class="fa-solid fa-plus-circle text-indigo-500"></i> Add New FAQ
        </h1>
        <p class="text-slate-400 text-xs mt-1">Create a localized question and answer item for the website.</p>
    </div>

    {{-- Form Container --}}
    <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-2xl shadow-xl p-6 md:p-8">
        <form action="{{ route('admin.faqs.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Grid for side-by-side localization --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- English Question --}}
                <div>
                    <label class="block text-slate-400 text-xs font-semibold uppercase tracking-wider mb-2">Question (EN) <span class="text-rose-500">*</span></label>
                    <input type="text" name="question_en" 
                           class="block w-full px-4 py-2.5 bg-[#0c1322] border border-slate-700/50 focus:border-indigo-500/50 rounded-xl text-slate-200 text-sm outline-none transition-colors" 
                           placeholder="e.g. What is Lozybyte?" required>
                </div>

                {{-- Bengali Question --}}
                <div>
                    <label class="block text-slate-400 text-xs font-semibold uppercase tracking-wider mb-2">Question (BN)</label>
                    <input type="text" name="question_bn" 
                           class="block w-full px-4 py-2.5 bg-[#0c1322] border border-slate-700/50 focus:border-indigo-500/50 rounded-xl text-slate-200 text-sm outline-none transition-colors" 
                           placeholder="উদাঃ লজিবাইট কি?">
                </div>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- English Answer --}}
                <div>
                    <label class="block text-slate-400 text-xs font-semibold uppercase tracking-wider mb-2">Answer (EN) <span class="text-rose-500">*</span></label>
                    <textarea name="answer_en" rows="5" 
                              class="block w-full px-4 py-2.5 bg-[#0c1322] border border-slate-700/50 focus:border-indigo-500/50 rounded-xl text-slate-200 text-sm outline-none transition-colors resize-y" 
                              placeholder="Describe the answer in English..." required></textarea>
                </div>

                {{-- Bengali Answer --}}
                <div>
                    <label class="block text-slate-400 text-xs font-semibold uppercase tracking-wider mb-2">Answer (BN)</label>
                    <textarea name="answer_bn" rows="5" 
                              class="block w-full px-4 py-2.5 bg-[#0c1322] border border-slate-700/50 focus:border-indigo-500/50 rounded-xl text-slate-200 text-sm outline-none transition-colors resize-y" 
                              placeholder="বাংলায় উত্তরটি লিখুন..."></textarea>
                </div>

            </div>

            {{-- Bottom row with sort order and status toggle --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 border-t border-slate-800/60 pt-6 mt-8">
                
                {{-- Sort Order --}}
                <div class="flex items-center gap-3">
                    <label class="text-slate-400 text-xs font-semibold uppercase tracking-wider shrink-0">Sort Order</label>
                    <input type="number" name="sort_order" 
                           class="w-24 px-3 py-2 bg-[#0c1322] border border-slate-700/50 focus:border-indigo-500/50 rounded-lg text-slate-200 text-sm font-mono text-center outline-none transition-colors" 
                           value="0" min="0">
                </div>

                {{-- Status switch toggle --}}
                <div class="flex items-center gap-3">
                    <span class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Active Status</span>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                        <input type="checkbox" name="status" class="sr-only peer" value="active" checked>
                        <div class="w-10 h-5 bg-rose-500 rounded-full transition-all duration-300
                                    peer-checked:bg-emerald-500
                                    after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                    after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all
                                    peer-checked:after:translate-x-5 shadow-inner"></div>
                    </label>
                </div>

            </div>

            {{-- Action buttons --}}
            <div class="flex items-center justify-end gap-3 mt-8">
                <a href="{{ route('admin.faqs.index') }}" 
                   class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 active:bg-slate-650 text-slate-350 text-xs font-bold rounded-xl transition-all">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 bg-white hover:bg-slate-200 active:bg-slate-300 text-black text-xs font-bold rounded-xl shadow-lg transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-check text-[11px] text-emerald-600"></i> Save FAQ
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
