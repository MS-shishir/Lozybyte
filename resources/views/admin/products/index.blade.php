@extends('admin.layouts.app')

@section('page_title', 'SaaS Products Marketplace')

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
    <h3 class="text-lg font-bold"><i class="fa-solid fa-box-open text-indigo-500 mr-2"></i> Marketplace Subscriptions</h3>
    <button onclick="toggleModal('modal-add-product')" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-slate-50 text-xs font-semibold rounded-xl transition-all shadow-[0_0_15px_rgba(99,102,241,0.2)]">
        <i class="fa-solid fa-plus mr-1"></i> Add SaaS Product
    </button>
</div>

<!-- Products Table -->
<div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl p-8 shadow-lg mb-8">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse">
            <thead>
                <tr class="border-b border-slate-700/50 text-slate-400 text-xs font-semibold uppercase tracking-wider">
                    <th class="py-3 px-4">Product Info</th>
                    <th class="py-3 px-4">Category / Tagline</th>
                    <th class="py-3 px-4">Pricing plans</th>
                    <th class="py-3 px-4">Stats & Badges</th>
                    <th class="py-3 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800 text-slate-300">
                @php $productDataMap = []; @endphp
                @forelse($products as $product)
                @php
                    $titleEn = is_array($product->title) ? ($product->title['en'] ?? '') : $product->title;
                    $titleBn = is_array($product->title) ? ($product->title['bn'] ?? '') : '';
                    $tagEn = is_array($product->tagline) ? ($product->tagline['en'] ?? '') : $product->tagline;
                    $tagBn = is_array($product->tagline) ? ($product->tagline['bn'] ?? '') : '';
                    $badgeEn = is_array($product->badge) ? ($product->badge['en'] ?? '') : $product->badge;
                    $badgeBn = is_array($product->badge) ? ($product->badge['bn'] ?? '') : '';
                    $descEn = is_array($product->description) ? ($product->description['en'] ?? '') : $product->description;
                    $descBn = is_array($product->description) ? ($product->description['bn'] ?? '') : '';
                    $featEn = is_array($product->features) ? ($product->features['en'] ?? []) : [];
                    $featBn = is_array($product->features) ? ($product->features['bn'] ?? []) : [];
                @endphp
                <tr class="align-top group hover:bg-slate-950/20 transition-all">
                    <!-- Title & Demo -->
                    <td class="py-5 px-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold" style="background-color: {{ $product->color ?? '#6366f1' }}22; color: {{ $product->color ?? '#6366f1' }}">
                                <i class="fa-solid fa-cube"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-slate-100 text-sm">{{ $titleEn }}</h5>
                                <span class="block text-[10px] text-slate-500 font-mono">slug: {{ $product->slug }}</span>
                            </div>
                        </div>
                        <div class="mt-2 space-y-1">
                            @if($product->demo_url)
                            <a href="{{ $product->demo_url }}" target="_blank" class="inline-flex items-center text-xs text-indigo-400 hover:text-indigo-300">
                                <i class="fa-solid fa-arrow-up-right-from-square mr-1"></i> Demo link
                            </a>
                            @endif
                        </div>
                    </td>

                    <!-- Category & Tagline -->
                    <td class="py-5 px-4">
                        <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold bg-indigo-950/50 border border-indigo-900/30 rounded-full text-indigo-400 mb-1">
                            {{ $product->category }}
                        </span>
                        <p class="text-xs text-slate-400 font-medium">{{ $tagEn }}</p>
                    </td>

                    <!-- Pricing -->
                    <td class="py-5 px-4 font-mono text-xs">
                        <div class="space-y-1">
                            <p class="text-slate-300"><span class="text-slate-500">Monthly:</span> {{ $product->pricing['monthly']['price'] ?? '0' }}</p>
                            <p class="text-slate-300"><span class="text-slate-500">Yearly:</span> {{ $product->pricing['yearly']['price'] ?? '0' }}</p>
                            <p class="text-slate-300"><span class="text-slate-500">Lifetime:</span> {{ $product->pricing['lifetime']['price'] ?? '0' }}</p>
                        </div>
                    </td>

                    <!-- Stats & Badges -->
                    <td class="py-5 px-4 text-xs">
                        <div class="space-y-1">
                            @if($badgeEn)
                            <p><span class="text-slate-500">Badge:</span> <span class="px-2 py-0.5 rounded text-[10px]" style="background-color: {{ $product->badge_color ?? $product->color ?? '#6366f1' }}22; color: {{ $product->badge_color ?? $product->color ?? '#6366f1' }}">{{ $badgeEn }}</span></p>
                            @endif
                            <p><span class="text-slate-500">Rating:</span> <span class="text-amber-400 font-bold"><i class="fa-solid fa-star text-[10px]"></i> {{ $product->rating ?? '4.8' }}</span></p>
                            <p><span class="text-slate-500">Clients:</span> {{ $product->clients_count ?? '0' }}</p>
                        </div>
                    </td>

                    <!-- Actions -->
                    <td class="py-5 px-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold rounded-full {{ $product->status ? 'bg-emerald-500/10 text-emerald-400' : 'bg-slate-800 text-slate-500' }} mr-2">
                                {{ $product->status ? 'Active' : 'Draft' }}
                            </span>
                            <button onclick="openEdit({{ $product->id }})" class="px-3 py-1.5 bg-indigo-600/10 hover:bg-indigo-600 text-indigo-400 hover:text-slate-50 text-[10px] font-bold rounded-xl transition-all">
                                <i class="fa-solid fa-pen mr-1"></i> Edit
                            </button>
                            <form action="{{ route('admin.products.delete', $product->id) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 bg-rose-600/10 hover:bg-rose-600 text-rose-400 hover:text-slate-50 text-[10px] font-bold rounded-xl transition-all">
                                    <i class="fa-solid fa-trash mr-1"></i> Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @php
                    $productDataMap[$product->id] = [
                        'id' => $product->id,
                        'slug' => $product->slug,
                        'category' => $product->category,
                        'demo_url' => $product->demo_url,
                        'status' => (bool)$product->status,
                        'title_en' => $titleEn,
                        'title_bn' => $titleBn,
                        'tagline_en' => $tagEn,
                        'tagline_bn' => $tagBn,
                        'badge_en' => $badgeEn,
                        'badge_bn' => $badgeBn,
                        'badge_color' => $product->badge_color ?? $product->color ?? '#6366f1',
                        'description_en' => $descEn,
                        'description_bn' => $descBn,
                        'price_monthly' => $product->pricing['monthly']['price'] ?? '',
                        'link_monthly' => $product->pricing['monthly']['link'] ?? '',
                        'price_yearly' => $product->pricing['yearly']['price'] ?? '',
                        'link_yearly' => $product->pricing['yearly']['link'] ?? '',
                        'price_lifetime' => $product->pricing['lifetime']['price'] ?? '',
                        'link_lifetime' => $product->pricing['lifetime']['link'] ?? '',
                        'features_en' => implode("\n", is_array($featEn) ? $featEn : []),
                        'features_bn' => implode("\n", is_array($featBn) ? $featBn : []),
                        'icon' => $product->icon ?? 'Package',
                        'color' => $product->color ?? '#6366f1',
                        'clients_count' => $product->clients_count ?? 0,
                        'rating' => $product->rating ?? 4.8,
                        'screenshot_type' => $product->screenshot_type ?? 'school',
                        'sort_order' => $product->sort_order ?? 0,
                    ];
                @endphp
                @empty
                <tr>
                    <td colspan="5" class="py-12 px-4 text-center text-slate-500">
                        <i class="fa-solid fa-box-open text-4xl block mb-2"></i>
                        No SaaS products listed. Click "Add SaaS Product" to register one.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>window._productData = {!! json_encode($productDataMap, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!};</script>

{!! $products->links() !!}

{{-- ======================== ADD MODAL ======================== --}}
<div id="modal-add-product" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-start justify-center p-6 overflow-y-auto hidden">
    <div class="bg-[#141C2F] border border-slate-700/50 rounded-3xl w-full max-w-3xl shadow-2xl my-6">
        <div class="h-16 flex items-center justify-between px-6 border-b border-slate-700/50 sticky top-0 bg-[#141C2F] z-10 rounded-t-3xl">
            <h4 class="font-bold text-slate-50 flex items-center gap-2">
                <i class="fa-solid fa-box-open text-indigo-500"></i> Add New SaaS Product
            </h4>
            <button onclick="toggleModal('modal-add-product')" class="text-slate-400 hover:text-slate-200 text-lg"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf

            {{-- Row 1: Slug, Category, Icon, Sort Order, Status --}}
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div>
                    <label class="form-label">Slug <span class="text-rose-400">*</span></label>
                    <input type="text" name="slug" placeholder="e.g. school-erp" required class="form-input">
                </div>
                <div>
                    <label class="form-label">Category <span class="text-rose-400">*</span></label>
                    <select name="category" required class="form-input">
                        <option value="School Management">School Management</option>
                        <option value="POS">Retail POS</option>
                        <option value="Pharmacy">Pharmacy SaaS</option>
                        <option value="Restaurant">Restaurant POS</option>
                        <option value="Inventory">Inventory Hub</option>
                        <option value="CRM">Sales CRM</option>
                        <option value="HRM">HRM Payroll</option>
                        <option value="ERP">Unified ERP</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Icon Name</label>
                    <input type="text" name="icon" placeholder="GraduationCap, Users, ShoppingCart" class="form-input" value="Package">
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

            {{-- Row 2: Color, Glow Color, Mockup Type --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="form-label">Primary Color (hex)</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="color" value="#06b6d4" class="w-10 h-10 rounded-xl border border-slate-700/50 bg-transparent cursor-pointer p-0.5">
                        <input type="text" name="color_text" placeholder="#06b6d4" oninput="syncColor(this,'color','add')" class="form-input flex-1" value="#06b6d4">
                    </div>
                </div>
                <div>
                    <label class="form-label">Glow / Badge Color</label>
                    <input type="text" name="badge_color" placeholder="e.g. #06b6d4" class="form-input" value="#06b6d4">
                </div>
                <div>
                    <label class="form-label">Mockup Template Preview</label>
                    <select name="screenshot_type" class="form-input">
                        <option value="school">School (CampusOS)</option>
                        <option value="crm">CRM (HubIQ)</option>
                        <option value="pos">POS (RetailIQ)</option>
                        <option value="restaurant">Restaurant (DineOS)</option>
                        <option value="inventory">Inventory (StockMate)</option>
                        <option value="pharmacy">Pharmacy (PharmaPro)</option>
                        <option value="hrm">HRM (TalentFlow)</option>
                        <option value="erp">ERP (EnterpriseCore)</option>
                    </select>
                </div>
            </div>

            {{-- Row 3: Titles --}}
            <div class="border-t border-slate-700/40 pt-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Product Name (EN) <span class="text-rose-400">*</span></label>
                        <input type="text" name="title_en" placeholder="CampusOS" required class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Product Name (BN) <span class="text-rose-400">*</span></label>
                        <input type="text" name="title_bn" placeholder="ক্যাম্পাস ওএস" required class="form-input">
                    </div>
                </div>
            </div>

            {{-- Row 4: Tagline --}}
            <div class="border-t border-slate-700/40 pt-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Tagline (EN)</label>
                        <input type="text" name="tagline_en" placeholder="School Management System" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Tagline (BN)</label>
                        <input type="text" name="tagline_bn" placeholder="স্কুল ম্যানেজমেন্ট সিস্টেম" class="form-input">
                    </div>
                </div>
            </div>

            {{-- Row 5: Badge & Description --}}
            <div class="border-t border-slate-700/40 pt-4">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="form-label">Badge (EN)</label>
                        <input type="text" name="badge_en" placeholder="Best Seller" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Badge (BN)</label>
                        <input type="text" name="badge_bn" placeholder="সেরা বিক্রেতা" class="form-input">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Short Description (EN)</label>
                        <textarea name="description_en" rows="2" placeholder="Complete school ERP..." class="form-input"></textarea>
                    </div>
                    <div>
                        <label class="form-label">Short Description (BN)</label>
                        <textarea name="description_bn" rows="2" placeholder="সম্পূর্ণ স্কুল ইআরপি..." class="form-input"></textarea>
                    </div>
                </div>
            </div>

            {{-- Row 6: Pricing Setup --}}
            <div class="border-t border-slate-700/40 pt-4 space-y-4">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Plan Pricing details</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Monthly --}}
                    <div class="p-3 bg-slate-950/40 border border-slate-700/30 rounded-xl space-y-2">
                        <span class="block text-[10px] font-bold text-indigo-400">Monthly</span>
                        <input type="text" name="price_monthly" placeholder="e.g. ৳5,000 or Custom" required class="form-input" value="৳5,000">
                        <input type="text" name="link_monthly" placeholder="Checkout URL" class="form-input text-[10px]">
                    </div>
                    {{-- Yearly --}}
                    <div class="p-3 bg-slate-950/40 border border-slate-700/30 rounded-xl space-y-2">
                        <span class="block text-[10px] font-bold text-indigo-400">Yearly</span>
                        <input type="text" name="price_yearly" placeholder="e.g. ৳50,000" class="form-input" value="৳50,000">
                        <input type="text" name="link_yearly" placeholder="Checkout URL" class="form-input text-[10px]">
                    </div>
                    {{-- Lifetime / custom --}}
                    <div class="p-3 bg-slate-950/40 border border-slate-700/30 rounded-xl space-y-2">
                        <span class="block text-[10px] font-bold text-indigo-400">Lifetime</span>
                        <input type="text" name="price_lifetime" placeholder="e.g. ৳100,000" class="form-input" value="৳100,000">
                        <input type="text" name="link_lifetime" placeholder="Checkout URL" class="form-input text-[10px]">
                    </div>
                </div>
            </div>

            {{-- Row 7: Features --}}
            <div class="border-t border-slate-700/40 pt-4">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Core Features</p>
                <p class="text-[10px] text-slate-500 mb-3">One feature per line</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Features (EN)</label>
                        <textarea name="features_en" rows="4" placeholder="Student & Staff Database&#10;Fee Collection with SMS Alerts" class="form-input font-mono text-xs"></textarea>
                    </div>
                    <div>
                        <label class="form-label">Features (BN)</label>
                        <textarea name="features_bn" rows="4" placeholder="ছাত্র ও স্টাফ ডাটাবেস&#10;এসএমএস এলার্ট সহ ফি কালেকশন" class="form-input font-mono text-xs"></textarea>
                    </div>
                </div>
            </div>

            {{-- Row 8: Rating & Clients, Demo URL --}}
            <div class="border-t border-slate-700/40 pt-4 grid grid-cols-3 gap-4">
                <div>
                    <label class="form-label">Demo URL</label>
                    <input type="text" name="demo_url" placeholder="e.g. #contact" class="form-input" value="#contact">
                </div>
                <div>
                    <label class="form-label">Rating (out of 5)</label>
                    <input type="text" name="rating" placeholder="4.9" class="form-input" value="4.9">
                </div>
                <div>
                    <label class="form-label">Clients Count</label>
                    <input type="number" name="clients_count" placeholder="12" class="form-input" value="12">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-700/50">
                <button type="button" onclick="toggleModal('modal-add-product')" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl transition-all">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-slate-50 text-xs font-semibold rounded-xl transition-all shadow-[0_0_15px_rgba(99,102,241,0.25)]">
                    <i class="fa-solid fa-plus mr-1"></i> Add Product
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ======================== EDIT MODAL ======================== --}}
<div id="modal-edit-product" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-start justify-center p-6 overflow-y-auto hidden">
    <div class="bg-[#141C2F] border border-slate-700/50 rounded-3xl w-full max-w-3xl shadow-2xl my-6">
        <div class="h-16 flex items-center justify-between px-6 border-b border-slate-700/50 sticky top-0 bg-[#141C2F] z-10 rounded-t-3xl">
            <h4 class="font-bold text-slate-50 flex items-center gap-2">
                <i class="fa-solid fa-pen text-amber-400"></i> Edit SaaS Product
            </h4>
            <button onclick="toggleModal('modal-edit-product')" class="text-slate-400 hover:text-slate-200 text-lg"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form id="edit-product-form" action="" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf
            @method('POST')
            <input type="hidden" name="_method" value="POST">

            {{-- Row 1: Slug, Category, Icon, Sort Order, Status --}}
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div>
                    <label class="form-label">Slug <span class="text-rose-400">*</span></label>
                    <input type="text" name="slug" id="edit-slug" required class="form-input">
                </div>
                <div>
                    <label class="form-label">Category <span class="text-rose-400">*</span></label>
                    <select name="category" id="edit-category" required class="form-input">
                        <option value="School Management">School Management</option>
                        <option value="POS">Retail POS</option>
                        <option value="Pharmacy">Pharmacy SaaS</option>
                        <option value="Restaurant">Restaurant POS</option>
                        <option value="Inventory">Inventory Hub</option>
                        <option value="CRM">Sales CRM</option>
                        <option value="HRM">HRM Payroll</option>
                        <option value="ERP">Unified ERP</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Icon Name</label>
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

            {{-- Row 2: Color, Glow Color, Mockup Type --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="form-label">Primary Color (hex)</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="color" id="edit-color-picker" class="w-10 h-10 rounded-xl border border-slate-700/50 bg-transparent cursor-pointer p-0.5">
                        <input type="text" id="edit-color" name="color_text" placeholder="#06b6d4" oninput="syncColor(this,'edit-color-picker','edit')" class="form-input flex-1">
                    </div>
                </div>
                <div>
                    <label class="form-label">Glow / Badge Color</label>
                    <input type="text" name="badge_color" id="edit-badge_color" class="form-input">
                </div>
                <div>
                    <label class="form-label">Mockup Template Preview</label>
                    <select name="screenshot_type" id="edit-screenshot_type" class="form-input">
                        <option value="school">School (CampusOS)</option>
                        <option value="crm">CRM (HubIQ)</option>
                        <option value="pos">POS (RetailIQ)</option>
                        <option value="restaurant">Restaurant (DineOS)</option>
                        <option value="inventory">Inventory (StockMate)</option>
                        <option value="pharmacy">Pharmacy (PharmaPro)</option>
                        <option value="hrm">HRM (TalentFlow)</option>
                        <option value="erp">ERP (EnterpriseCore)</option>
                    </select>
                </div>
            </div>

            {{-- Row 3: Titles --}}
            <div class="border-t border-slate-700/40 pt-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Product Name (EN) <span class="text-rose-400">*</span></label>
                        <input type="text" name="title_en" id="edit-title_en" required class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Product Name (BN) <span class="text-rose-400">*</span></label>
                        <input type="text" name="title_bn" id="edit-title_bn" required class="form-input">
                    </div>
                </div>
            </div>

            {{-- Row 4: Tagline --}}
            <div class="border-t border-slate-700/40 pt-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Tagline (EN)</label>
                        <input type="text" name="tagline_en" id="edit-tagline_en" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Tagline (BN)</label>
                        <input type="text" name="tagline_bn" id="edit-tagline_bn" class="form-input">
                    </div>
                </div>
            </div>

            {{-- Row 5: Badge & Description --}}
            <div class="border-t border-slate-700/40 pt-4">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="form-label">Badge (EN)</label>
                        <input type="text" name="badge_en" id="edit-badge_en" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Badge (BN)</label>
                        <input type="text" name="badge_bn" id="edit-badge_bn" class="form-input">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Short Description (EN)</label>
                        <textarea name="description_en" id="edit-description_en" rows="2" class="form-input"></textarea>
                    </div>
                    <div>
                        <label class="form-label">Short Description (BN)</label>
                        <textarea name="description_bn" id="edit-description_bn" rows="2" class="form-input"></textarea>
                    </div>
                </div>
            </div>

            {{-- Row 6: Pricing Setup --}}
            <div class="border-t border-slate-700/40 pt-4 space-y-4">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Plan Pricing details</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Monthly --}}
                    <div class="p-3 bg-slate-950/40 border border-slate-700/30 rounded-xl space-y-2">
                        <span class="block text-[10px] font-bold text-indigo-400">Monthly</span>
                        <input type="text" name="price_monthly" id="edit-price_monthly" required class="form-input">
                        <input type="text" name="link_monthly" id="edit-link_monthly" placeholder="Checkout URL" class="form-input text-[10px]">
                    </div>
                    {{-- Yearly --}}
                    <div class="p-3 bg-slate-950/40 border border-slate-700/30 rounded-xl space-y-2">
                        <span class="block text-[10px] font-bold text-indigo-400">Yearly</span>
                        <input type="text" name="price_yearly" id="edit-price_yearly" class="form-input">
                        <input type="text" name="link_yearly" id="edit-link_yearly" placeholder="Checkout URL" class="form-input text-[10px]">
                    </div>
                    {{-- Lifetime / custom --}}
                    <div class="p-3 bg-slate-950/40 border border-slate-700/30 rounded-xl space-y-2">
                        <span class="block text-[10px] font-bold text-indigo-400">Lifetime</span>
                        <input type="text" name="price_lifetime" id="edit-price_lifetime" class="form-input">
                        <input type="text" name="link_lifetime" id="edit-link_lifetime" placeholder="Checkout URL" class="form-input text-[10px]">
                    </div>
                </div>
            </div>

            {{-- Row 7: Features --}}
            <div class="border-t border-slate-700/40 pt-4">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Core Features (one per line)</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Features (EN)</label>
                        <textarea name="features_en" id="edit-features_en" rows="4" class="form-input font-mono text-xs"></textarea>
                    </div>
                    <div>
                        <label class="form-label">Features (BN)</label>
                        <textarea name="features_bn" id="edit-features_bn" rows="4" class="form-input font-mono text-xs"></textarea>
                    </div>
                </div>
            </div>

            {{-- Row 8: Rating & Clients, Demo URL --}}
            <div class="border-t border-slate-700/40 pt-4 grid grid-cols-3 gap-4">
                <div>
                    <label class="form-label">Demo URL</label>
                    <input type="text" name="demo_url" id="edit-demo_url" class="form-input">
                </div>
                <div>
                    <label class="form-label">Rating (out of 5)</label>
                    <input type="text" name="rating" id="edit-rating" class="form-input">
                </div>
                <div>
                    <label class="form-label">Clients Count</label>
                    <input type="number" name="clients_count" id="edit-clients_count" class="form-input">
                </div>
            </div>

            {{-- Row 9: Screenshots Edit --}}
            <div class="border-t border-slate-700/40 pt-4">
                <label class="form-label">Add Preview Screenshots (Multiple)</label>
                <input type="file" name="screenshots[]" multiple class="block w-full text-sm text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600/10 file:text-indigo-400 hover:file:bg-indigo-600/20 mb-4">
                <div id="edit-screenshots-container" class="grid grid-cols-4 gap-3">
                    {{-- Populated dynamically via JS --}}
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-700/50">
                <button type="button" onclick="toggleModal('modal-edit-product')" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl transition-all">Cancel</button>
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
    const d = window._productData[id];
    if (!d) return;

    // Set form action
    document.getElementById('edit-product-form').action = `/admin/products/${id}`;

    // Fill all fields
    const set = (fieldId, val) => {
        const el = document.getElementById(fieldId);
        if (!el) return;
        if (el.type === 'checkbox') el.checked = val;
        else el.value = val ?? '';
    };

    set('edit-slug', d.slug);
    set('edit-category', d.category);
    set('edit-icon', d.icon);
    set('edit-sort_order', d.sort_order);
    set('edit-status', d.status);
    set('edit-color-picker', d.color);
    set('edit-color', d.color);
    set('edit-badge_color', d.badge_color);
    set('edit-screenshot_type', d.screenshot_type);
    set('edit-title_en', d.title_en);
    set('edit-title_bn', d.title_bn);
    set('edit-tagline_en', d.tagline_en);
    set('edit-tagline_bn', d.tagline_bn);
    set('edit-badge_en', d.badge_en);
    set('edit-badge_bn', d.badge_bn);
    set('edit-description_en', d.description_en);
    set('edit-description_bn', d.description_bn);
    set('edit-price_monthly', d.price_monthly);
    set('edit-link_monthly', d.link_monthly);
    set('edit-price_yearly', d.price_yearly);
    set('edit-link_yearly', d.link_yearly);
    set('edit-price_lifetime', d.price_lifetime);
    set('edit-link_lifetime', d.link_lifetime);
    set('edit-features_en', d.features_en);
    set('edit-features_bn', d.features_bn);
    set('edit-demo_url', d.demo_url);
    set('edit-rating', d.rating);
    set('edit-clients_count', d.clients_count);

    // Populate screenshots preview manager
    const container = document.getElementById('edit-screenshots-container');
    container.innerHTML = '';
    if (d.screenshots && d.screenshots.length > 0) {
        d.screenshots.forEach((path, index) => {
            const wrapper = document.createElement('div');
            wrapper.className = 'relative group border border-slate-700/50 rounded-xl overflow-hidden aspect-video bg-slate-900 flex items-center justify-center';
            wrapper.innerHTML = `
                <img src="/storage/${path}" class="w-full h-full object-cover" />
                <label class="absolute top-1 right-1 bg-rose-600 hover:bg-rose-500 text-white rounded-full p-1.5 cursor-pointer flex items-center justify-center w-6 h-6 shadow transition-all">
                    <input type="checkbox" name="remove_screenshots[]" value="${index}" class="hidden" onchange="toggleRemoveScreenshot(this)" />
                    <i class="fa-solid fa-trash-can text-[10px]"></i>
                </label>
            `;
            container.appendChild(wrapper);
        });
    }

    toggleModal('modal-edit-product');
}

function toggleRemoveScreenshot(el) {
    const icon = el.nextElementSibling;
    if (el.checked) {
        el.parentElement.classList.remove('bg-rose-600', 'hover:bg-rose-500');
        el.parentElement.classList.add('bg-slate-700', 'hover:bg-slate-650');
        el.parentElement.style.opacity = '0.5';
        icon.className = 'fa-solid fa-undo text-[10px]';
    } else {
        el.parentElement.classList.add('bg-rose-600', 'hover:bg-rose-500');
        el.parentElement.classList.remove('bg-slate-700', 'hover:bg-slate-650');
        el.parentElement.style.opacity = '1';
        icon.className = 'fa-solid fa-trash-can text-[10px]';
    }
}

function syncColor(inputEl, pickerId, prefix) {
    const picker = document.getElementById(pickerId);
    if (picker && inputEl.value.startsWith('#') && inputEl.value.length >= 4) {
        picker.value = inputEl.value;
    }
}
</script>

@endsection
