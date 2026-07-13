@extends('admin.layouts.app')

@section('page_title', 'Manage Industries We Serve')

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

<div class="flex items-center justify-between mb-8 text-slate-100">
    <h3 class="text-lg font-bold"><i class="fa-solid fa-building-user text-indigo-500 mr-2"></i> Industries We Serve</h3>
    <button onclick="toggleModal('modal-add-industry')" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-slate-50 text-xs font-semibold rounded-xl transition-all shadow-[0_0_15px_rgba(99,102,241,0.2)]">
        <i class="fa-solid fa-plus mr-1"></i> Add Industry Card
    </button>
</div>

<!-- Industries Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    @php $industryDataMap = []; @endphp
    @forelse($industries as $industry)
    @php
        $titleEn = is_array($industry->title) ? ($industry->title['en'] ?? '') : $industry->title;
        $titleBn = is_array($industry->title) ? ($industry->title['bn'] ?? '') : '';
        $descEn  = is_array($industry->description) ? ($industry->description['en'] ?? '') : $industry->description;
        $descBn  = is_array($industry->description) ? ($industry->description['bn'] ?? '') : '';
    @endphp
    <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl p-6 flex flex-col justify-between shadow-lg">
        <div>
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600/10 text-indigo-400 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-100">{{ $titleEn }}</h4>
                        <span class="text-[10px] text-slate-500 font-mono">slug: {{ $industry->slug }} &bull; order: {{ $industry->sort_order }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold rounded-full {{ $industry->status ? 'bg-emerald-500/10 text-emerald-400' : 'bg-slate-800 text-slate-500' }}">
                        {{ $industry->status ? 'Active' : 'Draft' }}
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2 mb-3">
                <span class="text-[10px] text-slate-500"><i class="fa-solid fa-icons mr-1"></i>Icon name: {{ $industry->icon }}</span>
            </div>

            <p class="text-xs text-slate-400 leading-relaxed mb-1"><strong class="text-slate-300">EN:</strong> {{ $descEn }}</p>
            @if($descBn)
            <p class="text-xs text-slate-400 leading-relaxed"><strong class="text-slate-300">BN:</strong> {{ $descBn }}</p>
            @endif
        </div>

        <div class="flex items-center justify-end mt-4 pt-4 border-t border-slate-700/50 gap-2">
            <button onclick="openEdit({{ $industry->id }})" class="px-3 py-1.5 bg-indigo-600/10 hover:bg-indigo-600 text-indigo-400 hover:text-slate-50 text-[10px] font-bold rounded-xl transition-all">
                <i class="fa-solid fa-pen mr-1"></i> Edit
            </button>
            <form action="{{ route('admin.industries.delete', $industry->id) }}" method="POST" onsubmit="return confirm('Delete this industry?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-1.5 bg-rose-600/10 hover:bg-rose-600 text-rose-400 hover:text-slate-50 text-[10px] font-bold rounded-xl transition-all">
                    <i class="fa-solid fa-trash mr-1"></i> Delete
                </button>
            </form>
        </div>
    </div>

    @php
        $industryDataMap[$industry->id] = [
            'id' => $industry->id,
            'slug' => $industry->slug,
            'icon' => $industry->icon,
            'title_en' => $titleEn,
            'title_bn' => $titleBn,
            'desc_en' => $descEn,
            'desc_bn' => $descBn,
            'sort_order' => $industry->sort_order ?? 0,
            'status' => (bool)$industry->status,
        ];
    @endphp

    @empty
    <div class="col-span-2 py-16 text-center text-slate-500 bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl">
        <i class="fa-solid fa-building-user text-4xl block mb-3"></i>
        <p class="text-sm">No industries registered yet. Click <strong class="text-indigo-400">Add Industry Card</strong> to get started.</p>
    </div>
    @endforelse
</div>

<script>window._industryData = {!! json_encode($industryDataMap, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!};</script>

{!! $industries->links() !!}

{{-- ======================== ADD MODAL ======================== --}}
<div id="modal-add-industry" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-start justify-center p-6 overflow-y-auto hidden">
    <div class="bg-[#141C2F] border border-slate-700/50 rounded-3xl w-full max-w-2xl shadow-2xl my-6">
        <div class="h-16 flex items-center justify-between px-6 border-b border-slate-700/50 sticky top-0 bg-[#141C2F] z-10 rounded-t-3xl">
            <h4 class="font-bold text-slate-50 flex items-center gap-2">
                <i class="fa-solid fa-building-user text-indigo-500"></i> Add New Industry Card
            </h4>
            <button onclick="toggleModal('modal-add-industry')" class="text-slate-400 hover:text-slate-200 text-lg"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form action="{{ route('admin.industries.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            {{-- Slug, Icon, Sort Order, Status --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label class="form-label">Slug <span class="text-rose-400">*</span></label>
                    <input type="text" name="slug" placeholder="e.g. healthcare, retail" required class="form-input">
                </div>
                <div>
                    <label class="form-label">Lucide Icon Name</label>
                    <input type="text" name="icon" placeholder="Activity, Store, Truck" class="form-input" value="Building2">
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

            {{-- Label / Title EN + BN --}}
            <div class="border-t border-slate-700/40 pt-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Industry Label (EN) <span class="text-rose-400">*</span></label>
                        <input type="text" name="title_en" placeholder="Education" required class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Industry Label (BN)</label>
                        <input type="text" name="title_bn" placeholder="শিক্ষা খাত" class="form-input">
                    </div>
                </div>
            </div>

            {{-- Description EN + BN --}}
            <div class="border-t border-slate-700/40 pt-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Description (EN) <span class="text-rose-400">*</span></label>
                        <textarea name="desc_en" rows="3" placeholder="Custom ERP and portal architectures..." required class="form-input"></textarea>
                    </div>
                    <div>
                        <label class="form-label">Description (BN)</label>
                        <textarea name="desc_bn" rows="3" placeholder="আধুনিক স্কুলের জন্য কাস্টম ইআরপি..." class="form-input"></textarea>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-700/50">
                <button type="button" onclick="toggleModal('modal-add-industry')" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl transition-all">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-slate-50 text-xs font-semibold rounded-xl transition-all shadow-[0_0_15px_rgba(99,102,241,0.25)]">
                    <i class="fa-solid fa-plus mr-1"></i> Add Industry
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ======================== EDIT MODAL ======================== --}}
<div id="modal-edit-industry" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-start justify-center p-6 overflow-y-auto hidden">
    <div class="bg-[#141C2F] border border-slate-700/50 rounded-3xl w-full max-w-2xl shadow-2xl my-6">
        <div class="h-16 flex items-center justify-between px-6 border-b border-slate-700/50 sticky top-0 bg-[#141C2F] z-10 rounded-t-3xl">
            <h4 class="font-bold text-slate-50 flex items-center gap-2">
                <i class="fa-solid fa-pen text-amber-400"></i> Edit Industry Card
            </h4>
            <button onclick="toggleModal('modal-edit-industry')" class="text-slate-400 hover:text-slate-200 text-lg"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form id="edit-industry-form" action="" method="POST" class="p-6 space-y-5">
            @csrf
            @method('POST')
            <input type="hidden" name="_method" value="POST">

            {{-- Slug, Icon, Sort Order, Status --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label class="form-label">Slug <span class="text-rose-400">*</span></label>
                    <input type="text" name="slug" id="edit-slug" required class="form-input">
                </div>
                <div>
                    <label class="form-label">Lucide Icon Name</label>
                    <input type="text" name="icon" id="edit-icon" class="form-input">
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

            {{-- Label / Title EN + BN --}}
            <div class="border-t border-slate-700/40 pt-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Industry Label (EN) <span class="text-rose-400">*</span></label>
                        <input type="text" name="title_en" id="edit-title_en" required class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Industry Label (BN)</label>
                        <input type="text" name="title_bn" id="edit-title_bn" class="form-input">
                    </div>
                </div>
            </div>

            {{-- Description EN + BN --}}
            <div class="border-t border-slate-700/40 pt-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Description (EN) <span class="text-rose-400">*</span></label>
                        <textarea name="desc_en" id="edit-desc_en" rows="3" required class="form-input"></textarea>
                    </div>
                    <div>
                        <label class="form-label">Description (BN)</label>
                        <textarea name="desc_bn" id="edit-desc_bn" rows="3" class="form-input"></textarea>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-700/50">
                <button type="button" onclick="toggleModal('modal-edit-industry')" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl transition-all">Cancel</button>
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
    const d = window._industryData[id];
    if (!d) return;

    // Set form action
    document.getElementById('edit-industry-form').action = `/admin/industries/${id}`;

    // Fill fields
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
    set('edit-title_en', d.title_en);
    set('edit-title_bn', d.title_bn);
    set('edit-desc_en', d.desc_en);
    set('edit-desc_bn', d.desc_bn);

    toggleModal('modal-edit-industry');
}
</script>

@endsection
