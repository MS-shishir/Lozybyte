@extends('admin.layouts.app')

@section('page_title', 'Manage Services')

@section('content')

{{-- Auto-dismiss Toast --}}
@if(session('success'))
<div id="toast-success"
     class="fixed top-6 right-6 z-[9999] flex items-center gap-3 px-5 py-3.5 rounded-2xl border text-sm font-semibold shadow-2xl
            bg-emerald-500/10 border-emerald-500/30 text-emerald-400
            translate-x-0 opacity-100 transition-all duration-500">
    <i class="fa-solid fa-circle-check text-base"></i>
    <span>{{ session('success') }}</span>
    <button onclick="dismissToast()" class="ml-2 text-emerald-400/60 hover:text-emerald-300 transition-colors">
        <i class="fa-solid fa-xmark text-xs"></i>
    </button>
    {{-- Progress bar --}}
    <div id="toast-progress" class="absolute bottom-0 left-0 h-[3px] bg-emerald-400/50 rounded-b-2xl w-full" style="animation: shrink 2s linear forwards;"></div>
</div>
<script>
    function dismissToast() {
        const toast = document.getElementById('toast-success');
        if (!toast) return;
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(120%)';
        setTimeout(() => toast.remove(), 500);
    }
    // Auto dismiss after 2 seconds
    setTimeout(dismissToast, 2000);
</script>
<style>
    @keyframes shrink {
        from { width: 100%; }
        to   { width: 0%; }
    }
    #toast-success {
        animation: slideIn 0.4s cubic-bezier(0.16,1,0.3,1) forwards;
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(120%); }
        to   { opacity: 1; transform: translateX(0); }
    }
</style>
@endif


<div class="flex items-center justify-between mb-8">
    <h3 class="text-lg font-bold text-slate-100"><i class="fa-solid fa-screwdriver-wrench text-indigo-500 mr-2"></i> Service Explorer Cards</h3>
    <button onclick="toggleModal('modal-add-service')" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-slate-50 text-xs font-semibold rounded-xl transition-all shadow-[0_0_15px_rgba(99,102,241,0.2)]">
        <i class="fa-solid fa-plus mr-1"></i> Add Service Card
    </button>
</div>

{{-- Services Grid --}}
@php $serviceDataMap = []; @endphp
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    @forelse($services as $service)
    @php
        $titleEn = is_array($service->title) ? ($service->title['en'] ?? '') : $service->title;
        $titleBn = is_array($service->title) ? ($service->title['bn'] ?? '') : '';
        $descEn  = is_array($service->description) ? ($service->description['en'] ?? '') : $service->description;
        $descBn  = is_array($service->description) ? ($service->description['bn'] ?? '') : '';
        $detailsEn = is_array($service->details) ? ($service->details['en'] ?? '') : $service->details;
        $detailsBn = is_array($service->details) ? ($service->details['bn'] ?? '') : '';
        $caseEn  = is_array($service->case_result) ? ($service->case_result['en'] ?? '') : $service->case_result;
        $caseBn  = is_array($service->case_result) ? ($service->case_result['bn'] ?? '') : '';
        $featEn  = is_array($service->features) ? ($service->features['en'] ?? []) : [];
        $featBn  = is_array($service->features) ? ($service->features['bn'] ?? []) : [];
        $stepsEn = is_array($service->process_steps) ? ($service->process_steps['en'] ?? []) : [];
        $stepsBn = is_array($service->process_steps) ? ($service->process_steps['bn'] ?? []) : [];
    @endphp
    <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl p-6 flex flex-col justify-between shadow-lg" style="border-left: 3px solid {{ $service->color ?? '#6366f1' }}">
        <div>
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-sm font-bold" style="background-color: {{ $service->color ?? '#6366f1' }}22; color: {{ $service->color ?? '#6366f1' }}">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-100">{{ $titleEn }}</h4>
                        <span class="text-[10px] text-slate-500 font-mono">slug: {{ $service->slug }} &bull; order: {{ $service->sort_order }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold rounded-full {{ $service->status ? 'bg-emerald-500/10 text-emerald-400' : 'bg-slate-800 text-slate-500' }}">
                        {{ $service->status ? 'Active' : 'Draft' }}
                    </span>
                </div>
            </div>

            {{-- Color + Icon --}}
            <div class="flex items-center gap-3 mb-3">
                <div class="w-5 h-5 rounded-full border border-slate-600" style="background: {{ $service->color ?? '#6366f1' }}"></div>
                <span class="text-[10px] text-slate-500 font-mono">{{ $service->color ?? '#6366f1' }}</span>
                <span class="text-[10px] text-slate-500 ml-2"><i class="fa-solid fa-icons mr-1"></i>{{ $service->icon }}</span>
            </div>

            <p class="text-xs text-slate-400 leading-relaxed mb-3">{{ $descEn }}</p>

            {{-- Meta row --}}
            <div class="grid grid-cols-2 gap-2 mb-3">
                <div class="bg-slate-950/40 rounded-xl p-2.5 text-center">
                    <div class="text-[9px] text-slate-500 uppercase tracking-wider mb-0.5">Timeline</div>
                    <div class="text-xs font-bold text-slate-200">{{ $service->timeline ?: '—' }}</div>
                </div>
                <div class="bg-slate-950/40 rounded-xl p-2.5 text-center">
                    <div class="text-[9px] text-slate-500 uppercase tracking-wider mb-0.5">Starting At</div>
                    <div class="text-xs font-bold text-slate-200">{{ $service->starting_price ?: '—' }}</div>
                </div>
            </div>

            {{-- Features --}}
            @if(!empty($featEn))
            <div class="mb-3">
                <div class="text-[9px] text-slate-500 uppercase tracking-wider mb-1">Features (EN)</div>
                <div class="flex flex-wrap gap-1.5">
                    @foreach(array_slice($featEn, 0, 5) as $f)
                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-slate-800 text-slate-300">{{ $f }}</span>
                    @endforeach
                    @if(count($featEn) > 5)<span class="text-[10px] text-slate-500">+{{ count($featEn) - 5 }} more</span>@endif
                </div>
            </div>
            @endif

            {{-- Case Result --}}
            @if($caseEn)
            <div class="p-2.5 rounded-xl mb-3" style="background: {{ $service->color ?? '#6366f1' }}11; border: 1px solid {{ $service->color ?? '#6366f1' }}22">
                <div class="text-[9px] font-bold uppercase tracking-wider mb-0.5" style="color: {{ $service->color ?? '#6366f1' }}">Real Client Result</div>
                <div class="text-xs text-slate-200 font-semibold">{{ $caseEn }}</div>
            </div>
            @endif
        </div>

        <div class="flex items-center justify-end mt-4 pt-4 border-t border-slate-700/50 gap-2">
            <button onclick="openEdit({{ $service->id }})" class="px-3 py-1.5 bg-indigo-600/10 hover:bg-indigo-600 text-indigo-400 hover:text-slate-50 text-[10px] font-bold rounded-xl transition-all">
                <i class="fa-solid fa-pen mr-1"></i> Edit
            </button>
            <form action="{{ route('admin.services.delete', $service->id) }}" method="POST" onsubmit="return confirm('Delete this service?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-1.5 bg-rose-600/10 hover:bg-rose-600 text-rose-400 hover:text-slate-50 text-[10px] font-bold rounded-xl transition-all">
                    <i class="fa-solid fa-trash mr-1"></i> Delete
                </button>
            </form>
        </div>
    </div>

    @php
        $serviceDataMap[$service->id] = [
            'id'               => $service->id,
            'slug'             => $service->slug,
            'icon'             => $service->icon,
            'color'            => $service->color ?? '#6366f1',
            'glow_color'       => $service->glow_color ?? 'rgba(99,102,241,0.2)',
            'title_en'         => $titleEn,
            'title_bn'         => $titleBn,
            'desc_en'          => $descEn,
            'desc_bn'          => $descBn,
            'details_en'       => $detailsEn,
            'details_bn'       => $detailsBn,
            'timeline'         => $service->timeline,
            'starting_price'   => $service->starting_price,
            'case_result_en'   => $caseEn,
            'case_result_bn'   => $caseBn,
            'features_en'      => implode("\n", is_array($featEn) ? $featEn : []),
            'features_bn'      => implode("\n", is_array($featBn) ? $featBn : []),
            'process_steps_en' => implode("\n", is_array($stepsEn) ? $stepsEn : []),
            'process_steps_bn' => implode("\n", is_array($stepsBn) ? $stepsBn : []),
            'sort_order'       => (int)($service->sort_order ?? 0),
            'status'           => (bool)$service->status,
        ];
    @endphp

    @empty
    <div class="col-span-2 py-16 text-center text-slate-500 bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl">
        <i class="fa-solid fa-screwdriver-wrench text-4xl block mb-3"></i>
        <p class="text-sm">No services found. Click <strong class="text-indigo-400">Add Service Card</strong> to get started.</p>
    </div>
    @endforelse
</div>

<script>window._serviceData = {!! json_encode($serviceDataMap, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!};</script>

{!! $services->links() !!}

<!-- ADD MODAL -->
<div id="modal-add-service" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-start justify-center p-6 overflow-y-auto hidden">
    <div class="bg-[#141C2F] border border-slate-700/50 rounded-3xl w-full max-w-3xl shadow-2xl my-6">
        <div class="h-16 flex items-center justify-between px-6 border-b border-slate-700/50 sticky top-0 bg-[#141C2F] z-10 rounded-t-3xl">
            <h4 class="font-bold text-slate-50 flex items-center gap-2">
                <i class="fa-solid fa-screwdriver-wrench text-indigo-500"></i> Add New Service Card
            </h4>
            <button onclick="toggleModal('modal-add-service')" class="text-slate-400 hover:text-slate-200 text-lg"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form action="{{ route('admin.services.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            {{-- Row 1: Slug, Icon, Sort Order, Status --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label class="form-label">Slug <span class="text-rose-400">*</span></label>
                    <input type="text" name="slug" placeholder="e.g. website, saas, pos" required class="form-input">
                </div>
                <div>
                    <label class="form-label">Lucide Icon Name</label>
                    <input type="text" name="icon" placeholder="Globe, Cloud, ShoppingCart" class="form-input">
                    <p class="text-[10px] text-slate-500 mt-1"><a href="https://lucide.dev/icons/" target="_blank" class="text-indigo-400 underline">Browse icons ↗</a></p>
                </div>
                <div>
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" value="0" min="0" class="form-input">
                </div>
                <div class="flex flex-col justify-end pb-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="status" id="add-status" class="w-4 h-4 accent-indigo-500" checked>
                        <span class="text-sm font-semibold text-slate-300">Active</span>
                    </label>
                </div>
            </div>

            {{-- Row 2: Color + Glow --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Card Color (hex)</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="color" value="#6366f1" class="w-10 h-10 rounded-xl border border-slate-700/50 bg-transparent cursor-pointer p-0.5">
                        <input type="text" name="color_text" placeholder="#6366f1" oninput="syncColor(this,'color','add')" class="form-input flex-1" value="#6366f1">
                    </div>
                </div>
                <div>
                    <label class="form-label">Glow Color (rgba)</label>
                    <input type="text" name="glow_color" placeholder="rgba(99,102,241,0.2)" class="form-input" value="rgba(99,102,241,0.2)">
                </div>
            </div>

            {{-- Row 3: Titles --}}
            <div class="border-t border-slate-700/40 pt-4">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-3">Titles</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Card Label (EN) <span class="text-rose-400">*</span></label>
                        <input type="text" name="title_en" placeholder="Need a Website" required class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Card Label (BN)</label>
                        <input type="text" name="title_bn" placeholder="ওয়েবসাইট দরকার" class="form-input">
                    </div>
                </div>
            </div>

            {{-- Row 4: Description --}}
            <div class="border-t border-slate-700/40 pt-4">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-3">Short Description (shown in detail panel)</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Description (EN)</label>
                        <textarea name="desc_en" rows="3" placeholder="We design and build..." class="form-input"></textarea>
                    </div>
                    <div>
                        <label class="form-label">Description (BN)</label>
                        <textarea name="desc_bn" rows="3" placeholder="আমরা ডিজাইন ও তৈরি করি..." class="form-input"></textarea>
                    </div>
                </div>
            </div>

            {{-- Row 5: Timeline + Price --}}
            <div class="border-t border-slate-700/40 pt-4">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-3">Pricing & Timeline</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Timeline</label>
                        <input type="text" name="timeline" placeholder="2–4 weeks" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Starting Price</label>
                        <input type="text" name="starting_price" placeholder="$500" class="form-input">
                    </div>
                </div>
            </div>

            {{-- Row 6: Case Result --}}
            <div class="border-t border-slate-700/40 pt-4">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-3">Real Client Result</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Result (EN)</label>
                        <input type="text" name="case_result_en" placeholder="+65% more enquiries after launch" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Result (BN)</label>
                        <input type="text" name="case_result_bn" placeholder="লঞ্চের পর +৬৫% বেশি এনকোয়ারি" class="form-input">
                    </div>
                </div>
            </div>

            {{-- Row 7: Features --}}
            <div class="border-t border-slate-700/40 pt-4">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Features (What's Included)</p>
                <p class="text-[10px] text-slate-500 mb-3">Enter one feature per line</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Features (EN)</label>
                        <textarea name="features_en" rows="5" placeholder="Responsive Design&#10;SEO Optimized&#10;Fast Loading (< 2s)&#10;CMS Integration&#10;Analytics Dashboard" class="form-input font-mono text-xs"></textarea>
                    </div>
                    <div>
                        <label class="form-label">Features (BN)</label>
                        <textarea name="features_bn" rows="5" placeholder="রেসপন্সিভ ডিজাইন&#10;এসইও অপ্টিমাইজড&#10;দ্রুত লোডিং&#10;সিএমএস ইন্টিগ্রেশন&#10;অ্যানালিটিক্স ড্যাশবোর্ড" class="form-input font-mono text-xs"></textarea>
                    </div>
                </div>
            </div>

            {{-- Row 8: Process Steps --}}
            <div class="border-t border-slate-700/40 pt-4">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Our Process Steps</p>
                <p class="text-[10px] text-slate-500 mb-3">Enter one step per line</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Steps (EN)</label>
                        <textarea name="process_steps_en" rows="4" placeholder="Discovery & Scoping&#10;Design & Prototype&#10;Development & QA&#10;Launch & Support" class="form-input font-mono text-xs"></textarea>
                    </div>
                    <div>
                        <label class="form-label">Steps (BN)</label>
                        <textarea name="process_steps_bn" rows="4" placeholder="আবিষ্কার ও পরিকল্পনা&#10;ডিজাইন ও প্রোটোটাইপ&#10;ডেভেলপমেন্ট ও QA&#10;লঞ্চ ও সাপোর্ট" class="form-input font-mono text-xs"></textarea>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-700/50">
                <button type="button" onclick="toggleModal('modal-add-service')" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl transition-all">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-slate-50 text-xs font-semibold rounded-xl transition-all shadow-[0_0_15px_rgba(99,102,241,0.25)]">
                    <i class="fa-solid fa-plus mr-1"></i> Add Service
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ======================== EDIT MODAL ======================== --}}
<div id="modal-edit-service" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-start justify-center p-6 overflow-y-auto hidden">
    <div class="bg-[#141C2F] border border-slate-700/50 rounded-3xl w-full max-w-3xl shadow-2xl my-6">
        <div class="h-16 flex items-center justify-between px-6 border-b border-slate-700/50 sticky top-0 bg-[#141C2F] z-10 rounded-t-3xl">
            <h4 class="font-bold text-slate-50 flex items-center gap-2">
                <i class="fa-solid fa-pen text-amber-400"></i> Edit Service Card
            </h4>
            <button onclick="toggleModal('modal-edit-service')" class="text-slate-400 hover:text-slate-200 text-lg"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form id="edit-service-form" action="" method="POST" class="p-6 space-y-5">
            @csrf
            @method('POST')
            <input type="hidden" name="_method" value="POST">

            {{-- Row 1 --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label class="form-label">Slug <span class="text-rose-400">*</span></label>
                    <input type="text" name="slug" id="edit-slug" required class="form-input">
                </div>
                <div>
                    <label class="form-label">Lucide Icon Name</label>
                    <input type="text" name="icon" id="edit-icon" placeholder="Globe, Cloud, ShoppingCart" class="form-input">
                </div>
                <div>
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" id="edit-sort_order" min="0" class="form-input">
                </div>
                <div class="flex flex-col justify-end pb-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="status" id="edit-status" class="w-4 h-4 accent-indigo-500">
                        <span class="text-sm font-semibold text-slate-300">Active</span>
                    </label>
                </div>
            </div>

            {{-- Row 2: Color --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Card Color (hex)</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="color" id="edit-color-picker" class="w-10 h-10 rounded-xl border border-slate-700/50 bg-transparent cursor-pointer p-0.5">
                        <input type="text" id="edit-color" name="color_text" placeholder="#6366f1" oninput="syncColor(this,'edit-color-picker','edit')" class="form-input flex-1">
                    </div>
                </div>
                <div>
                    <label class="form-label">Glow Color (rgba)</label>
                    <input type="text" name="glow_color" id="edit-glow_color" placeholder="rgba(99,102,241,0.2)" class="form-input">
                </div>
            </div>

            {{-- Titles --}}
            <div class="border-t border-slate-700/40 pt-4">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-3">Titles</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Card Label (EN) <span class="text-rose-400">*</span></label>
                        <input type="text" name="title_en" id="edit-title_en" required class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Card Label (BN)</label>
                        <input type="text" name="title_bn" id="edit-title_bn" class="form-input">
                    </div>
                </div>
            </div>

            {{-- Description --}}
            <div class="border-t border-slate-700/40 pt-4">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-3">Short Description</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Description (EN)</label>
                        <textarea name="desc_en" id="edit-desc_en" rows="3" class="form-input"></textarea>
                    </div>
                    <div>
                        <label class="form-label">Description (BN)</label>
                        <textarea name="desc_bn" id="edit-desc_bn" rows="3" class="form-input"></textarea>
                    </div>
                </div>
            </div>

            {{-- Timeline + Price --}}
            <div class="border-t border-slate-700/40 pt-4">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-3">Pricing & Timeline</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Timeline</label>
                        <input type="text" name="timeline" id="edit-timeline" placeholder="2–4 weeks" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Starting Price</label>
                        <input type="text" name="starting_price" id="edit-starting_price" placeholder="$500" class="form-input">
                    </div>
                </div>
            </div>

            {{-- Case Result --}}
            <div class="border-t border-slate-700/40 pt-4">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-3">Real Client Result</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Result (EN)</label>
                        <input type="text" name="case_result_en" id="edit-case_result_en" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Result (BN)</label>
                        <input type="text" name="case_result_bn" id="edit-case_result_bn" class="form-input">
                    </div>
                </div>
            </div>

            {{-- Features --}}
            <div class="border-t border-slate-700/40 pt-4">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Features (one per line)</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Features (EN)</label>
                        <textarea name="features_en" id="edit-features_en" rows="5" class="form-input font-mono text-xs"></textarea>
                    </div>
                    <div>
                        <label class="form-label">Features (BN)</label>
                        <textarea name="features_bn" id="edit-features_bn" rows="5" class="form-input font-mono text-xs"></textarea>
                    </div>
                </div>
            </div>

            {{-- Process Steps --}}
            <div class="border-t border-slate-700/40 pt-4">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Process Steps (one per line)</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Steps (EN)</label>
                        <textarea name="process_steps_en" id="edit-process_steps_en" rows="4" class="form-input font-mono text-xs"></textarea>
                    </div>
                    <div>
                        <label class="form-label">Steps (BN)</label>
                        <textarea name="process_steps_bn" id="edit-process_steps_bn" rows="4" class="form-input font-mono text-xs"></textarea>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-700/50">
                <button type="button" onclick="toggleModal('modal-edit-service')" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl transition-all">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-semibold rounded-xl transition-all shadow-[0_0_15px_rgba(245,158,11,0.25)]">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.form-label { display: block; font-size: 10px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px; }
.form-input { display: block; width: 100%; padding: 8px 14px; background: rgba(2,6,23,0.5); border: 1px solid rgba(51,65,85,0.7); border-radius: 12px; color: #e2e8f0; font-size: 12px; outline: none; transition: border-color 0.2s; }
.form-input:focus { border-color: #6366f1; }
textarea.form-input { resize: vertical; }
</style>

<script>
function toggleModal(id) {
    document.getElementById(id).classList.toggle('hidden');
}

function openEdit(id) {
    const d = window._serviceData[id];
    if (!d) return;

    // Set form action
    document.getElementById('edit-service-form').action = `/admin/services/${id}`;

    // Fill all fields
    const set = (fieldId, val) => {
        const el = document.getElementById(fieldId);
        if (!el) return;
        if (el.type === 'checkbox') el.checked = val;
        else el.value = val ?? '';
    };

    set('edit-slug', d.slug);
    set('edit-icon', d.icon);
    set('edit-sort_order', d.sort_order);
    set('edit-status', d.status);
    set('edit-color-picker', d.color);
    set('edit-color', d.color);
    set('edit-glow_color', d.glow_color);
    set('edit-title_en', d.title_en);
    set('edit-title_bn', d.title_bn);
    set('edit-desc_en', d.desc_en);
    set('edit-desc_bn', d.desc_bn);
    set('edit-timeline', d.timeline);
    set('edit-starting_price', d.starting_price);
    set('edit-case_result_en', d.case_result_en);
    set('edit-case_result_bn', d.case_result_bn);
    set('edit-features_en', d.features_en);
    set('edit-features_bn', d.features_bn);
    set('edit-process_steps_en', d.process_steps_en);
    set('edit-process_steps_bn', d.process_steps_bn);

    toggleModal('modal-edit-service');
}

function syncColor(inputEl, pickerId, prefix) {
    const picker = document.getElementById(pickerId) || document.querySelector(`[name="color"]`);
    if (picker && inputEl.value.startsWith('#') && inputEl.value.length >= 4) {
        picker.value = inputEl.value;
    }
}
</script>

@endsection
