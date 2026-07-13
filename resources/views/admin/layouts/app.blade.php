@php
    $siteSettings = \App\Models\Setting::first();
@endphp
<!DOCTYPE html>
<html lang="en" class="h-full bg-[#0a0a0a] text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lozybyte | Admin Panel</title>
    @if($siteSettings && $siteSettings->favicon_path)
        <link rel="icon" type="image/webp" href="{{ $siteSettings->favicon_path }}">
    @endif
    <!-- Tailwind CSS (Tailwind CDN for reliable standalone preview and admin styling) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', 'Inter', system-ui, -apple-system, sans-serif;
        }
        /* Custom Premium scrollbar styles */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.15);
            border-radius: 999px;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.12);
            border-radius: 999px;
            border: 1px solid transparent;
            background-clip: padding-box;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.25);
            border-radius: 999px;
            border: 1px solid transparent;
            background-clip: padding-box;
        }
        
        /* Firefox Scrollbar support */
        * {
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.12) rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body class="h-full flex flex-col md:flex-row">

    <!-- Sidebar -->
    <aside class="w-64 border-r border-white/[0.08] bg-[#000000] flex flex-col transition-all duration-300">
        <div>
            <!-- Sidebar Header -->
            <div class="h-16 flex items-center px-6 border-b border-white/10">
                @if($siteSettings && $siteSettings->logo_path)
                    <img src="{{ $siteSettings->logo_path }}" alt="Logo" class="h-7 w-auto mr-2.5 object-contain rounded-lg">
                @else
                    <i class="fa-solid fa-cubes text-indigo-500 text-2xl mr-3"></i>
                @endif
                <span class="font-bold text-base tracking-wider text-slate-50 truncate">{{ $siteSettings ? $siteSettings->company_name : 'LOZYBYTE' }}</span>
            </div>

            <!-- User Information Box -->
            <div class="p-4 border-b border-white/10 bg-[#0a0a0a]/80 backdrop-blur-md/50">
                <div class="flex items-center">
                    @if(auth()->user()->avatar)
                        <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover border border-indigo-500/50">
                    @else
                        <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center font-bold text-slate-100 uppercase">
                            {{ substr(auth()->user()->name, 0, 2) }}
                        </div>
                    @endif
                    <div class="ml-3 overflow-hidden">
                        <p class="text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
                        <span class="inline-block px-2 py-0.5 mt-0.5 text-xs font-medium rounded-full bg-indigo-500/10 text-indigo-400 capitalize">
                            {{ str_replace('_', ' ', auth()->user()->role) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-chart-line w-6 text-base"></i>
                    Dashboard
                </a>

                <!-- Visitor Analytics Link (Super Admin & Content Manager) -->
                @if(in_array(auth()->user()->role, ['super_admin', 'content_manager']))
                <a href="{{ route('admin.analytics.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.analytics.*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-chart-simple w-6 text-base"></i>
                    Visitor Analytics
                </a>
                @endif

                <!-- Leads Link (Sales Manager & Super Admin) -->
                @if(in_array(auth()->user()->role, ['super_admin', 'sales_manager']))
                <a href="{{ route('admin.leads.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.leads.*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-envelope-open-text w-6 text-base"></i>
                    Leads Inbox
                </a>
                @endif

                <!-- SaaS Products Link (SaaS Manager, Content Manager & Super Admin) -->
                @if(in_array(auth()->user()->role, ['super_admin', 'content_manager', 'saas_manager']))
                <a href="{{ route('admin.products.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.products.*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5 transition-colors' }}">
                    <i class="fa-solid fa-box-open w-6 text-base"></i>
                    SaaS Products
                </a>
                @endif

                <!-- Content Links (Content Manager & Super Admin) -->
                @if(in_array(auth()->user()->role, ['super_admin', 'content_manager']))
                <div class="pt-4 pb-2 text-[11px] font-medium text-slate-500 tracking-wider px-4">Content CMS</div>
                
                <a href="{{ route('admin.services.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.services.*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5 transition-colors' }}">
                    <i class="fa-solid fa-screwdriver-wrench w-6 text-base"></i>
                    Services
                </a>

                <a href="{{ route('admin.industries.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.industries.*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5 transition-colors' }}">
                    <i class="fa-solid fa-building-user w-6 text-base"></i>
                    Industries
                </a>

                <a href="{{ route('admin.pricing.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.pricing.*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5 transition-colors' }}">
                    <i class="fa-solid fa-tags w-6 text-base"></i>
                    Pricing Plans
                </a>

                <a href="{{ route('admin.portfolios.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.portfolios.*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5 transition-colors' }}">
                    <i class="fa-solid fa-folder-open w-6 text-base"></i>
                    Case Studies
                </a>

                <a href="{{ route('admin.testimonials.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.testimonials.*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5 transition-colors' }}">
                    <i class="fa-solid fa-comment-dots w-6 text-base"></i>
                    Testimonials
                </a>

                <a href="{{ route('admin.teams.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.teams.*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5 transition-colors' }}">
                    <i class="fa-solid fa-users w-6 text-base"></i>
                    Team Members
                </a>

                <a href="{{ route('admin.clients.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.clients.*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5 transition-colors' }}">
                    <i class="fa-solid fa-circle-nodes w-6 text-base"></i>
                    Client Logos
                </a>

                <a href="{{ route('admin.blog.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.blog.*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-blog w-6 text-base"></i>
                    Blog System
                </a>

                <a href="{{ route('admin.faqs.index') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.faqs.*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-circle-question w-6 text-base"></i>
                    FAQs
                </a>

                <div class="pt-4 pb-2 text-[11px] font-medium text-slate-500 tracking-wider px-4">System</div>

                <a href="{{ route('admin.profile') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.profile') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5 transition-colors' }}">
                    <i class="fa-solid fa-user-gear w-6 text-base"></i>
                    Account Management
                </a>

                <a href="{{ route('admin.founder-and-vision.edit') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.founder-and-vision.*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5 transition-colors' }}">
                    <i class="fa-solid fa-video w-6 text-base"></i>
                    Founder & Vision
                </a>

                <a href="{{ route('admin.settings.edit') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5 transition-colors' }}">
                    <i class="fa-solid fa-gears w-6 text-base"></i>
                    Global Settings
                </a>
                @endif
            </nav>
        </div>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-white/10 bg-[#0a0a0a]">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center px-4 py-2.5 bg-rose-600/10 text-rose-400 hover:bg-rose-600 hover:text-slate-50 rounded-lg text-sm font-medium transition-all">
                    <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i>
                    Sign Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col min-w-0 bg-[#0a0a0a] overflow-y-auto">
        <!-- Top Nav Header -->
        <header class="h-14 border-b border-white/[0.08] bg-[#0a0a0a]/80 backdrop-blur-md flex items-center justify-between px-6 shrink-0 sticky top-0 z-10">
            <div class="flex items-center">
                <h1 class="font-semibold text-[15px] text-slate-200">@yield('page_title', 'CMS Dashboard')</h1>
            </div>
            <div class="flex items-center space-y-0 space-x-4">
                <a href="{{ route('admin.live-editor') }}" target="_blank" class="px-4 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-[12px] font-bold text-slate-50 flex items-center transition-all shadow-lg shadow-indigo-600/30">
                    <i class="fa-solid fa-wand-magic-sparkles mr-2 animate-pulse"></i>
                    Go to Live Visual Editor
                </a>
            </div>
        </header>

        <!-- Dynamic Content Body -->
        <div class="p-8">
            <!-- Alert Messages -->
            @if(session('success'))
            <div class="mb-6 p-4 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center text-sm">
                <i class="fa-solid fa-circle-check text-base mr-3"></i>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 p-4 rounded-lg bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center text-sm">
                <i class="fa-solid fa-circle-exclamation text-base mr-3"></i>
                {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Global Custom Confirmation Modal -->
    <div id="global-confirm-modal" class="fixed inset-0 bg-slate-950/50/80 backdrop-blur-sm z-50 flex items-center justify-center p-6 hidden opacity-0 transition-all duration-200 ease-out transform scale-95 origin-center">
        <div class="bg-[#141C2F]/90 backdrop-blur-md border border-slate-700/50 rounded-3xl w-full max-w-md shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="h-14 flex items-center gap-3 px-6 border-b border-white/[0.06] bg-slate-950/20">
                <div class="w-8 h-8 rounded-full bg-rose-500/10 text-rose-500 flex items-center justify-center border border-rose-500/20">
                    <i class="fa-solid fa-circle-exclamation text-sm"></i>
                </div>
                <h4 class="font-bold text-slate-100 text-sm">Confirm Action</h4>
            </div>
            <!-- Content -->
            <div class="p-6">
                <p id="global-confirm-message" class="text-xs text-slate-350 leading-relaxed">Are you sure you want to proceed with this action?</p>
            </div>
            <!-- Footer -->
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-white/[0.06] bg-slate-950/15">
                <button type="button" id="global-confirm-cancel" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl transition-all">Cancel</button>
                <button type="button" id="global-confirm-proceed" class="px-4 py-2 bg-rose-600 hover:bg-rose-500 text-white text-xs font-semibold rounded-xl transition-all shadow-[0_0_15px_rgba(239,68,68,0.2)]">Confirm Delete</button>
            </div>
        </div>
    </div>

    <!-- Custom Premium Dropdowns Handler -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const initCustomDropdowns = () => {
                const selects = document.querySelectorAll("select");
                
                selects.forEach((select) => {
                    // Skip hidden selects or already customized ones
                    if (select.dataset.customDropdown === "true") return;
                    if (select.classList.contains("hidden") || select.style.display === "none") return;
                    
                    // Mark as processed
                    select.dataset.customDropdown = "true";
                    
                    // Check if it's a status dropdown
                    const isStatusSelect = select.classList.contains("status-select");
                    
                    // Create wrapper
                    const wrapper = document.createElement("div");
                    wrapper.className = "relative custom-dropdown-wrapper w-full";
                    select.parentNode.insertBefore(wrapper, select);
                    wrapper.appendChild(select);
                    
                    // Hide original select
                    select.style.display = "none";
                    select.classList.add("hidden");
                    
                    // Determine classes based on original select size
                    let triggerClass = "w-full flex items-center justify-between px-4 py-2.5 bg-slate-950/60 border border-slate-700/50 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-2xl text-slate-200 text-sm outline-none cursor-pointer transition-all";
                    let optionClass = "w-full text-left px-3.5 py-2 rounded-xl text-xs text-slate-300 hover:text-white hover:bg-white/[0.06] transition-all truncate flex items-center justify-between";
                    let activeOptionClass = "w-full text-left px-3.5 py-2 rounded-xl text-xs bg-indigo-600/20 text-indigo-300 font-semibold transition-all truncate flex items-center justify-between";
                    
                    if (select.classList.contains("text-xs") || select.classList.contains("py-2") || select.classList.contains("py-1.5")) {
                        triggerClass = "w-full flex items-center justify-between px-4 py-2 bg-slate-950/60 border border-slate-700/50 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl text-slate-200 text-xs outline-none cursor-pointer transition-all";
                    }
                    
                    // Create trigger button
                    const trigger = document.createElement("button");
                    trigger.type = "button";
                    trigger.className = triggerClass;
                    
                    const triggerText = document.createElement("span");
                    triggerText.className = "truncate pr-2";
                    const selectedOption = select.options[select.selectedIndex];
                    triggerText.innerText = selectedOption ? selectedOption.text : "Select Option";
                    
                    const arrow = document.createElement("i");
                    arrow.className = "fa-solid fa-chevron-down text-slate-400 text-xs transition-transform duration-300 pointer-events-none";
                    
                    trigger.appendChild(triggerText);
                    trigger.appendChild(arrow);
                    wrapper.appendChild(trigger);
                    
                    // Function to style trigger dynamically if it's a status dropdown
                    const updateTriggerStyle = () => {
                        if (isStatusSelect) {
                            const val = select.value;
                            // Set base status classes
                            trigger.className = "w-full flex items-center justify-between px-3 py-1.5 rounded-xl text-xs font-semibold outline-none cursor-pointer transition-all border";
                            if (val === "new") {
                                trigger.className += " bg-indigo-500/10 text-indigo-400 border-indigo-500/30 hover:bg-indigo-500/20";
                            } else if (val === "contacted") {
                                trigger.className += " bg-amber-500/10 text-amber-400 border-amber-500/30 hover:bg-amber-500/20";
                            } else if (val === "converted") {
                                trigger.className += " bg-emerald-500/10 text-emerald-400 border-emerald-500/30 hover:bg-emerald-500/20";
                            } else if (val === "lost") {
                                trigger.className += " bg-rose-500/10 text-rose-400 border-rose-500/30 hover:bg-rose-500/20";
                            } else {
                                trigger.className += " bg-slate-900/60 text-slate-300 border-slate-700/50 hover:bg-slate-900/80";
                            }
                        }
                    };
                    
                    updateTriggerStyle();
                    
                    // Create options dropdown container
                    const optionsContainer = document.createElement("div");
                    optionsContainer.className = "absolute left-0 right-0 mt-2 bg-[#141C2F]/95 backdrop-blur-md border border-slate-700/50 rounded-2xl py-1.5 px-1.5 shadow-2xl space-y-0.5 z-50 hidden opacity-0 scale-95 transition-all duration-200 ease-out transform origin-top";
                    
                    // Populate options
                    const updateOptionsList = () => {
                        optionsContainer.innerHTML = "";
                        Array.from(select.options).forEach((option, idx) => {
                            const optBtn = document.createElement("button");
                            optBtn.type = "button";
                            optBtn.className = option.selected ? activeOptionClass : optionClass;
                            
                            if (isStatusSelect) {
                                const dot = document.createElement("span");
                                dot.className = "w-2 h-2 rounded-full mr-2 shrink-0 inline-block";
                                if (option.value === "new") dot.classList.add("bg-indigo-400");
                                else if (option.value === "contacted") dot.classList.add("bg-amber-400");
                                else if (option.value === "converted") dot.classList.add("bg-emerald-400");
                                else if (option.value === "lost") dot.classList.add("bg-rose-400");
                                else dot.classList.add("bg-slate-400");
                                
                                optBtn.innerText = "";
                                optBtn.appendChild(dot);
                                optBtn.appendChild(document.createTextNode(option.text));
                            } else {
                                optBtn.innerText = option.text;
                            }
                            
                            optBtn.addEventListener("click", (e) => {
                                e.stopPropagation();
                                select.selectedIndex = idx;
                                select.value = option.value;
                                select.dispatchEvent(new Event('change'));
                                triggerText.innerText = option.text;
                                updateTriggerStyle();
                                closeDropdown();
                            });
                            
                            optionsContainer.appendChild(optBtn);
                        });
                    };
                    
                    updateOptionsList();
                    wrapper.appendChild(optionsContainer);
                    
                    // Helper to open/close
                    const openDropdown = () => {
                        // Close other custom dropdowns
                        document.querySelectorAll(".custom-dropdown-wrapper .z-50").forEach(div => {
                            if (div !== optionsContainer) {
                                div.classList.add("hidden", "opacity-0", "scale-95");
                                const otherArrow = div.previousSibling.querySelector("i");
                                if (otherArrow) otherArrow.classList.remove("rotate-180");
                            }
                        });
                        
                        // Dynamically update option list before showing in case options changed
                        updateOptionsList();
                        
                        // Check screen position to see if we should open upwards
                        const rect = wrapper.getBoundingClientRect();
                        const spaceBelow = window.innerHeight - rect.bottom;
                        const dropdownHeight = 180; // approximate max height if not rendered yet
                        
                        if (spaceBelow < dropdownHeight && rect.top > dropdownHeight) {
                            // Open upwards
                            optionsContainer.classList.remove("mt-2", "origin-top");
                            optionsContainer.classList.add("mb-2", "bottom-full", "origin-bottom");
                        } else {
                            // Open downwards
                            optionsContainer.classList.remove("mb-2", "bottom-full", "origin-bottom");
                            optionsContainer.classList.add("mt-2", "origin-top");
                        }
                        
                        optionsContainer.classList.remove("hidden");
                        setTimeout(() => {
                            optionsContainer.classList.remove("opacity-0", "scale-95");
                        }, 10);
                        arrow.classList.add("rotate-180");
                        if (!isStatusSelect) {
                            trigger.classList.add("border-indigo-500", "ring-2", "ring-indigo-500/20");
                        }
                    };
                    
                    const closeDropdown = () => {
                        optionsContainer.classList.add("opacity-0", "scale-95");
                        arrow.classList.remove("rotate-180");
                        if (!isStatusSelect) {
                            trigger.classList.remove("border-indigo-500", "ring-2", "ring-indigo-500/20");
                        }
                        setTimeout(() => {
                            optionsContainer.classList.add("hidden");
                        }, 200);
                    };
                    
                    trigger.addEventListener("click", (e) => {
                        e.stopPropagation();
                        const isHidden = optionsContainer.classList.contains("hidden");
                        if (isHidden) openDropdown();
                        else closeDropdown();
                    });
                    
                    // Listen to original change event to sync text
                    select.addEventListener("change", () => {
                        const opt = select.options[select.selectedIndex];
                        if (opt) triggerText.innerText = opt.text;
                        updateTriggerStyle();
                        
                        // Sync buttons classes
                        optionsContainer.querySelectorAll("button").forEach((btn, idx) => {
                            if (select.options[idx]) {
                                btn.className = select.options[idx].selected ? activeOptionClass : optionClass;
                            }
                        });
                    });
                    
                    // Close on click outside
                    document.addEventListener("click", (e) => {
                        if (!wrapper.contains(e.target)) {
                            closeDropdown();
                        }
                    });
                });
            };
            
            initCustomDropdowns();
            
            // Re-run in case dynamic modals or elements are loaded (optional helper)
            window.reinitCustomDropdowns = initCustomDropdowns;

            // Global Form Submit Loading Handler
            document.addEventListener("submit", (e) => {
                if (e.defaultPrevented) return;
                
                const form = e.target;
                
                // Ignore GET forms (like search/filters)
                if (form.method && form.method.toLowerCase() === 'get') {
                    return;
                }
                
                // Prevent duplicate submission triggers
                if (form.dataset.submitting === "true") {
                    e.preventDefault();
                    return;
                }
                
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn && !submitBtn.disabled) {
                    // Mark form as submitting
                    form.dataset.submitting = "true";
                    
                    // Disable button and show spinner after a microtask to allow form validations to run
                    setTimeout(() => {
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                        
                        const rect = submitBtn.getBoundingClientRect();
                        if (rect.width > 0) {
                            submitBtn.style.width = rect.width + 'px';
                        }
                        
                        submitBtn.innerHTML = `
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        `;
                    }, 0);
                }
            });

            // Global custom confirmation modal logic
            const confirmModal = document.getElementById("global-confirm-modal");
            const confirmMessage = document.getElementById("global-confirm-message");
            const confirmCancel = document.getElementById("global-confirm-cancel");
            const confirmProceed = document.getElementById("global-confirm-proceed");
            let activeConfirmForm = null;

            const openConfirmModal = (message, form) => {
                activeConfirmForm = form;
                confirmMessage.innerText = message;
                
                // Show modal
                confirmModal.classList.remove("hidden");
                setTimeout(() => {
                    confirmModal.classList.remove("opacity-0", "scale-95");
                }, 10);
            };

            const closeConfirmModal = () => {
                confirmModal.classList.add("opacity-0", "scale-95");
                setTimeout(() => {
                    confirmModal.classList.add("hidden");
                    activeConfirmForm = null;
                }, 200);
            };

            confirmCancel.addEventListener("click", closeConfirmModal);
            confirmModal.addEventListener("click", (e) => {
                if (e.target === confirmModal) closeConfirmModal();
            });

            confirmProceed.addEventListener("click", () => {
                if (activeConfirmForm) {
                    activeConfirmForm.dataset.confirmed = "true";
                    
                    // Show global submit loader on the form submit button if it exists
                    const submitBtn = activeConfirmForm.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                        const rect = submitBtn.getBoundingClientRect();
                        if (rect.width > 0) submitBtn.style.width = rect.width + 'px';
                        submitBtn.innerHTML = `
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        `;
                    }
                    
                    activeConfirmForm.submit();
                    closeConfirmModal();
                }
            });

            // Find all forms with confirm inside inline onsubmit and replace
            const hookConfirmForms = () => {
                document.querySelectorAll("form").forEach((form) => {
                    const inlineOnsubmit = form.getAttribute("onsubmit");
                    if (inlineOnsubmit && inlineOnsubmit.includes("confirm(")) {
                        // Extract confirm message
                        const match = inlineOnsubmit.match(/confirm\(['"](.*?)['"]\)/);
                        const message = match ? match[1] : "Are you sure you want to proceed?";
                        
                        // Strip native confirm
                        form.removeAttribute("onsubmit");
                        
                        // Prevent default submit and trigger our premium modal
                        form.addEventListener("submit", (e) => {
                            if (form.dataset.confirmed !== "true") {
                                e.preventDefault();
                                openConfirmModal(message, form);
                            }
                        });
                    }
                });
            };

            hookConfirmForms();
            window.rehookConfirmForms = hookConfirmForms;
        });
    </script>
</body>
</html>
