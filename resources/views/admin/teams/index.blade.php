@extends('admin.layouts.app')
@section('content')
<div class="p-6 md:p-8 max-w-7xl mx-auto">

    {{-- Toast --}}
    @if(session('success'))
    <div id="toast" class="fixed top-6 right-6 z-[9999] flex items-center gap-3 px-5 py-4 rounded-2xl bg-emerald-500 text-white text-sm font-semibold shadow-2xl shadow-emerald-500/30" style="animation:slideIn .4s ease">
        <i class="fa-solid fa-circle-check text-lg"></i>
        <span>{{ session('success') }}</span>
        <div class="absolute bottom-0 left-0 h-0.5 bg-white/40 rounded-full" style="width:100%;animation:shrink 2s linear forwards"></div>
    </div>
    <style>
        @keyframes slideIn{from{transform:translateX(120%);opacity:0}to{transform:translateX(0);opacity:1}}
        @keyframes shrink{from{width:100%}to{width:0%}}
    </style>
    <script>setTimeout(()=>{const t=document.getElementById('toast');if(t){t.style.transform='translateX(120%)';t.style.opacity='0';t.style.transition='all .5s';setTimeout(()=>t.remove(),500)}},2000)</script>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
    <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm">
        <i class="fa-solid fa-triangle-exclamation mr-2"></i>
        <strong>Please fix the following:</strong>
        <ul class="mt-2 ml-4 list-disc space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white">Team Members & Accounts</h1>
            <p class="text-slate-400 text-sm mt-1">Manage team profiles, admin access, roles and passwords</p>
        </div>
        <button onclick="openAdd()"
            class="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-xl transition-all shadow-lg shadow-indigo-600/25">
            <i class="fa-solid fa-user-plus"></i> Add Member
        </button>
    </div>

    {{-- Team Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($team as $member)
        @php
            $roleRaw  = $member->getRawOriginal('role');
            $roleArr  = is_array($roleRaw) ? $roleRaw : (json_decode($roleRaw, true) ?? []);
            $roleEn   = $roleArr['en'] ?? '';
            $roleBn   = $roleArr['bn'] ?? '';
            $links    = $member->social_links ?? [];
            $linked   = $member->user;
            $hasLogin = !!$linked;
            $imgUrl   = $member->image_path;
        @endphp
        <div class="rounded-2xl border border-white/[0.07] bg-[#111111] overflow-hidden group hover:border-white/10 transition-all">

            {{-- Card Top --}}
            <div class="relative p-5 flex items-center gap-4">
                {{-- Avatar --}}
                <div class="w-14 h-14 rounded-2xl flex-shrink-0 overflow-hidden border border-white/10">
                    @if($imgUrl)
                        <img src="{{ $imgUrl }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-indigo-600 to-violet-700 flex items-center justify-center font-black text-white text-xl">
                            {{ strtoupper(substr($member->name, 0, 2)) }}
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <h3 class="text-white font-bold text-sm truncate">{{ $member->name }}</h3>
                    <p class="text-indigo-400 text-[11px] font-semibold truncate mt-0.5">{{ $roleEn ?: 'No role title' }}</p>
                    @if($hasLogin)
                    <div class="flex items-center gap-1.5 mt-1">
                        <i class="fa-solid fa-circle-check text-emerald-400 text-[9px]"></i>
                        <span class="text-[10px] text-slate-400 truncate">{{ $linked->email }}</span>
                    </div>
                    @else
                    <span class="text-[10px] text-slate-600 mt-1 block">No admin account</span>
                    @endif
                </div>

                {{-- Status badge --}}
                <span class="absolute top-3 right-3 text-[9px] px-2 py-0.5 rounded-full font-bold {{ $member->status ? 'bg-emerald-500/15 text-emerald-400' : 'bg-rose-500/15 text-rose-400' }}">
                    {{ $member->status ? 'Active' : 'Suspended' }}
                </span>
            </div>

            {{-- Role badge row --}}
            <div class="px-5 pb-4 flex items-center gap-2 flex-wrap">
                @if($hasLogin)
                <span class="text-[9px] px-2.5 py-1 rounded-full font-bold bg-violet-500/15 text-violet-400 border border-violet-500/20">
                    <i class="fa-solid fa-shield-halved mr-1"></i>{{ str_replace('_', ' ', strtoupper($linked->role)) }}
                </span>
                @endif
                {{-- Socials --}}
                @if($links['linkedin'] ?? null)
                <a href="{{ $links['linkedin'] }}" target="_blank" class="text-[10px] text-slate-500 hover:text-blue-400 transition"><i class="fa-brands fa-linkedin"></i></a>
                @endif
                @if($links['github'] ?? null)
                <a href="{{ $links['github'] }}" target="_blank" class="text-[10px] text-slate-500 hover:text-white transition"><i class="fa-brands fa-github"></i></a>
                @endif
                @if($links['twitter'] ?? null)
                <a href="{{ $links['twitter'] }}" target="_blank" class="text-[10px] text-slate-500 hover:text-sky-400 transition"><i class="fa-brands fa-twitter"></i></a>
                @endif
            </div>

            {{-- Actions --}}
            <div class="px-5 pb-5 flex gap-2 border-t border-white/[0.05] pt-4">
                <button
                    onclick='openEdit({
                        id: {{ $member->id }},
                        name: @json($member->name),
                        role_en: @json($roleEn),
                        role_bn: @json($roleBn),
                        linkedin: @json($links["linkedin"] ?? ""),
                        github:   @json($links["github"] ?? ""),
                        twitter:  @json($links["twitter"] ?? ""),
                        status:   {{ $member->status ? "true" : "false" }},
                        email:    @json($linked?->email ?? ""),
                        system_role: @json($linked?->role ?? ""),
                        has_login: {{ $hasLogin ? "true" : "false" }}
                    })'
                    class="flex-1 px-3 py-2 rounded-xl bg-indigo-500/15 text-indigo-400 hover:bg-indigo-500/30 text-xs font-semibold transition text-center">
                    <i class="fa-solid fa-pen-to-square mr-1"></i>Edit
                </button>

                {{-- Toggle Suspend --}}
                <form action="{{ route('admin.teams.update', $member->id) }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="name" value="{{ $member->name }}">
                    <input type="hidden" name="role_en" value="{{ $roleEn }}">
                    <input type="hidden" name="role_bn" value="{{ $roleBn }}">
                    <input type="hidden" name="social_linkedin" value="{{ $links['linkedin'] ?? '' }}">
                    <input type="hidden" name="social_github" value="{{ $links['github'] ?? '' }}">
                    <input type="hidden" name="social_twitter" value="{{ $links['twitter'] ?? '' }}">
                    @if($linked)
                    <input type="hidden" name="email" value="{{ $linked->email }}">
                    <input type="hidden" name="system_role" value="{{ $linked->role }}">
                    @endif
                    @if($member->status){{-- Currently active → show Suspend --}}
                    {{-- No status checkbox = will become false --}}
                    @else
                    <input type="hidden" name="status" value="1">{{-- Currently suspended → will become active --}}
                    @endif
                    <button type="submit"
                        class="w-full px-3 py-2 rounded-xl text-xs font-semibold transition text-center {{ $member->status ? 'bg-amber-500/10 text-amber-400 hover:bg-amber-500/20' : 'bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20' }}">
                        <i class="fa-solid {{ $member->status ? 'fa-ban' : 'fa-circle-check' }} mr-1"></i>{{ $member->status ? 'Suspend' : 'Activate' }}
                    </button>
                </form>

                {{-- Delete --}}
                <form action="{{ route('admin.teams.delete', $member->id) }}" method="POST" onsubmit="return confirm('Delete {{ $member->name }}? This will also remove their admin account.')">
                    @csrf @method('DELETE')
                    <button class="px-3 py-2 rounded-xl bg-rose-500/10 text-rose-400 hover:bg-rose-500/20 text-xs font-semibold transition">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-3 py-20 text-center text-slate-500">
            <i class="fa-solid fa-users text-4xl block mb-3 opacity-30"></i>
            No team members found. Click "Add Member" to create your first profile.
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-8">{{ $team->links() }}</div>
</div>

{{-- ════════════ ADD MODAL ════════════ --}}
<div id="addModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closeAdd()"></div>
    <div class="relative w-full max-w-2xl bg-[#0f0f0f] border border-white/10 rounded-2xl shadow-2xl max-h-[92vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-white/10 sticky top-0 bg-[#0f0f0f] z-10">
            <h2 class="text-white font-bold text-lg flex items-center gap-2"><i class="fa-solid fa-user-plus text-indigo-400"></i> Add Team Member</h2>
            <button onclick="closeAdd()" class="text-slate-400 hover:text-white text-xl"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('admin.teams.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf
            @include('admin.teams._form')
            <div class="flex justify-end gap-3 pt-4 border-t border-white/10">
                <button type="button" onclick="closeAdd()" class="px-5 py-2.5 rounded-xl bg-white/5 text-slate-300 hover:bg-white/10 text-sm font-medium transition">Cancel</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold transition shadow-lg shadow-indigo-600/25">
                    <i class="fa-solid fa-user-plus mr-1.5"></i>Add Member
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ════════════ EDIT MODAL ════════════ --}}
<div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closeEdit()"></div>
    <div class="relative w-full max-w-2xl bg-[#0f0f0f] border border-white/10 rounded-2xl shadow-2xl max-h-[92vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-white/10 sticky top-0 bg-[#0f0f0f] z-10">
            <h2 class="text-white font-bold text-lg flex items-center gap-2"><i class="fa-solid fa-pen-to-square text-indigo-400"></i> Edit Team Member</h2>
            <button onclick="closeEdit()" class="text-slate-400 hover:text-white text-xl"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="editForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf
            @include('admin.teams._form', ['isEdit' => true])
            <div class="flex justify-end gap-3 pt-4 border-t border-white/10">
                <button type="button" onclick="closeEdit()" class="px-5 py-2.5 rounded-xl bg-white/5 text-slate-300 hover:bg-white/10 text-sm font-medium transition">Cancel</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold transition shadow-lg shadow-indigo-600/25">
                    <i class="fa-solid fa-floppy-disk mr-1.5"></i>Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAdd() {
    document.getElementById('addModal').classList.remove('hidden');
    document.getElementById('addModal').classList.add('flex');
}
function closeAdd() {
    document.getElementById('addModal').classList.add('hidden');
    document.getElementById('addModal').classList.remove('flex');
}

function openEdit(data) {
    const form = document.getElementById('editForm');
    form.action = '/admin/teams/' + data.id;

    const fields = {
        name: data.name, role_en: data.role_en, role_bn: data.role_bn,
        social_linkedin: data.linkedin, social_github: data.github, social_twitter: data.twitter,
        email: data.email, system_role: data.system_role
    };
    Object.entries(fields).forEach(([k, v]) => {
        const el = form.querySelector('[name="' + k + '"]');
        if (el) el.value = v ?? '';
    });

    const statusEl = form.querySelector('[name="status"]');
    if (statusEl) statusEl.checked = data.status;

    // Show/hide password hint
    const pwHint = form.querySelector('#edit-pw-hint');
    if (pwHint) pwHint.classList.toggle('hidden', !data.has_login);

    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
}
function closeEdit() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
}

// Preview uploaded photo
function previewPhoto(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0] && preview) {
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.classList.remove('hidden'); };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
