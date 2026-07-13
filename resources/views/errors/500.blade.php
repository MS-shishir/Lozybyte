<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Internal Server Error | Lozybyte</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', system-ui, -apple-system, sans-serif;
        }
    </style>
</head>
<body class="bg-[#050B18] min-h-full flex items-center justify-center p-6 text-slate-100 relative overflow-hidden">
    <!-- Background Glows -->
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-rose-500/5 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-purple-500/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.015)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.015)_1px,transparent_1px)] bg-[size:4rem_4rem] pointer-events-none"></div>

    <!-- Error Card -->
    <div class="bg-[#141C2F]/50 backdrop-blur-xl border border-white/[0.06] rounded-[32px] p-8 md:p-12 w-full max-w-lg shadow-2xl relative z-10 text-center flex flex-col items-center justify-center">
        <!-- Glow Line Indicator on Top of Card -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-32 h-[2px] bg-gradient-to-r from-transparent via-rose-500 to-transparent"></div>

        <!-- Vector Illustration: Failing Server Rack -->
        <svg class="w-72 h-48 mx-auto mb-4 opacity-95" viewBox="0 0 300 200" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="150" cy="100" r="80" fill="url(#serverGlow)" opacity="0.15" />
            
            <rect x="95" y="40" width="110" height="120" rx="16" fill="#111827" stroke="url(#borderGrad)" strokeWidth="2.5" />
            <rect x="103" y="48" width="94" height="104" rx="10" fill="#030712" opacity="0.8" />

            <rect x="110" y="56" width="80" height="24" rx="6" fill="#1f2937" stroke="#374151" strokeWidth="1.5" />
            <rect x="116" y="66" width="30" height="4" rx="1" fill="#4b5563" />
            <circle cx="172" cy="68" r="2.5" fill="#10b981" />
            <circle cx="182" cy="68" r="2.5" fill="#10b981" />

            <rect x="110" y="88" width="80" height="24" rx="6" fill="#1f2937" stroke="#ef4444" strokeWidth="1.5" />
            <rect x="116" y="98" width="30" height="4" rx="1" fill="#4b5563" />
            <circle cx="172" cy="100" r="2.5" fill="#ef4444" />
            <circle cx="182" cy="100" r="2.5" fill="#f59e0b" />

            <rect x="110" y="120" width="80" height="24" rx="6" fill="#1f2937" stroke="#374151" strokeWidth="1.5" />
            <rect x="116" y="130" width="30" height="4" rx="1" fill="#4b5563" />
            <circle cx="172" cy="132" r="2.5" fill="#10b981" />
            <circle cx="182" cy="132" r="2.5" fill="#3b82f6" />

            <g transform="translate(180, 110)">
                <circle cx="20" cy="20" r="18" fill="#ef4444" stroke="#fca5a5" strokeWidth="2" />
                <path d="M20 11 v10 M20 26 h.01" stroke="#ffffff" strokeWidth="3" strokeLinecap="round" />
            </g>

            <defs>
                <radialGradient id="serverGlow" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" transform="translate(150 100) rotate(90) scale(80)">
                    <stop offset="0%" stopColor="#ef4444" />
                    <stop offset="100%" stopColor="transparent" />
                </radialGradient>
                <linearGradient id="borderGrad" x1="95" y1="40" x2="205" y2="160" gradientUnits="userSpaceOnUse">
                    <stop offset="0%" stopColor="#ef4444" />
                    <stop offset="50%" stopColor="#4f46e5" />
                    <stop offset="100%" stopColor="#3b82f6" />
                </linearGradient>
            </defs>
        </svg>

        <!-- Status Badge -->
        <span class="px-3.5 py-1.5 rounded-full bg-rose-500/10 text-rose-450 border border-rose-500/20 text-[10px] font-bold uppercase tracking-widest mb-4">
            500 System Error
        </span>

        <!-- Message -->
        <h3 class="text-xl md:text-2xl font-bold mt-2 text-slate-50">System Malfunction</h3>
        <p class="text-slate-400 text-xs mt-2.5 max-w-sm leading-relaxed">
            Whoops! Something went wrong on our servers. Don't worry, our development team has been notified and we are fixing it right now.
        </p>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row items-center gap-4 mt-8 w-full sm:w-auto">
            <button onclick="location.reload()" class="w-full sm:w-auto px-6 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-slate-50 text-xs font-bold rounded-2xl transition-all duration-300 shadow-[0_0_20px_rgba(99,102,241,0.35)] hover:shadow-[0_0_25px_rgba(99,102,241,0.5)] transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                <i class="fa-solid fa-rotate-right"></i> Reload Page
            </button>
            <a href="/" class="w-full sm:w-auto px-6 py-3.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold rounded-2xl border border-white/[0.05] transition-all duration-300 transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                <i class="fa-solid fa-house"></i> Go Home
            </a>
        </div>
    </div>
</body>
</html>
