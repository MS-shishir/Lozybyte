@extends('admin.layouts.app')

@section('page_title', 'Account Management')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <!-- Header Page Description -->
    <div class="flex items-center gap-4 mb-8">
        <div class="w-12 h-12 rounded-2xl bg-indigo-600/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
            <i class="fa-solid fa-user-gear text-xl"></i>
        </div>
        <div>
            <h3 class="text-lg font-bold text-slate-100">Super Admin Profile Settings</h3>
            <p class="text-xs text-slate-400 mt-1">Manage your account email address, security credentials, and dashboard avatar.</p>
        </div>
    </div>

    <!-- Feedback Alerts -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-semibold rounded-2xl flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-base"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-semibold rounded-2xl">
            <div class="flex items-center gap-3 mb-2 font-bold">
                <i class="fa-solid fa-circle-exclamation text-base"></i>
                Please correct the following errors:
            </div>
            <ul class="list-disc pl-5 space-y-1 font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Profile Management Card -->
    <div class="bg-[#141C2F]/80 backdrop-blur-md border border-slate-700/50 rounded-3xl p-8 shadow-xl">
        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Avatar Preview & Selector -->
            <div class="flex flex-col sm:flex-row items-center gap-6 pb-6 border-b border-slate-700/50">
                <div class="relative shrink-0 group">
                    @if($user->avatar)
                        <img id="avatar-preview" src="{{ $user->avatar }}" alt="Avatar" class="w-24 h-24 rounded-full object-cover border-2 border-indigo-500 shadow-2xl transition-all duration-300 group-hover:scale-[1.03]">
                    @else
                        <div id="avatar-placeholder" class="w-24 h-24 rounded-full bg-indigo-600 flex items-center justify-center font-black text-3xl text-slate-100 uppercase shadow-2xl">
                            {{ substr($user->name, 0, 2) }}
                        </div>
                        <img id="avatar-preview" class="w-24 h-24 rounded-full object-cover border-2 border-indigo-500 shadow-2xl hidden">
                    @endif
                </div>
                <div class="space-y-2 text-center sm:text-left">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Profile Avatar Image</label>
                    <input type="file" name="avatar" id="avatar-file-input" onchange="previewFile()"
                        class="block w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-indigo-600/10 file:text-indigo-400 hover:file:bg-indigo-600/20 file:cursor-pointer">
                    <p class="text-[10px] text-slate-500">Supports PNG, JPG, or WEBP formats. Recommended size: 256x256px, max 2MB.</p>
                </div>
            </div>

            <!-- Email and Basic Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Display Name (Read Only)</label>
                    <input type="text" value="{{ $user->name }}" readonly
                        class="block w-full px-4 py-2.5 bg-slate-900/50 border border-slate-800 rounded-2xl text-slate-400 text-sm outline-none cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Role/Permissions (Read Only)</label>
                    <input type="text" value="{{ str_replace('_', ' ', $user->role) }}" readonly
                        class="block w-full px-4 py-2.5 bg-slate-900/50 border border-slate-800 rounded-2xl text-slate-400 text-sm outline-none uppercase tracking-widest text-xs font-bold cursor-not-allowed">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none transition-colors">
                </div>
            </div>

            <!-- Password Reset Configuration -->
            <div class="space-y-4 pt-6 border-t border-slate-700/50">
                <h4 class="text-xs font-bold text-slate-300 uppercase tracking-widest flex items-center"><i class="fa-solid fa-lock-open mr-2 text-indigo-500"></i> Reset Password (Leave blank to keep current)</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">New Password</label>
                        <input type="password" name="password" placeholder="••••••••"
                            class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Confirm New Password</label>
                        <input type="password" name="password_confirmation" placeholder="••••••••"
                            class="block w-full px-4 py-2.5 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 rounded-2xl text-slate-200 text-sm outline-none transition-colors">
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-700/50">
                <a href="{{ route('admin.dashboard') }}" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-2xl transition-colors">Cancel</a>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-slate-50 text-xs font-semibold rounded-2xl transition-all shadow-[0_0_15px_rgba(99,102,241,0.2)]">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewFile() {
        const preview = document.getElementById('avatar-preview');
        const placeholder = document.getElementById('avatar-placeholder');
        const file = document.getElementById('avatar-file-input').files[0];
        const reader = new FileReader();

        reader.onloadend = function () {
            preview.src = reader.result;
            preview.classList.remove('hidden');
            if (placeholder) {
                placeholder.classList.add('hidden');
            }
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "";
            preview.classList.add('hidden');
            if (placeholder) {
                placeholder.classList.remove('hidden');
            }
        }
    }
</script>
@endsection
