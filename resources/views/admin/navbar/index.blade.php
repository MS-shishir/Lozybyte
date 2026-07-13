@extends('admin.layouts.app')

@section('page_title', 'Manage Navbar Links')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <h3 class="text-lg font-bold text-slate-100"><i class="fa-solid fa-compass text-indigo-500 mr-2"></i> Navigation Menu Items</h3>
    <button onclick="openAddModal()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-slate-50 text-xs font-semibold rounded-xl transition-all shadow-[0_0_15px_rgba(99,102,241,0.2)] shrink-0 self-start sm:self-auto">
        <i class="fa-solid fa-plus mr-1"></i> Add Menu Link
    </button>
</div>

<!-- Filters & Search -->
<div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl p-6 mb-8 shadow-lg">
    <form action="{{ route('admin.navbar.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
        <div class="flex-1 w-full">
            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Search Links</label>
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by label or URL..."
                    class="block w-full pl-10 pr-4 py-2.5 bg-slate-950/50/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                <div class="absolute left-3.5 top-3.5 text-slate-500 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
            </div>
        </div>
        <div class="w-full md:w-48">
            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Status</label>
            <select name="status" class="block w-full px-4 py-2.5 bg-slate-950/50/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <div class="flex gap-2 w-full md:w-auto">
            <button type="submit" class="flex-1 md:flex-none px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold rounded-2xl transition-colors">
                Filter
            </button>
            <a href="{{ route('admin.navbar.index') }}" class="flex-1 md:flex-none px-5 py-2.5 border border-slate-700/50 hover:bg-[#141C2F]/80 backdrop-blur-md text-slate-400 hover:text-slate-300 text-xs font-bold rounded-2xl text-center transition-colors">
                Clear
            </a>
        </div>
    </form>
</div>

<!-- Links Table -->
<div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl overflow-hidden shadow-lg mb-6">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse">
            <thead>
                <tr class="border-b border-slate-700/50 text-slate-400 text-xs font-semibold uppercase tracking-wider">
                    <th class="py-4 px-6">Label (EN)</th>
                    <th class="py-4 px-6">Target URL</th>
                    <th class="py-4 px-6">Parent Menu</th>
                    <th class="py-4 px-6 text-center">Sort Order</th>
                    <th class="py-4 px-6 text-center">Type</th>
                    <th class="py-4 px-6">Status</th>
                    <th class="py-4 px-6 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60 text-slate-300">
                @forelse($navItems as $item)
                <tr class="hover:bg-slate-800/10 transition-colors">
                    <td class="py-4 px-6">
                        <span class="font-bold text-slate-100">{{ $item->label }}</span>
                        <div class="text-[10px] text-slate-500 mt-0.5">BN: {{ $item->getTranslations('label')['bn'] ?? '' }} | JA: {{ $item->getTranslations('label')['ja'] ?? '' }}</div>
                    </td>
                    <td class="py-4 px-6 font-mono text-xs text-indigo-400">{{ $item->url }}</td>
                    <td class="py-4 px-6">
                        @if($item->parent)
                        <span class="inline-block px-2.5 py-0.5 text-[10px] font-semibold rounded-full bg-slate-800 text-slate-300">
                            {{ $item->parent->label }}
                        </span>
                        @else
                        <span class="text-slate-500 text-xs italic">None (Root)</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-center font-semibold text-slate-200">{{ $item->order }}</td>
                    <td class="py-4 px-6 text-center">
                        <span class="inline-block px-2 py-0.5 text-[10px] font-bold rounded {{ $item->is_external ? 'bg-amber-500/10 text-amber-400' : 'bg-indigo-500/10 text-indigo-400' }}">
                            {{ $item->is_external ? 'External' : 'Internal' }}
                        </span>
                    </td>
                    <td class="py-4 px-6">
                        <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold rounded-full {{ $item->status ? 'bg-emerald-500/10 text-emerald-400' : 'bg-slate-800 text-slate-500' }}">
                            {{ $item->status ? 'Active' : 'Hidden' }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <button onclick='openEditModal({!! json_encode($item) !!})' class="px-2.5 py-1 bg-slate-800 hover:bg-indigo-600 hover:text-white text-slate-300 text-xs font-semibold rounded-lg transition-colors">
                                Edit
                            </button>
                            <form action="{{ route('admin.navbar.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this link?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2.5 py-1 bg-rose-600/10 hover:bg-rose-600 text-rose-400 hover:text-slate-50 text-xs font-semibold rounded-lg transition-colors">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-12 text-center text-slate-500">
                        <i class="fa-solid fa-compass text-4xl block mb-2"></i>
                        No navigation items found. Click "Add Menu Link" to create one.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $navItems->links() }}
</div>

<!-- Modal: Add / Edit Link -->
<div id="modal-link" class="fixed inset-0 bg-slate-950/50/50/80 backdrop-blur-sm z-50 flex items-center justify-center p-6 hidden">
    <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl w-full max-w-xl max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="h-16 flex items-center justify-between px-6 border-b border-slate-700/50">
            <h4 id="modal-title" class="font-bold text-slate-50 flex items-center">
                <i class="fa-solid fa-compass text-indigo-500 mr-2"></i> Add Menu Link
            </h4>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-200 text-lg"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form id="modal-form" action="{{ route('admin.navbar.store') }}" method="POST" class="p-6 space-y-5">
            @csrf
            <input type="hidden" name="_method" id="form-method" value="POST">

            <!-- Parent Menu & Order -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Parent Menu (Optional)</label>
                    <select name="parent_id" id="field-parent" class="block w-full px-4 py-2.5 bg-slate-950/50/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                        <option value="">None (Top Level Root)</option>
                        @foreach($parentItems as $p)
                        <option value="{{ $p->id }}">{{ $p->label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Sort Order</label>
                    <input type="number" name="order" id="field-order" value="0" required
                        class="block w-full px-4 py-2.5 bg-slate-950/50/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none">
                </div>
            </div>

            <!-- Labels EN, BN, JA -->
            <div class="space-y-4 pt-3 border-t border-slate-700/50/60">
                <h5 class="text-xs font-bold text-slate-300 uppercase tracking-widest">Menu Label Text</h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">English</label>
                        <input type="text" name="label_en" id="field-label-en" required
                            class="block w-full px-3.5 py-2 bg-slate-950/50/50 border border-slate-700/50 focus:border-indigo-500 rounded-xl text-slate-200 text-xs outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Bangla</label>
                        <input type="text" name="label_bn" id="field-label-bn"
                            class="block w-full px-3.5 py-2 bg-slate-950/50/50 border border-slate-700/50 focus:border-indigo-500 rounded-xl text-slate-200 text-xs outline-none">
                    </div>
                    <div>
                        

                    </div>
                </div>
            </div>

            <!-- URL & Toggles -->
            <div class="space-y-4 pt-3 border-t border-slate-700/50/60">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Target URL / Section Anchor</label>
                    <input type="text" name="url" id="field-url" placeholder="e.g. #services, /pricing, https://google.com" required
                        class="block w-full px-4 py-2.5 bg-slate-950/50/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none font-mono">
                </div>

                <div class="flex items-center space-x-6 pt-2">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_external" id="field-external" class="w-5 h-5 text-indigo-600 border-slate-700/50 rounded focus:ring-indigo-500">
                        <label for="field-external" class="ml-2.5 text-xs font-semibold text-slate-300">Is External Link?</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="status" id="field-status" class="w-5 h-5 text-indigo-600 border-slate-700/50 rounded focus:ring-indigo-500" checked>
                        <label for="field-status" class="ml-2.5 text-xs font-semibold text-slate-300">Show Menu Link</label>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-700/50">
                <button type="button" onclick="closeModal()" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl transition-all">Cancel</button>
                <button type="submit" id="btn-submit" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-slate-50 text-xs font-semibold rounded-xl transition-all shadow-[0_0_15px_rgba(99,102,241,0.2)]">Save Item</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('modal-title').innerHTML = '<i class="fa-solid fa-compass text-indigo-500 mr-2"></i> Add Menu Link';
        document.getElementById('form-method').value = 'POST';
        document.getElementById('modal-form').action = "{{ route('admin.navbar.store') }}";
        
        // Reset inputs
        document.getElementById('field-parent').value = '';
        document.getElementById('field-order').value = '0';
        document.getElementById('field-label-en').value = '';
        document.getElementById('field-label-bn').value = '';
        document.getElementById('field-label-ja').value = '';
        document.getElementById('field-url').value = '';
        document.getElementById('field-external').checked = false;
        document.getElementById('field-status').checked = true;

        document.getElementById('modal-link').classList.remove('hidden');
    }

    function openEditModal(item) {
        document.getElementById('modal-title').innerHTML = '<i class="fa-solid fa-compass text-indigo-500 mr-2"></i> Edit Menu Link';
        document.getElementById('form-method').value = 'POST'; // We use POST for routing updates to prevent PUT issues
        document.getElementById('modal-form').action = "/admin/navbar/" + item.id;
        
        // Fill inputs
        document.getElementById('field-parent').value = item.parent_id || '';
        document.getElementById('field-order').value = item.order;
        
        // Parse translations
        const labels = item.translations?.label || {};
        document.getElementById('field-label-en').value = labels.en || item.label || '';
        document.getElementById('field-label-bn').value = labels.bn || '';
        document.getElementById('field-label-ja').value = labels.ja || '';
        
        document.getElementById('field-url').value = item.url;
        document.getElementById('field-external').checked = !!item.is_external;
        document.getElementById('field-status').checked = !!item.status;

        document.getElementById('modal-link').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modal-link').classList.add('hidden');
    }
</script>
@endsection
