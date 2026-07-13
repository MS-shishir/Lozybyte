{{-- Reusable form partial for Add & Edit modals --}}
@php $isEdit = $isEdit ?? false; $prefix = $isEdit ? 'edit-' : 'add-'; @endphp

{{-- Section: Basic Info --}}
<div class="space-y-4">
    <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Plan Identity</p>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-xs text-slate-400 mb-1.5 font-medium">Plan Name (EN)</label>
            <input name="name_en" type="text" placeholder="e.g. School Management Platform"
                class="w-full bg-white/[0.05] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition">
        </div>
        <div>
            <label class="block text-xs text-slate-400 mb-1.5 font-medium">Plan Name (BN)</label>
            <input name="name_bn" type="text" placeholder="e.g. স্কুল ম্যানেজমেন্ট"
                class="w-full bg-white/[0.05] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition">
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-xs text-slate-400 mb-1.5 font-medium">Category Label (EN)</label>
            <input name="category_en" type="text" placeholder="e.g. School Plan"
                class="w-full bg-white/[0.05] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition">
        </div>
        <div>
            <label class="block text-xs text-slate-400 mb-1.5 font-medium">Category Label (BN)</label>
            <input name="category_bn" type="text" placeholder="e.g. স্কুল প্ল্যান"
                class="w-full bg-white/[0.05] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition">
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-xs text-slate-400 mb-1.5 font-medium">Tagline (EN)</label>
            <input name="tagline_en" type="text" placeholder="e.g. Best for schools & colleges"
                class="w-full bg-white/[0.05] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition">
        </div>
        <div>
            <label class="block text-xs text-slate-400 mb-1.5 font-medium">Tagline (BN)</label>
            <input name="tagline_bn" type="text" placeholder="e.g. স্কুল-কলেজের জন্য সেরা"
                class="w-full bg-white/[0.05] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition">
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-xs text-slate-400 mb-1.5 font-medium">Badge Text (EN) <span class="text-slate-600">optional</span></label>
            <input name="badge_en" type="text" placeholder="e.g. Most Popular"
                class="w-full bg-white/[0.05] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition">
        </div>
        <div>
            <label class="block text-xs text-slate-400 mb-1.5 font-medium">Badge Text (BN)</label>
            <input name="badge_bn" type="text" placeholder="e.g. সবচেয়ে জনপ্রিয়"
                class="w-full bg-white/[0.05] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition">
        </div>
    </div>
</div>

{{-- Section: Pricing --}}
<div class="space-y-4">
    <p class="text-xs font-bold uppercase tracking-widest text-slate-500 border-t border-white/[0.06] pt-4">Pricing Per Billing Cycle</p>

    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="block text-xs text-slate-400 mb-1.5 font-medium">Monthly Price ($)</label>
            <input name="price_monthly" type="text" placeholder="49"
                class="w-full bg-white/[0.05] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition">
        </div>
        <div>
            <label class="block text-xs text-slate-400 mb-1.5 font-medium">Yearly Price ($)</label>
            <input name="price_yearly" type="text" placeholder="499"
                class="w-full bg-white/[0.05] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition">
        </div>
        <div>
            <label class="block text-xs text-slate-400 mb-1.5 font-medium">Lifetime Price ($)</label>
            <input name="price_lifetime" type="text" placeholder="999"
                class="w-full bg-white/[0.05] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition">
        </div>
    </div>

    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="block text-xs text-slate-400 mb-1.5 font-medium">Monthly Checkout Link</label>
            <input name="link_monthly" type="url" placeholder="https://..."
                class="w-full bg-white/[0.05] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition">
        </div>
        <div>
            <label class="block text-xs text-slate-400 mb-1.5 font-medium">Yearly Checkout Link</label>
            <input name="link_yearly" type="url" placeholder="https://..."
                class="w-full bg-white/[0.05] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition">
        </div>
        <div>
            <label class="block text-xs text-slate-400 mb-1.5 font-medium">Lifetime Checkout Link</label>
            <input name="link_lifetime" type="url" placeholder="https://..."
                class="w-full bg-white/[0.05] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition">
        </div>
    </div>
</div>

{{-- Section: Features --}}
<div class="space-y-4">
    <p class="text-xs font-bold uppercase tracking-widest text-slate-500 border-t border-white/[0.06] pt-4">Features (one per line)</p>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-xs text-slate-400 mb-1.5 font-medium">Features (EN)</label>
            <textarea name="features_en" rows="5" placeholder="Student Management&#10;Fee Collection&#10;Attendance Tracking&#10;Report Generation"
                class="w-full bg-white/[0.05] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition resize-none"></textarea>
        </div>
        <div>
            <label class="block text-xs text-slate-400 mb-1.5 font-medium">Features (BN)</label>
            <textarea name="features_bn" rows="5" placeholder="ছাত্র ব্যবস্থাপনা&#10;ফি সংগ্রহ&#10;উপস্থিতি ট্র্যাকিং&#10;রিপোর্ট তৈরি"
                class="w-full bg-white/[0.05] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition resize-none"></textarea>
        </div>
    </div>
</div>

{{-- Section: Appearance & Settings --}}
<div class="space-y-4">
    <p class="text-xs font-bold uppercase tracking-widest text-slate-500 border-t border-white/[0.06] pt-4">Appearance & Settings</p>

    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="block text-xs text-slate-400 mb-1.5 font-medium">Accent Color</label>
            <div class="flex items-center gap-2">
                <div id="{{ $prefix }}color-preview" class="w-9 h-9 rounded-lg border border-white/10 flex-shrink-0 transition-colors" style="background:#6366f1"></div>
                <input name="color" type="color" value="#6366f1"
                    oninput="document.getElementById('{{ $prefix }}color-preview').style.background=this.value"
                    class="w-full h-9 bg-white/[0.05] border border-white/10 rounded-xl cursor-pointer">
            </div>
        </div>
        <div>
            <label class="block text-xs text-slate-400 mb-1.5 font-medium">Sort Order</label>
            <input name="sort_order" type="number" min="0" value="0"
                class="w-full bg-white/[0.05] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 transition">
        </div>
        <div class="flex flex-col gap-3 justify-center pt-1">
            <label class="flex items-center gap-2.5 cursor-pointer select-none">
                <div class="relative">
                    <input name="is_featured" type="checkbox" class="sr-only peer">
                    <div class="w-10 h-5 bg-white/10 rounded-full peer-checked:bg-amber-500 transition-colors"></div>
                    <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
                </div>
                <span class="text-xs text-slate-300 font-medium">Featured (Highlighted)</span>
            </label>
            <label class="flex items-center gap-2.5 cursor-pointer select-none">
                <div class="relative">
                    <input name="status" type="checkbox" class="sr-only peer" checked>
                    <div class="w-10 h-5 bg-white/10 rounded-full peer-checked:bg-emerald-500 transition-colors"></div>
                    <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
                </div>
                <span class="text-xs text-slate-300 font-medium">Active (Visible on site)</span>
            </label>
        </div>
    </div>
</div>
