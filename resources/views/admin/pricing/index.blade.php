@extends('admin.layouts.app')

@section('content')
<div class="p-6 md:p-8 max-w-7xl mx-auto">

    {{-- Toast Notification --}}
    @if(session('success'))
    <div id="toast-success"
        class="fixed top-6 right-6 z-[9999] flex items-center gap-3 px-5 py-4 rounded-2xl bg-emerald-500 text-white text-sm font-semibold shadow-2xl shadow-emerald-500/30 translate-x-0 transition-all duration-500"
        style="animation: slideInRight .4s ease;">
        <i class="fa-solid fa-circle-check text-lg"></i>
        <span>{{ session('success') }}</span>
        <div class="absolute bottom-0 left-0 h-0.5 bg-white/40 rounded-full toast-progress" style="width:100%"></div>
    </div>
    <style>
        @keyframes slideInRight { from { transform:translateX(120%); opacity:0; } to { transform:translateX(0); opacity:1; } }
        .toast-progress { animation: shrinkWidth 2s linear forwards; }
        @keyframes shrinkWidth { from { width:100%; } to { width:0%; } }
    </style>
    <script>setTimeout(() => { const t = document.getElementById('toast-success'); if(t){ t.style.transform='translateX(120%)'; t.style.opacity='0'; setTimeout(()=>t.remove(),500); } }, 2000);</script>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white">Billing Plans & Pricing</h1>
            <p class="text-slate-400 text-sm mt-1">Manage your monthly, yearly and lifetime pricing plans</p>
        </div>
        <button onclick="openAddModal()"
            class="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-xl transition-all shadow-lg shadow-indigo-600/25">
            <i class="fa-solid fa-plus"></i> Add Plan
        </button>
    </div>

    {{-- Plans Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($plans as $plan)
        @php
            $nameEn = is_array($plan->getRawOriginal('name')) ? ($plan->getRawOriginal('name')['en'] ?? '') : (json_decode($plan->getRawOriginal('name'), true)['en'] ?? '');
            $nameBn = is_array($plan->getRawOriginal('name')) ? ($plan->getRawOriginal('name')['bn'] ?? '') : (json_decode($plan->getRawOriginal('name'), true)['bn'] ?? '');
            $catEn  = is_array($plan->getRawOriginal('category')) ? ($plan->getRawOriginal('category')['en'] ?? '') : (json_decode($plan->getRawOriginal('category'), true)['en'] ?? '');
            $catBn  = is_array($plan->getRawOriginal('category')) ? ($plan->getRawOriginal('category')['bn'] ?? '') : (json_decode($plan->getRawOriginal('category'), true)['bn'] ?? '');
            $tagEn  = is_array($plan->getRawOriginal('tagline')) ? ($plan->getRawOriginal('tagline')['en'] ?? '') : (json_decode($plan->getRawOriginal('tagline'), true)['en'] ?? '');
            $tagBn  = is_array($plan->getRawOriginal('tagline')) ? ($plan->getRawOriginal('tagline')['bn'] ?? '') : (json_decode($plan->getRawOriginal('tagline'), true)['bn'] ?? '');
            $badgeEn= is_array($plan->getRawOriginal('badge')) ? ($plan->getRawOriginal('badge')['en'] ?? '') : (json_decode($plan->getRawOriginal('badge'), true)['en'] ?? '');
            $badgeBn= is_array($plan->getRawOriginal('badge')) ? ($plan->getRawOriginal('badge')['bn'] ?? '') : (json_decode($plan->getRawOriginal('badge'), true)['bn'] ?? '');
            $descEn = is_array($plan->getRawOriginal('description')) ? ($plan->getRawOriginal('description')['en'] ?? '') : (json_decode($plan->getRawOriginal('description'), true)['en'] ?? '');
            $descBn = is_array($plan->getRawOriginal('description')) ? ($plan->getRawOriginal('description')['bn'] ?? '') : (json_decode($plan->getRawOriginal('description'), true)['bn'] ?? '');
            $featRaw= $plan->getRawOriginal('features');
            $featArr= is_array($featRaw) ? $featRaw : (json_decode($featRaw, true) ?? []);
            $featEn = implode("\n", $featArr['en'] ?? []);
            $featBn = implode("\n", $featArr['bn'] ?? []);
            $color  = $plan->color ?? '#6366f1';
        @endphp
        <div class="rounded-2xl border border-white/[0.07] bg-[#111111] overflow-hidden transition hover:border-white/10 group">
            {{-- Color bar --}}
            <div class="h-1.5 w-full" style="background: {{ $color }}"></div>

            <div class="p-5">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-[10px] font-bold uppercase tracking-widest" style="color: {{ $color }}">{{ $catEn }}</span>
                            @if($plan->is_featured)
                            <span class="text-[9px] px-2 py-0.5 rounded-full font-bold text-white" style="background: {{ $color }}">FEATURED</span>
                            @endif
                            @if($badgeEn)
                            <span class="text-[9px] px-2 py-0.5 rounded-full bg-cyan-500/20 text-cyan-400 font-bold">{{ $badgeEn }}</span>
                            @endif
                        </div>
                        <h3 class="text-white font-bold text-base">{{ $nameEn }}</h3>
                        @if($tagEn)<p class="text-slate-400 text-xs mt-0.5">{{ $tagEn }}</p>@endif
                    </div>
                    <span class="text-[10px] px-2 py-1 rounded-full font-semibold {{ $plan->status ? 'bg-emerald-500/15 text-emerald-400' : 'bg-rose-500/15 text-rose-400' }}">
                        {{ $plan->status ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                {{-- Pricing row --}}
                <div class="flex gap-3 mt-3 mb-4">
                    <div class="flex-1 bg-white/[0.04] rounded-xl p-3 text-center">
                        <div class="text-[9px] text-slate-500 uppercase tracking-wider mb-1">Monthly</div>
                        <div class="text-white font-black text-sm">${{ $plan->price_monthly ?? '—' }}</div>
                    </div>
                    <div class="flex-1 bg-white/[0.04] rounded-xl p-3 text-center">
                        <div class="text-[9px] text-slate-500 uppercase tracking-wider mb-1">Yearly</div>
                        <div class="text-white font-black text-sm">${{ $plan->price_yearly ?? '—' }}</div>
                    </div>
                    <div class="flex-1 bg-white/[0.04] rounded-xl p-3 text-center">
                        <div class="text-[9px] text-slate-500 uppercase tracking-wider mb-1">Lifetime</div>
                        <div class="text-white font-black text-sm">${{ $plan->price_lifetime ?? '—' }}</div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-[10px] text-slate-500">Sort: {{ $plan->sort_order }}</span>
                    <div class="flex gap-2">
                        <button
                            onclick='openEditModal({
                                id: {{ $plan->id }},
                                name_en: @json($nameEn), name_bn: @json($nameBn),
                                category_en: @json($catEn), category_bn: @json($catBn),
                                tagline_en: @json($tagEn), tagline_bn: @json($tagBn),
                                badge_en: @json($badgeEn), badge_bn: @json($badgeBn),
                                description_en: @json($descEn), description_bn: @json($descBn),
                                features_en: @json($featEn), features_bn: @json($featBn),
                                price_monthly: @json($plan->price_monthly), price_yearly: @json($plan->price_yearly), price_lifetime: @json($plan->price_lifetime),
                                link_monthly: @json($plan->link_monthly), link_yearly: @json($plan->link_yearly), link_lifetime: @json($plan->link_lifetime),
                                color: @json($color),
                                is_featured: {{ $plan->is_featured ? 'true' : 'false' }},
                                status: {{ $plan->status ? 'true' : 'false' }},
                                sort_order: {{ $plan->sort_order }}
                            })'
                            class="px-3 py-1.5 rounded-lg bg-indigo-500/15 text-indigo-400 hover:bg-indigo-500/30 text-xs font-semibold transition">
                            <i class="fa-solid fa-pen-to-square mr-1"></i>Edit
                        </button>
                        <form action="{{ route('admin.pricing.destroy', $plan) }}" method="POST" onsubmit="return confirm('Delete this plan?')">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1.5 rounded-lg bg-rose-500/10 text-rose-400 hover:bg-rose-500/20 text-xs font-semibold transition">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-20 text-slate-500">
            <i class="fa-solid fa-tags text-4xl mb-4 block opacity-30"></i>
            No pricing plans yet. Click "Add Plan" to get started.
        </div>
        @endforelse
    </div>
</div>

{{-- ═══════════════════ ADD MODAL ═══════════════════ --}}
<div id="addModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closeAddModal()"></div>
    <div class="relative w-full max-w-3xl bg-[#0f0f0f] border border-white/10 rounded-2xl shadow-2xl max-h-[92vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-white/10 sticky top-0 bg-[#0f0f0f] z-10">
            <h2 class="text-white font-bold text-lg">Add New Pricing Plan</h2>
            <button onclick="closeAddModal()" class="text-slate-400 hover:text-white text-xl"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('admin.pricing.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            @include('admin.pricing._form')
            <div class="flex justify-end gap-3 pt-4 border-t border-white/10">
                <button type="button" onclick="closeAddModal()" class="px-5 py-2.5 rounded-xl bg-white/5 text-slate-300 hover:bg-white/10 text-sm font-medium transition">Cancel</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold transition shadow-lg shadow-indigo-600/25">Save Plan</button>
            </div>
        </form>
    </div>
</div>

{{-- ═══════════════════ EDIT MODAL ═══════════════════ --}}
<div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closeEditModal()"></div>
    <div class="relative w-full max-w-3xl bg-[#0f0f0f] border border-white/10 rounded-2xl shadow-2xl max-h-[92vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-white/10 sticky top-0 bg-[#0f0f0f] z-10">
            <h2 class="text-white font-bold text-lg">Edit Pricing Plan</h2>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-white text-xl"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="editForm" method="POST" class="p-6 space-y-6">
            @csrf
            @include('admin.pricing._form', ['isEdit' => true])
            <div class="flex justify-end gap-3 pt-4 border-t border-white/10">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 rounded-xl bg-white/5 text-slate-300 hover:bg-white/10 text-sm font-medium transition">Cancel</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold transition shadow-lg shadow-indigo-600/25">Update Plan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('addModal').classList.remove('hidden');
    document.getElementById('addModal').classList.add('flex');
}
function closeAddModal() {
    document.getElementById('addModal').classList.add('hidden');
    document.getElementById('addModal').classList.remove('flex');
}
function openEditModal(data) {
    const form = document.getElementById('editForm');
    form.action = '/admin/pricing/' + data.id;
    const fields = ['name_en','name_bn','category_en','category_bn','tagline_en','tagline_bn',
        'badge_en','badge_bn','description_en','description_bn','features_en','features_bn',
        'price_monthly','price_yearly','price_lifetime','link_monthly','link_yearly','link_lifetime',
        'color','sort_order'];
    fields.forEach(f => {
        const el = form.querySelector('[name="' + f + '"]');
        if (el) el.value = data[f] ?? '';
    });
    form.querySelector('[name="is_featured"]').checked = data.is_featured;
    form.querySelector('[name="status"]').checked = data.status;
    // Update color preview
    const colorInput = form.querySelector('[name="color"]');
    const colorPreview = form.querySelector('#edit-color-preview');
    if (colorPreview) colorPreview.style.background = data.color;
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
}
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
}
</script>
@endsection
