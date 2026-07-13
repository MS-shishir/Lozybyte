<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>429 - Too Many Requests | Lozybyte</title>
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
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-amber-500/5 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-purple-500/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.015)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.015)_1px,transparent_1px)] bg-[size:4rem_4rem] pointer-events-none"></div>

    <!-- Error Card -->
    <div class="bg-[#141C2F]/50 backdrop-blur-xl border border-white/[0.06] rounded-[32px] p-8 md:p-12 w-full max-w-lg shadow-2xl relative z-10 text-center flex flex-col items-center justify-center">
        <!-- Glow Line Indicator on Top of Card -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-32 h-[2px] bg-gradient-to-r from-transparent via-amber-500 to-transparent"></div>

        <!-- Vector Illustration: Speedometer / Rate Limits -->
        <svg class="w-72 h-48 mx-auto mb-4 opacity-95" viewBox="0 0 300 200" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="150" cy="110" r="80" fill="url(#speedGlow)" opacity="0.12" />
            <!-- Gauge arc -->
            <path d="M90 140 A 70 70 0 1 1 210 140" stroke="#1f2937" strokeWidth="12" strokeLinecap="round" fill="none" />
            <path d="M90 140 A 70 70 0 0 1 180 60" stroke="url(#gaugeGrad)" strokeWidth="12" strokeLinecap="round" fill="none" />
            <!-- Gauge Needle -->
            <path d="M150 110 L 195 75" stroke="#ef4444" strokeWidth="4" strokeLinecap="round" />
            <circle cx="150" cy="110" r="10" fill="#ef4444" />
            <circle cx="150" cy="110" r="5" fill="#ffffff" />
            <!-- Speed indicators -->
            <circle cx="105" cy="100" r="3" fill="#10b981" />
            <circle cx="150" cy="55" r="3" fill="#f59e0b" />
            <circle cx="195" cy="100" r="3" fill="#ef4444" class="animate-ping" />
            <defs>
                <radialGradient id="speedGlow" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" transform="translate(150 110) rotate(90) scale(80)">
                    <stop offset="0%" stopColor="#f59e0b" />
                    <stop offset="100%" stopColor="transparent" />
                </radialGradient>
                <linearGradient id="gaugeGrad" x1="90" y1="140" x2="180" y2="60" gradientUnits="userSpaceOnUse">
                    <stop offset="0%" stopColor="#10b981" />
                    <stop offset="50%" stopColor="#f59e0b" />
                    <stop offset="100%" stopColor="#ef4444" />
                </linearGradient>
            </defs>
        </svg>

        <!-- Status Badge -->
        <span class="px-3.5 py-1.5 rounded-full bg-amber-500/10 text-amber-450 border border-amber-500/20 text-[10px] font-bold uppercase tracking-widest mb-4">
            429 Rate Limited
        </span>

        <!-- Message -->
        <h3 class="text-xl md:text-2xl font-bold mt-2 text-slate-50">Slowing Down</h3>
        <p class="text-slate-400 text-xs mt-2.5 max-w-sm leading-relaxed">
            You have sent too many requests in a short period of time. Please take a deep breath and wait a moment before trying again.
        </p>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row items-center gap-4 mt-8 w-full sm:w-auto">
            <button onclick="history.back()" class="w-full sm:w-auto px-6 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-slate-50 text-xs font-bold rounded-2xl transition-all duration-300 shadow-[0_0_20px_rgba(99,102,241,0.35)] hover:shadow-[0_0_25px_rgba(99,102,241,0.5)] transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Go Back
            </button>
            <a href="/" class="w-full sm:w-auto px-6 py-3.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold rounded-2xl border border-white/[0.05] transition-all duration-300 transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                <i class="fa-solid fa-house"></i> Go Home
            </a>
        </div>
    </div>
</body>
</html>
