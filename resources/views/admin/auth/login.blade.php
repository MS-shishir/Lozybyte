<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950/50 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | Lozybyte CMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="h-full flex items-center justify-center p-6 bg-[radial-gradient(ellipse_80%_80%_at_50%_-20%,rgba(99,102,241,0.15),rgba(255,255,255,0))]">

    <div class="w-full max-w-md">
        <!-- Logo Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-indigo-600/10 border border-indigo-500/20 text-indigo-400 text-3xl mb-4 shadow-[0_0_20px_rgba(99,102,241,0.15)]">
                <i class="fa-solid fa-cubes"></i>
            </div>
            <h2 class="text-2xl font-bold tracking-tight text-slate-50">Lozybyte Solutions</h2>
            <p class="text-sm text-slate-400 mt-2">Headless CMS Admin Portal</p>
        </div>

        <!-- Login Card -->
        <div class="bg-[#141C2F]/80 backdrop-blur-md/40 backdrop-blur-xl border border-slate-700/50 rounded-3xl p-8 shadow-2xl">
            <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Email field -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500">
                            <i class="fa-solid fa-envelope"></i>
                        </span>
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                            class="block w-full pl-10 pr-4 py-3 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-2xl text-slate-200 placeholder-slate-500 text-sm outline-none transition-all"
                            placeholder="name@company.com">
                    </div>
                    @error('email')
                    <span class="block text-xs text-rose-400 mt-2">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password field -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Password</label>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="block w-full pl-10 pr-4 py-3 bg-slate-950/50 border border-slate-700/50 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-2xl text-slate-200 placeholder-slate-500 text-sm outline-none transition-all"
                            placeholder="••••••••">
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full flex items-center justify-center px-4 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-slate-50 rounded-2xl text-sm font-semibold shadow-[0_0_20px_rgba(99,102,241,0.3)] transition-all">
                    Sign In
                    <i class="fa-solid fa-arrow-right-to-bracket ml-2"></i>
                </button>
            </form>
        </div>

        <div class="text-center mt-6">
            <p class="text-xs text-slate-500">&copy; {{ date('Y') }} Lozybyte Solutions. All rights reserved.</p>
        </div>
    </div>

</body>
</html>
