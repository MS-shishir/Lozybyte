{{-- Reusable form partial for Team Add & Edit modals --}}
@php $isEdit = $isEdit ?? false; @endphp

{{-- Section: Profile --}}
<div class="space-y-4">
    <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Profile</p>

    <div>
        <label class="block text-xs text-slate-400 mb-1.5 font-medium">Full Name <span class="text-rose-400">*</span></label>
        <input name="name" type="text" placeholder="e.g. Jane Doe"
            class="w-full bg-white/[0.05] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition">
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-xs text-slate-400 mb-1.5 font-medium">Role Title (EN)</label>
            <input name="role_en" type="text" placeholder="e.g. Lead Engineer"
                class="w-full bg-white/[0.05] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition">
        </div>
        <div>
            <label class="block text-xs text-slate-400 mb-1.5 font-medium">Role Title (BN)</label>
            <input name="role_bn" type="text" placeholder="e.g. লিড ইঞ্জিনিয়ার"
                class="w-full bg-white/[0.05] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition">
        </div>
    </div>
</div>

{{-- Section: Social Links --}}
<div class="space-y-4">
    <p class="text-xs font-bold uppercase tracking-widest text-slate-500 border-t border-white/[0.06] pt-4">Social Links <span class="text-slate-600 font-normal normal-case">optional</span></p>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
            <label class="block text-xs text-slate-400 mb-1.5 font-medium">LinkedIn</label>
            <input name="social_linkedin" type="url" placeholder="https://linkedin.com/in/..."
                class="w-full bg-white/[0.05] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition">
        </div>
        <div>
            <label class="block text-xs text-slate-400 mb-1.5 font-medium">GitHub</label>
            <input name="social_github" type="url" placeholder="https://github.com/..."
                class="w-full bg-white/[0.05] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition">
        </div>
        <div>
            <label class="block text-xs text-slate-400 mb-1.5 font-medium">Twitter / X</label>
            <input name="social_twitter" type="url" placeholder="https://x.com/..."
                class="w-full bg-white/[0.05] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition">
        </div>
    </div>
</div>

{{-- Section: Admin Account --}}
<div class="space-y-4">
    <p class="text-xs font-bold uppercase tracking-widest text-slate-500 border-t border-white/[0.06] pt-4">Admin Account <span class="text-slate-600 font-normal normal-case">optional — link a login</span></p>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-xs text-slate-400 mb-1.5 font-medium">Account Email</label>
            <input name="email" type="email" placeholder="member@lozybyte.com"
                class="w-full bg-white/[0.05] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition">
        </div>
        <div>
            <label class="block text-xs text-slate-400 mb-1.5 font-medium">System Role</label>
            <select name="system_role"
                class="w-full bg-white/[0.05] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 transition">
                <option value="">— No account —</option>
                <option value="super_admin">Super Admin</option>
                <option value="content_manager">Content Manager</option>
                <option value="sales_manager">Sales Manager</option>
                <option value="saas_manager">SaaS Manager</option>
            </select>
        </div>
    </div>

    <div>
        <label class="block text-xs text-slate-400 mb-1.5 font-medium">Password</label>
        <input name="password" type="password" placeholder="{{ $isEdit ? 'Leave blank to keep current' : 'Auto-generated if empty' }}"
            class="w-full bg-white/[0.05] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition">
        @if($isEdit)
            <p id="edit-pw-hint" class="hidden mt-1.5 text-[11px] text-slate-500">
                <i class="fa-solid fa-circle-info mr-1"></i>Only fill this to change the linked account's password.
            </p>
        @endif
    </div>
</div>

{{-- Section: Photo & Status --}}
<div class="space-y-4">
    <p class="text-xs font-bold uppercase tracking-widest text-slate-500 border-t border-white/[0.06] pt-4">Photo & Status</p>

    <div>
        <label class="block text-xs text-slate-400 mb-1.5 font-medium">Profile Photo</label>
        <input name="image" type="file" accept="image/*" onchange="previewPhoto(this,'team-photo-preview')"
            class="w-full bg-white/[0.05] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-indigo-600 file:text-white file:text-xs file:font-semibold hover:file:bg-indigo-500 transition">
        <img id="team-photo-preview" class="hidden mt-3 w-16 h-16 rounded-2xl object-cover border border-white/10" alt="Profile preview">
    </div>

    <label class="flex items-center gap-2.5 cursor-pointer select-none">
        <div class="relative">
            <input name="status" type="checkbox" class="sr-only peer" checked>
            <div class="w-10 h-5 bg-white/10 rounded-full peer-checked:bg-emerald-500 transition-colors"></div>
            <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
        </div>
        <span class="text-xs text-slate-300 font-medium">Active (Visible on site)</span>
    </label>
</div>
