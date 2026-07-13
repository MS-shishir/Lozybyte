@extends('admin.layouts.app')

@section('page_title', 'Global  Settings')

@section('content')
<form class="max-w-5xl mx-auto pb-12" action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
    @csrf

    <!-- Form Navigation Tabs -->
    <div class="border-b border-slate-700/50 shrink-0">
        <nav class="flex space-x-8" aria-label="Tabs" id="settings-tabs">
            <button type="button" onclick="switchTab('tab-company')" id="btn-tab-company" class="border-b-2 border-indigo-500 text-indigo-400 pb-4 px-1 text-sm font-semibold transition-all">
                <i class="fa-solid fa-building mr-2"></i> Company Info
            </button>
            <button type="button" onclick="switchTab('tab-theme')" id="btn-tab-theme" class="border-b-2 border-transparent text-slate-400 hover:text-slate-300 hover:border-slate-300 pb-4 px-1 text-sm font-semibold transition-all">
                <i class="fa-solid fa-palette mr-2"></i> Dynamic Theme Builder
            </button>
            <button type="button" onclick="switchTab('tab-homepage')" id="btn-tab-homepage" class="border-b-2 border-transparent text-slate-400 hover:text-slate-300 hover:border-slate-300 pb-4 px-1 text-sm font-semibold transition-all">
                <i class="fa-solid fa-house-laptop mr-2"></i> Homepage Sections
            </button>
            <button type="button" onclick="switchTab('tab-seo')" id="btn-tab-seo" class="border-b-2 border-transparent text-slate-400 hover:text-slate-300 hover:border-slate-300 pb-4 px-1 text-sm font-semibold transition-all">
                <i class="fa-solid fa-magnifying-glass-chart mr-2"></i> SEO CMS
            </button>
        </nav>
    </div>

    <!-- Tab 1: Company Info -->
    <div id="tab-company" class="tab-pane space-y-6">
        <div class="bg-[#000000] border border-white/[0.08] rounded-xl p-8 space-y-8">
            <h3 class="text-[14px] font-semibold text-white mb-6">General Business Details</h3>

            <!-- Company Name EN, BN, JA -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <div>
                    <label class="block text-[13px] font-medium text-slate-400 mb-2">Company Name (English)</label>
                    <input type="text" name="company_name_en" value="{{ $settings->getTranslations('company_name')['en'] ?? '' }}"
                        class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-white/30 rounded-lg text-slate-200 text-[13px] outline-none transition-colors hover:border-white/20">
                </div>
                <div>
                    <label class="block text-[13px] font-medium text-slate-400 mb-2">Company Name (Bangla)</label>
                    <input type="text" name="company_name_bn" value="{{ $settings->getTranslations('company_name')['bn'] ?? '' }}"
                        class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-white/30 rounded-lg text-slate-200 text-[13px] outline-none transition-colors hover:border-white/20">
                </div>
                
            </div>

            <!-- Tagline EN, BN, JA -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <div>
                    <label class="block text-[13px] font-medium text-slate-400 mb-2">Tagline (English)</label>
                    <input type="text" name="tagline_en" value="{{ $settings->getTranslations('tagline')['en'] ?? '' }}"
                        class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-white/30 rounded-lg text-slate-200 text-[13px] outline-none transition-colors hover:border-white/20">
                </div>
                <div>
                    <label class="block text-[13px] font-medium text-slate-400 mb-2">Tagline (Bangla)</label>
                    <input type="text" name="tagline_bn" value="{{ $settings->getTranslations('tagline')['bn'] ?? '' }}"
                        class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-white/30 rounded-lg text-slate-200 text-[13px] outline-none transition-colors hover:border-white/20">
                </div>
                
            </div>

            <!-- Contact Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <div>
                    <label class="block text-[13px] font-medium text-slate-400 mb-2">Corporate Email</label>
                    <input type="email" name="email" value="{{ $settings->email }}"
                        class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-white/30 rounded-lg text-slate-200 text-[13px] outline-none transition-colors hover:border-white/20">
                </div>
                <div>
                    <label class="block text-[13px] font-medium text-slate-400 mb-2">Corporate Phone</label>
                    <input type="text" name="phone" value="{{ $settings->phone }}"
                        class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-white/30 rounded-lg text-slate-200 text-[13px] outline-none transition-colors hover:border-white/20">
                </div>
            </div>

            <!-- Address EN, BN, JA -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <div>
                    <label class="block text-[13px] font-medium text-slate-400 mb-2">Address (English)</label>
                    <textarea name="address_en" rows="2" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-white/30 rounded-lg text-slate-200 text-[13px] outline-none transition-colors hover:border-white/20">{{ $settings->getTranslations('address')['en'] ?? '' }}</textarea>
                </div>
                <div>
                    <label class="block text-[13px] font-medium text-slate-400 mb-2">Address (Bangla)</label>
                    <textarea name="address_bn" rows="2" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-white/30 rounded-lg text-slate-200 text-[13px] outline-none transition-colors hover:border-white/20">{{ $settings->getTranslations('address')['bn'] ?? '' }}</textarea>
                </div>
                
            </div>

            <!-- Media files -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <div>
                    <label class="block text-[13px] font-medium text-slate-400 mb-2">Corporate Logo</label>
                    @if($settings->logo_path)
                    <div class="mb-3 flex items-center">
                        <img src="{{ $settings->logo_path }}" alt="Logo" class="h-10 border border-slate-700/50 rounded px-2 bg-slate-950/50">
                        <span class="text-xs text-slate-400 ml-3 truncate">{{ $settings->logo_path }}</span>
                    </div>
                    @endif
                    <input type="file" name="logo" class="block w-full text-[13px] text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-[13px] file:font-medium file:bg-white/10 file:text-white hover:file:bg-white/20 transition-colors cursor-pointer">
                </div>
                <div>
                    <label class="block text-[13px] font-medium text-slate-400 mb-2">Favicon</label>
                    @if($settings->favicon_path)
                    <div class="mb-3 flex items-center">
                        <img src="{{ $settings->favicon_path }}" alt="Favicon" class="h-8 w-8 border border-slate-700/50 rounded bg-slate-950/50 p-1">
                        <span class="text-xs text-slate-400 ml-3 truncate">{{ $settings->favicon_path }}</span>
                    </div>
                    @endif
                    <input type="file" name="favicon" class="block w-full text-[13px] text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-[13px] file:font-medium file:bg-white/10 file:text-white hover:file:bg-white/20 transition-colors cursor-pointer">
                </div>
            </div>

            <!-- Social Links -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-x-8 gap-y-6 pt-4 border-t border-slate-700/50/60">
                <div>
                    <label class="block text-[13px] font-medium text-slate-400 mb-2"><i class="fa-brands fa-facebook mr-1"></i> Facebook URL</label>
                    <input type="text" name="social_facebook" value="{{ $settings->social_links['facebook'] ?? '' }}"
                        class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-white/30 rounded-lg text-slate-200 text-[13px] outline-none transition-colors hover:border-white/20">
                </div>
                <div>
                    <label class="block text-[13px] font-medium text-slate-400 mb-2"><i class="fa-brands fa-twitter mr-1"></i> Twitter URL</label>
                    <input type="text" name="social_twitter" value="{{ $settings->social_links['twitter'] ?? '' }}"
                        class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-white/30 rounded-lg text-slate-200 text-[13px] outline-none transition-colors hover:border-white/20">
                </div>
                <div>
                    <label class="block text-[13px] font-medium text-slate-400 mb-2"><i class="fa-brands fa-linkedin mr-1"></i> LinkedIn URL</label>
                    <input type="text" name="social_linkedin" value="{{ $settings->social_links['linkedin'] ?? '' }}"
                        class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-white/30 rounded-lg text-slate-200 text-[13px] outline-none transition-colors hover:border-white/20">
                </div>
                <div>
                    <label class="block text-[13px] font-medium text-slate-400 mb-2"><i class="fa-brands fa-github mr-1"></i> GitHub URL</label>
                    <input type="text" name="social_github" value="{{ $settings->social_links['github'] ?? '' }}"
                        class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-white/30 rounded-lg text-slate-200 text-[13px] outline-none transition-colors hover:border-white/20">
                </div>
            </div>
        </div>
    </div>

    <!-- Tab 2: Theme Builder -->
    <div id="tab-theme" class="tab-pane hidden space-y-6">
        <div class="bg-[#000000] border border-white/[0.08] rounded-xl p-8 space-y-8">
            <div class="flex items-center justify-between border-b border-white/[0.08] pb-4 mb-6">
                <h3 class="text-[14px] font-semibold text-white">Theme Colors & Styling Customizer</h3>
                <p class="text-[12px] text-slate-400">Expose full layout and brand control for light & dark modes.</p>
            </div>

            <!-- Global Style Configuration -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2 pb-6 border-b border-white/[0.08]">
                <div>
                    <label class="block text-[13px] font-medium text-slate-400 mb-2">Typography Family (Google Font)</label>
                    <select name="theme_font_family" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-white/30 rounded-lg text-slate-200 text-[13px] outline-none transition-colors hover:border-white/20">
                        <option value="Outfit" {{ ($settings->theme_config['font_family'] ?? '') === 'Outfit' ? 'selected' : '' }}>Outfit (Modern & Playful)</option>
                        <option value="Inter" {{ ($settings->theme_config['font_family'] ?? '') === 'Inter' ? 'selected' : '' }}>Inter (Sleek Tech Sans)</option>
                        <option value="Plus Jakarta Sans" {{ ($settings->theme_config['font_family'] ?? '') === 'Plus Jakarta Sans' ? 'selected' : '' }}>Plus Jakarta Sans (Premium Sans)</option>
                        <option value="Roboto" {{ ($settings->theme_config['font_family'] ?? '') === 'Roboto' ? 'selected' : '' }}>Roboto (Clean & Classic)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[13px] font-medium text-slate-400 mb-2">Button Shape & Styling</label>
                    <select name="theme_button_style" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-white/30 rounded-lg text-slate-200 text-[13px] outline-none transition-colors hover:border-white/20">
                        <option value="rounded-full" {{ ($settings->theme_config['button_style'] ?? '') === 'rounded-full' ? 'selected' : '' }}>Pill Rounded (rounded-full)</option>
                        <option value="rounded-2xl" {{ ($settings->theme_config['button_style'] ?? '') === 'rounded-2xl' ? 'selected' : '' }}>Smooth Rounded (rounded-2xl)</option>
                        <option value="rounded-lg" {{ ($settings->theme_config['button_style'] ?? '') === 'rounded-lg' ? 'selected' : '' }}>Standard Boxed (rounded-lg)</option>
                        <option value="rounded-none" {{ ($settings->theme_config['button_style'] ?? '') === 'rounded-none' ? 'selected' : '' }}>Sharp Square (rounded-none)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[13px] font-medium text-slate-400 mb-2">Default Theme Mode</label>
                    <select name="theme_default_mode" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-white/30 rounded-lg text-slate-200 text-[13px] outline-none transition-colors hover:border-white/20">
                        <option value="dark" {{ ($settings->theme_config['default_mode'] ?? '') === 'dark' ? 'selected' : '' }}>Dark Mode (Default)</option>
                        <option value="light" {{ ($settings->theme_config['default_mode'] ?? '') === 'light' ? 'selected' : '' }}>Light Mode</option>
                    </select>
                </div>
            </div>

            <!-- Navbar CTA Button Settings -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 pb-6 border-b border-white/[0.08]">
                <div>
                    <label class="block text-[13px] font-medium text-slate-400 mb-2">Navbar CTA Button Text (English)</label>
                    <input type="text" name="navbar_cta_text_en" value="{{ $settings->theme_config['navbar_cta_text']['en'] ?? 'Start Project' }}"
                        class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-white/30 rounded-lg text-slate-200 text-[13px] outline-none transition-colors hover:border-white/20">
                </div>
                <div>
                    <label class="block text-[13px] font-medium text-slate-400 mb-2">Navbar CTA Button Text (Bangla)</label>
                    <input type="text" name="navbar_cta_text_bn" value="{{ $settings->theme_config['navbar_cta_text']['bn'] ?? 'প্রজেক্ট শুরু করুন' }}"
                        class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-white/30 rounded-lg text-slate-200 text-[13px] outline-none transition-colors hover:border-white/20">
                </div>
                <div>
                    <label class="block text-[13px] font-medium text-slate-400 mb-2">Navbar CTA Button URL / Link</label>
                    <input type="text" name="navbar_cta_url" value="{{ $settings->theme_config['navbar_cta_url'] ?? '#contact' }}"
                        class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-white/30 rounded-lg text-slate-200 text-[13px] outline-none transition-colors hover:border-white/20">
                </div>
            </div>

            <!-- Mode Sub-Tabs -->
            <div>
                <div class="flex space-x-6 border-b border-white/[0.08] mb-6 pb-px">
                    <button type="button" onclick="switchThemeSubTab('dark-theme')" id="btn-dark-theme" class="text-white border-b-2 border-indigo-500 pb-3 text-[13px] font-semibold transition-all">
                        <i class="fa-solid fa-moon mr-2"></i> Dark Mode Colors
                    </button>
                    <button type="button" onclick="switchThemeSubTab('light-theme')" id="btn-light-theme" class="text-slate-400 border-b-2 border-transparent pb-3 text-[13px] font-semibold transition-all hover:text-slate-200">
                        <i class="fa-solid fa-sun mr-2"></i> Light Mode Colors
                    </button>
                </div>

                <!-- Dark Mode Content Panel -->
                <div id="sub-tab-dark-theme" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <!-- Left Block: Brand Colors -->
                        <div class="space-y-6">
                            <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Brand Colors (Dark Mode)</h4>
                            <div>
                                <label class="block text-[12px] font-medium text-slate-400 mb-2">Primary Accent</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" value="{{ $settings->theme_config['primary_color'] ?? '#6366f1' }}" onchange="this.nextElementSibling.value = this.value" class="w-10 h-10 bg-transparent border border-white/10 rounded-lg cursor-pointer">
                                    <input type="text" name="theme_primary_color" value="{{ $settings->theme_config['primary_color'] ?? '#6366f1' }}" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 rounded-lg text-slate-200 text-[13px] outline-none font-mono">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-medium text-slate-400 mb-2">Secondary Highlight</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" value="{{ $settings->theme_config['secondary_color'] ?? '#06b6d4' }}" onchange="this.nextElementSibling.value = this.value" class="w-10 h-10 bg-transparent border border-white/10 rounded-lg cursor-pointer">
                                    <input type="text" name="theme_secondary_color" value="{{ $settings->theme_config['secondary_color'] ?? '#06b6d4' }}" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 rounded-lg text-slate-200 text-[13px] outline-none font-mono">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-medium text-slate-400 mb-2">Accent / Gradient end</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" value="{{ $settings->theme_config['accent_color'] ?? '#a855f7' }}" onchange="this.nextElementSibling.value = this.value" class="w-10 h-10 bg-transparent border border-white/10 rounded-lg cursor-pointer">
                                    <input type="text" name="theme_accent_color" value="{{ $settings->theme_config['accent_color'] ?? '#a855f7' }}" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 rounded-lg text-slate-200 text-[13px] outline-none font-mono">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-medium text-slate-400 mb-2">Button Gradient Start</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" value="{{ $settings->theme_config['btn_grad_start'] ?? '#a855f7' }}" onchange="this.nextElementSibling.value = this.value" class="w-10 h-10 bg-transparent border border-white/10 rounded-lg cursor-pointer">
                                    <input type="text" name="theme_btn_grad_start" value="{{ $settings->theme_config['btn_grad_start'] ?? '#a855f7' }}" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 rounded-lg text-slate-200 text-[13px] outline-none font-mono">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-medium text-slate-400 mb-2">Button Gradient End</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" value="{{ $settings->theme_config['btn_grad_end'] ?? '#6366f1' }}" onchange="this.nextElementSibling.value = this.value" class="w-10 h-10 bg-transparent border border-white/10 rounded-lg cursor-pointer">
                                    <input type="text" name="theme_btn_grad_end" value="{{ $settings->theme_config['btn_grad_end'] ?? '#6366f1' }}" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 rounded-lg text-slate-200 text-[13px] outline-none font-mono">
                                </div>
                            </div>
                        </div>

                        <!-- Right Block: Layout Colors -->
                        <div class="space-y-6">
                            <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Layout Colors (Dark Mode)</h4>
                            <div>
                                <label class="block text-[12px] font-medium text-slate-400 mb-2">Main Background</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" value="{{ $settings->theme_config['bg_dark'] ?? '#030712' }}" onchange="this.nextElementSibling.value = this.value" class="w-10 h-10 bg-transparent border border-white/10 rounded-lg cursor-pointer">
                                    <input type="text" name="theme_bg_dark" value="{{ $settings->theme_config['bg_dark'] ?? '#030712' }}" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 rounded-lg text-slate-200 text-[13px] outline-none font-mono">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-medium text-slate-400 mb-2">Secondary Background</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" value="{{ $settings->theme_config['bg_dark_2'] ?? '#050b18' }}" onchange="this.nextElementSibling.value = this.value" class="w-10 h-10 bg-transparent border border-white/10 rounded-lg cursor-pointer">
                                    <input type="text" name="theme_bg_dark_2" value="{{ $settings->theme_config['bg_dark_2'] ?? '#050b18' }}" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 rounded-lg text-slate-200 text-[13px] outline-none font-mono">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-medium text-slate-400 mb-2">Borders & Lines</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" value="{{ $settings->theme_config['border_dark'] ?? '#1f2937' }}" onchange="this.nextElementSibling.value = this.value" class="w-10 h-10 bg-transparent border border-white/10 rounded-lg cursor-pointer">
                                    <input type="text" name="theme_border_dark" value="{{ $settings->theme_config['border_dark'] ?? 'rgba(255,255,255,0.07)' }}" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 rounded-lg text-slate-200 text-[13px] outline-none font-mono">
                                </div>
                            </div>
                        </div>

                        <!-- Card/Surfaces -->
                        <div class="space-y-6 pt-4 border-t border-white/[0.08]">
                            <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Surfaces & Containers (Dark Mode)</h4>
                            <div>
                                <label class="block text-[12px] font-medium text-slate-400 mb-2">Surface Level 1 (Cards)</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" value="{{ $settings->theme_config['surface_dark'] ?? '#0d1117' }}" onchange="this.nextElementSibling.value = this.value" class="w-10 h-10 bg-transparent border border-white/10 rounded-lg cursor-pointer">
                                    <input type="text" name="theme_surface_dark" value="{{ $settings->theme_config['surface_dark'] ?? '#0d1117' }}" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 rounded-lg text-slate-200 text-[13px] outline-none font-mono">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-medium text-slate-400 mb-2">Surface Level 2 (Nested Panels)</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" value="{{ $settings->theme_config['surface_dark_2'] ?? '#161b22' }}" onchange="this.nextElementSibling.value = this.value" class="w-10 h-10 bg-transparent border border-white/10 rounded-lg cursor-pointer">
                                    <input type="text" name="theme_surface_dark_2" value="{{ $settings->theme_config['surface_dark_2'] ?? '#161b22' }}" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 rounded-lg text-slate-200 text-[13px] outline-none font-mono">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-medium text-slate-400 mb-2">Surface Level 3 (Inputs)</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" value="{{ $settings->theme_config['surface_dark_3'] ?? '#21262d' }}" onchange="this.nextElementSibling.value = this.value" class="w-10 h-10 bg-transparent border border-white/10 rounded-lg cursor-pointer">
                                    <input type="text" name="theme_surface_dark_3" value="{{ $settings->theme_config['surface_dark_3'] ?? '#21262d' }}" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 rounded-lg text-slate-200 text-[13px] outline-none font-mono">
                                </div>
                            </div>
                        </div>

                        <!-- Typography -->
                        <div class="space-y-6 pt-4 border-t border-white/[0.08]">
                            <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Typography (Dark Mode)</h4>
                            <div>
                                <label class="block text-[12px] font-medium text-slate-400 mb-2">Primary Text</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" value="{{ $settings->theme_config['text_dark'] ?? '#f0f6fc' }}" onchange="this.nextElementSibling.value = this.value" class="w-10 h-10 bg-transparent border border-white/10 rounded-lg cursor-pointer">
                                    <input type="text" name="theme_text_dark" value="{{ $settings->theme_config['text_dark'] ?? '#f0f6fc' }}" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 rounded-lg text-slate-200 text-[13px] outline-none font-mono">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-medium text-slate-400 mb-2">Secondary Text</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" value="{{ $settings->theme_config['text_dark_2'] ?? '#8b949e' }}" onchange="this.nextElementSibling.value = this.value" class="w-10 h-10 bg-transparent border border-white/10 rounded-lg cursor-pointer">
                                    <input type="text" name="theme_text_dark_2" value="{{ $settings->theme_config['text_dark_2'] ?? '#8b949e' }}" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 rounded-lg text-slate-200 text-[13px] outline-none font-mono">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-medium text-slate-400 mb-2">Muted / Tertiary Text</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" value="{{ $settings->theme_config['text_dark_3'] ?? '#6e7681' }}" onchange="this.nextElementSibling.value = this.value" class="w-10 h-10 bg-transparent border border-white/10 rounded-lg cursor-pointer">
                                    <input type="text" name="theme_text_dark_3" value="{{ $settings->theme_config['text_dark_3'] ?? '#6e7681' }}" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 rounded-lg text-slate-200 text-[13px] outline-none font-mono">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Light Mode Content Panel -->
                <div id="sub-tab-light-theme" class="space-y-6 hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <!-- Left Block: Brand Colors -->
                        <div class="space-y-6">
                            <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Brand Colors (Light Mode)</h4>
                            <div>
                                <label class="block text-[12px] font-medium text-slate-400 mb-2">Primary Accent</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" value="{{ $settings->theme_config['primary_color_light'] ?? '#6366f1' }}" onchange="this.nextElementSibling.value = this.value" class="w-10 h-10 bg-transparent border border-white/10 rounded-lg cursor-pointer">
                                    <input type="text" name="theme_primary_color_light" value="{{ $settings->theme_config['primary_color_light'] ?? '#6366f1' }}" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 rounded-lg text-slate-200 text-[13px] outline-none font-mono">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-medium text-slate-400 mb-2">Secondary Highlight</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" value="{{ $settings->theme_config['secondary_color_light'] ?? '#06b6d4' }}" onchange="this.nextElementSibling.value = this.value" class="w-10 h-10 bg-transparent border border-white/10 rounded-lg cursor-pointer">
                                    <input type="text" name="theme_secondary_color_light" value="{{ $settings->theme_config['secondary_color_light'] ?? '#06b6d4' }}" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 rounded-lg text-slate-200 text-[13px] outline-none font-mono">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-medium text-slate-400 mb-2">Accent / Gradient end</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" value="{{ $settings->theme_config['accent_color_light'] ?? '#a855f7' }}" onchange="this.nextElementSibling.value = this.value" class="w-10 h-10 bg-transparent border border-white/10 rounded-lg cursor-pointer">
                                    <input type="text" name="theme_accent_color_light" value="{{ $settings->theme_config['accent_color_light'] ?? '#a855f7' }}" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 rounded-lg text-slate-200 text-[13px] outline-none font-mono">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-medium text-slate-400 mb-2">Button Gradient Start</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" value="{{ $settings->theme_config['btn_grad_start_light'] ?? '#a855f7' }}" onchange="this.nextElementSibling.value = this.value" class="w-10 h-10 bg-transparent border border-white/10 rounded-lg cursor-pointer">
                                    <input type="text" name="theme_btn_grad_start_light" value="{{ $settings->theme_config['btn_grad_start_light'] ?? '#a855f7' }}" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 rounded-lg text-slate-200 text-[13px] outline-none font-mono">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-medium text-slate-400 mb-2">Button Gradient End</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" value="{{ $settings->theme_config['btn_grad_end_light'] ?? '#6366f1' }}" onchange="this.nextElementSibling.value = this.value" class="w-10 h-10 bg-transparent border border-white/10 rounded-lg cursor-pointer">
                                    <input type="text" name="theme_btn_grad_end_light" value="{{ $settings->theme_config['btn_grad_end_light'] ?? '#6366f1' }}" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 rounded-lg text-slate-200 text-[13px] outline-none font-mono">
                                </div>
                            </div>
                        </div>

                        <!-- Right Block: Layout Colors -->
                        <div class="space-y-6">
                            <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Layout Colors (Light Mode)</h4>
                            <div>
                                <label class="block text-[12px] font-medium text-slate-400 mb-2">Main Background</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" value="{{ $settings->theme_config['bg_light'] ?? '#f4f6fb' }}" onchange="this.nextElementSibling.value = this.value" class="w-10 h-10 bg-transparent border border-white/10 rounded-lg cursor-pointer">
                                    <input type="text" name="theme_bg_light" value="{{ $settings->theme_config['bg_light'] ?? '#f4f6fb' }}" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 rounded-lg text-slate-200 text-[13px] outline-none font-mono">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-medium text-slate-400 mb-2">Secondary Background</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" value="{{ $settings->theme_config['bg_light_2'] ?? '#eef1f8' }}" onchange="this.nextElementSibling.value = this.value" class="w-10 h-10 bg-transparent border border-white/10 rounded-lg cursor-pointer">
                                    <input type="text" name="theme_bg_light_2" value="{{ $settings->theme_config['bg_light_2'] ?? '#eef1f8' }}" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 rounded-lg text-slate-200 text-[13px] outline-none font-mono">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-medium text-slate-400 mb-2">Borders & Lines</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" value="{{ $settings->theme_config['border_light'] ?? '#e5e7eb' }}" onchange="this.nextElementSibling.value = this.value" class="w-10 h-10 bg-transparent border border-white/10 rounded-lg cursor-pointer">
                                    <input type="text" name="theme_border_light" value="{{ $settings->theme_config['border_light'] ?? 'rgba(99,102,241,0.10)' }}" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 rounded-lg text-slate-200 text-[13px] outline-none font-mono">
                                </div>
                            </div>
                        </div>

                        <!-- Card/Surfaces -->
                        <div class="space-y-6 pt-4 border-t border-white/[0.08]">
                            <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Surfaces & Containers (Light Mode)</h4>
                            <div>
                                <label class="block text-[12px] font-medium text-slate-400 mb-2">Surface Level 1 (Cards)</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" value="{{ $settings->theme_config['surface_light'] ?? '#ffffff' }}" onchange="this.nextElementSibling.value = this.value" class="w-10 h-10 bg-transparent border border-white/10 rounded-lg cursor-pointer">
                                    <input type="text" name="theme_surface_light" value="{{ $settings->theme_config['surface_light'] ?? '#ffffff' }}" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 rounded-lg text-slate-200 text-[13px] outline-none font-mono">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-medium text-slate-400 mb-2">Surface Level 2 (Nested Panels)</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" value="{{ $settings->theme_config['surface_light_2'] ?? '#f8fafd' }}" onchange="this.nextElementSibling.value = this.value" class="w-10 h-10 bg-transparent border border-white/10 rounded-lg cursor-pointer">
                                    <input type="text" name="theme_surface_light_2" value="{{ $settings->theme_config['surface_light_2'] ?? '#f8fafd' }}" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 rounded-lg text-slate-200 text-[13px] outline-none font-mono">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-medium text-slate-400 mb-2">Surface Level 3 (Inputs)</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" value="{{ $settings->theme_config['surface_light_3'] ?? '#e5e9f5' }}" onchange="this.nextElementSibling.value = this.value" class="w-10 h-10 bg-transparent border border-white/10 rounded-lg cursor-pointer">
                                    <input type="text" name="theme_surface_light_3" value="{{ $settings->theme_config['surface_light_3'] ?? '#e5e9f5' }}" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 rounded-lg text-slate-200 text-[13px] outline-none font-mono">
                                </div>
                            </div>
                        </div>

                        <!-- Typography -->
                        <div class="space-y-6 pt-4 border-t border-white/[0.08]">
                            <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Typography (Light Mode)</h4>
                            <div>
                                <label class="block text-[12px] font-medium text-slate-400 mb-2">Primary Text</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" value="{{ $settings->theme_config['text_light'] ?? '#0e1120' }}" onchange="this.nextElementSibling.value = this.value" class="w-10 h-10 bg-transparent border border-white/10 rounded-lg cursor-pointer">
                                    <input type="text" name="theme_text_light" value="{{ $settings->theme_config['text_light'] ?? '#0e1120' }}" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 rounded-lg text-slate-200 text-[13px] outline-none font-mono">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-medium text-slate-400 mb-2">Secondary Text</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" value="{{ $settings->theme_config['text_light_2'] ?? '#3d4669' }}" onchange="this.nextElementSibling.value = this.value" class="w-10 h-10 bg-transparent border border-white/10 rounded-lg cursor-pointer">
                                    <input type="text" name="theme_text_light_2" value="{{ $settings->theme_config['text_light_2'] ?? '#3d4669' }}" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 rounded-lg text-slate-200 text-[13px] outline-none font-mono">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[12px] font-medium text-slate-400 mb-2">Muted / Tertiary Text</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" value="{{ $settings->theme_config['text_light_3'] ?? '#8b95b8' }}" onchange="this.nextElementSibling.value = this.value" class="w-10 h-10 bg-transparent border border-white/10 rounded-lg cursor-pointer">
                                    <input type="text" name="theme_text_light_3" value="{{ $settings->theme_config['text_light_3'] ?? '#8b95b8' }}" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 rounded-lg text-slate-200 text-[13px] outline-none font-mono">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function switchThemeSubTab(mode) {
        const darkPanel = document.getElementById('sub-tab-dark-theme');
        const lightPanel = document.getElementById('sub-tab-light-theme');
        const darkBtn = document.getElementById('btn-dark-theme');
        const lightBtn = document.getElementById('btn-light-theme');

        if (mode === 'dark-theme') {
            darkPanel.classList.remove('hidden');
            lightPanel.classList.add('hidden');
            darkBtn.className = 'text-white border-b-2 border-indigo-500 pb-3 text-[13px] font-semibold transition-all';
            lightBtn.className = 'text-slate-400 border-b-2 border-transparent pb-3 text-[13px] font-semibold transition-all hover:text-slate-200';
        } else {
            darkPanel.classList.add('hidden');
            lightPanel.classList.remove('hidden');
            darkBtn.className = 'text-slate-400 border-b-2 border-transparent pb-3 text-[13px] font-semibold transition-all hover:text-slate-200';
            lightBtn.className = 'text-white border-b-2 border-indigo-500 pb-3 text-[13px] font-semibold transition-all';
        }
    }
    </script>
    <!-- Tab 3: Homepage Sections Builder -->
    <div id="tab-homepage" class="tab-pane hidden space-y-6">
        <div class="bg-[#000000] border border-white/[0.08] rounded-xl p-8">

            {{-- Header --}}
            <div class="flex items-center justify-between border-b border-white/[0.08] pb-5 mb-8">
                <div>
                    <h3 class="text-[14px] font-semibold text-white">Homepage Sections</h3>
                    <p class="text-[12px] text-slate-500 mt-1">Configure which sections are visible on your landing page. Green toggle means visible, red means hidden.</p>
                </div>
                <div class="flex items-center gap-2 text-[11px]">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-medium">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Active
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-rose-500/10 text-rose-500 border border-rose-500/20 font-medium">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Disabled
                    </span>
                </div>
            </div>

            @php
                $sectionMeta = [
                    'hero'              => ['label' => 'Cinematic Hero',        'icon' => 'fa-rocket',            'color' => '#6366f1'],
                    'client_logos'      => ['label' => 'Client Logos',          'icon' => 'fa-circle-nodes',      'color' => '#06b6d4'],
                    'stats_counter'     => ['label' => 'Stats Counter',         'icon' => 'fa-chart-bar',         'color' => '#f59e0b'],
                    'services'          => ['label' => 'Our Services',          'icon' => 'fa-screwdriver-wrench','color' => '#8b5cf6'],
                    'service_wizard'    => ['label' => 'Service Wizard',        'icon' => 'fa-wand-magic-sparkles','color' => '#ec4899'],
                    'tech_stack'        => ['label' => 'Tech Stack',            'icon' => 'fa-microchip',         'color' => '#14b8a6'],
                    'marketplace'       => ['label' => 'SaaS Marketplace',      'icon' => 'fa-store',             'color' => '#f97316'],
                    'showcase'          => ['label' => 'Software Showcase',     'icon' => 'fa-display',           'color' => '#3b82f6'],
                    'growth_calculator' => ['label' => 'Growth Calculator',     'icon' => 'fa-calculator',        'color' => '#22c55e'],
                    'process_timeline'  => ['label' => 'Process Timeline',      'icon' => 'fa-timeline',          'color' => '#a855f7'],
                    'industries'        => ['label' => 'Industries Served',     'icon' => 'fa-building-user',     'color' => '#0ea5e9'],
                    'before_after'      => ['label' => 'Before / After',        'icon' => 'fa-code-compare',      'color' => '#eab308'],
                    'founder_story'     => ['label' => 'Founder Story',         'icon' => 'fa-video',             'color' => '#ef4444'],
                    'case_studies'      => ['label' => 'Case Studies',          'icon' => 'fa-folder-open',       'color' => '#6366f1'],
                    'pricing'           => ['label' => 'Pricing Tables',        'icon' => 'fa-tags',              'color' => '#10b981'],
                    'faqs'              => ['label' => 'FAQs',                  'icon' => 'fa-circle-question',   'color' => '#8b5cf6'],
                    'team'              => ['label' => 'Team Members',          'icon' => 'fa-users',             'color' => '#f59e0b'],
                    'blog'              => ['label' => 'Blog Engine',           'icon' => 'fa-blog',              'color' => '#06b6d4'],
                    'testimonials'      => ['label' => 'Testimonials',          'icon' => 'fa-comment-dots',      'color' => '#ec4899'],
                    'contact'           => ['label' => 'Contact Form',          'icon' => 'fa-envelope',          'color' => '#14b8a6'],
                    'ai_assistant'      => ['label' => 'AI Assistant',          'icon' => 'fa-robot',             'color' => '#a855f7'],
                ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($sections->sortBy('sort_order') as $sec)
                    @php
                        $key   = $sec->key;
                        $meta  = $sectionMeta[$key] ?? ['label' => ucwords(str_replace('_',' ',$key)), 'icon' => 'fa-layer-group', 'color' => '#6366f1'];
                        $isOn  = (bool)($sec->visible ?? true);
                        $color = $meta['color'];
                    @endphp

                    {{-- Compact Section Card --}}
                    <div class="group relative flex items-center justify-between px-5 py-4 rounded-xl border transition-all duration-300"
                         id="sec-card-{{ $key }}"
                         style="{{ $isOn ? 'border-color:rgba(99,102,241,0.20);background:#0a0a14;' : 'border-color:rgba(255,255,255,0.05);background:#080808;' }}">

                        <div class="flex items-center gap-3.5 min-w-0">
                            {{-- Icon box --}}
                            <div class="shrink-0 w-8 h-8 rounded-lg flex items-center justify-center transition-colors"
                                 style="{{ $isOn ? 'background:'.$color.'22;color:'.$color.';' : 'background:rgba(255,255,255,0.05);color:#475569;' }}">
                                <i class="fa-solid {{ $meta['icon'] }} text-xs"></i>
                            </div>

                            {{-- Name + status badge --}}
                            <div class="min-w-0">
                                <p class="text-[12px] font-semibold text-slate-200 truncate leading-tight">{{ $meta['label'] }}</p>
                                <div class="flex items-center gap-1.5 mt-1">
                                    <span class="sec-dot w-1.5 h-1.5 rounded-full {{ $isOn ? 'bg-emerald-400' : 'bg-rose-500' }}"></span>
                                    <span class="sec-status text-[10px] font-medium {{ $isOn ? 'text-emerald-400' : 'text-rose-500' }}">
                                        {{ $isOn ? 'Visible' : 'Hidden' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Toggle switch (Green when Checked, Red when Unchecked) --}}
                        <label class="relative inline-flex items-center cursor-pointer shrink-0 ml-3">
                            <input type="checkbox"
                                   name="sections[{{ $key }}][visible]"
                                   class="sr-only peer sec-toggle"
                                   data-key="{{ $key }}"
                                   data-color="{{ $color }}"
                                   {{ $isOn ? 'checked' : '' }}>
                            <div class="w-10 h-5 bg-rose-500 rounded-full transition-all duration-300
                                        peer-checked:bg-emerald-500
                                        after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                        after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all
                                        peer-checked:after:translate-x-5 shadow-inner"></div>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
    document.querySelectorAll('.sec-toggle').forEach(function(t) {
        t.addEventListener('change', function() {
            var card = document.getElementById('sec-card-' + this.dataset.key);
            var color = this.dataset.color;
            var on = this.checked;
            if (!card) return;
            
            // Card styling
            card.style.borderColor = on ? 'rgba(99,102,241,0.20)' : 'rgba(255,255,255,0.05)';
            card.style.background  = on ? '#0a0a14' : '#080808';
            
            // Dot and text styling
            var dot = card.querySelector('.sec-dot');
            var lbl = card.querySelector('.sec-status');
            var box = card.querySelector('.shrink-0');
            
            if (dot) {
                dot.className = dot.className.replace(/bg-emerald-400|bg-rose-500/g,'') + (on ? ' bg-emerald-400' : ' bg-rose-500');
            }
            if (lbl) {
                lbl.className = lbl.className.replace(/text-emerald-400|text-rose-500/g,'') + (on ? ' text-emerald-400' : ' text-rose-500');
                lbl.textContent = on ? 'Visible' : 'Hidden';
            }
            if (box) {
                box.style.background = on ? color+'22' : 'rgba(255,255,255,0.05)';
                box.style.color = on ? color : '#475569';
            }
        });
    });
    </script>

    <!-- Tab 4: SEO Defaults -->
    <div id="tab-seo" class="tab-pane hidden space-y-6">
        <div class="bg-[#000000] border border-white/[0.08] rounded-xl p-8 space-y-8">
            <h3 class="text-[14px] font-semibold text-white mb-6">Site-Wide SEO & Google Ranking Meta</h3>

            <!-- SEO Title -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <div>
                    <label class="block text-[13px] font-medium text-slate-400 mb-2">Meta Title (EN)</label>
                    <input type="text" name="seo_title_en" value="{{ $settings->seo_defaults['meta_title']['en'] ?? '' }}"
                        class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-white/30 rounded-lg text-slate-200 text-[13px] outline-none transition-colors hover:border-white/20">
                </div>
                <div>
                    <label class="block text-[13px] font-medium text-slate-400 mb-2">Meta Title (BN)</label>
                    <input type="text" name="seo_title_bn" value="{{ $settings->seo_defaults['meta_title']['bn'] ?? '' }}"
                        class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-white/30 rounded-lg text-slate-200 text-[13px] outline-none transition-colors hover:border-white/20">
                </div>
                
            </div>

            <!-- SEO Description -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <div>
                    <label class="block text-[13px] font-medium text-slate-400 mb-2">Meta Description (EN)</label>
                    <textarea name="seo_desc_en" rows="3" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-white/30 rounded-lg text-slate-200 text-[13px] outline-none transition-colors hover:border-white/20">{{ $settings->seo_defaults['meta_description']['en'] ?? '' }}</textarea>
                </div>
                <div>
                    <label class="block text-[13px] font-medium text-slate-400 mb-2">Meta Description (BN)</label>
                    <textarea name="seo_desc_bn" rows="3" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-white/30 rounded-lg text-slate-200 text-[13px] outline-none transition-colors hover:border-white/20">{{ $settings->seo_defaults['meta_description']['bn'] ?? '' }}</textarea>
                </div>
                
            </div>


            <!-- ─── Open Graph (Social Sharing) ─── -->
            <div class="pt-6 border-t border-white/[0.06] space-y-4">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fa-solid fa-share-nodes text-indigo-400 text-sm"></i>
                    <h4 class="text-[13px] font-semibold text-white">Open Graph — Social Sharing Preview</h4>
                    <span class="text-[10px] text-slate-500 font-medium">Controls how your site appears when shared on Facebook, Twitter/X, WhatsApp, etc.</span>
                </div>

                <!-- OG Title -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                    <div>
                        <label class="block text-[13px] font-medium text-slate-400 mb-2">OG Title (EN)</label>
                        <input type="text" name="seo_og_title_en"
                            value="{{ $settings->seo_defaults['og_title']['en'] ?? '' }}"
                            placeholder="e.g. Lozybyte — Build Digital Businesses"
                            class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-indigo-500/60 rounded-lg text-slate-200 text-[13px] outline-none transition-colors hover:border-white/20">
                    </div>
                    <div>
                        <label class="block text-[13px] font-medium text-slate-400 mb-2">OG Title (BN)</label>
                        <input type="text" name="seo_og_title_bn"
                            value="{{ $settings->seo_defaults['og_title']['bn'] ?? '' }}"
                            placeholder="e.g. লজিইবাইট — ডিজিটাল ব্যবসা তৈরি করুন"
                            class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-indigo-500/60 rounded-lg text-slate-200 text-[13px] outline-none transition-colors hover:border-white/20">
                    </div>
                </div>

                <!-- OG Description -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                    <div>
                        <label class="block text-[13px] font-medium text-slate-400 mb-2">OG Description (EN)</label>
                        <textarea name="seo_og_desc_en" rows="3"
                            placeholder="Short, compelling description for social share cards (max ~155 chars)..."
                            class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-indigo-500/60 rounded-lg text-slate-200 text-[13px] outline-none transition-colors hover:border-white/20">{{ $settings->seo_defaults['og_description']['en'] ?? '' }}</textarea>
                    </div>
                    <div>
                        <label class="block text-[13px] font-medium text-slate-400 mb-2">OG Description (BN)</label>
                        <textarea name="seo_og_desc_bn" rows="3"
                            placeholder="সোশ্যাল শেয়ার কার্ডের জন্য সংক্ষিপ্ত বিবরণ..."
                            class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-indigo-500/60 rounded-lg text-slate-200 text-[13px] outline-none transition-colors hover:border-white/20">{{ $settings->seo_defaults['og_description']['bn'] ?? '' }}</textarea>
                    </div>
                </div>

                <!-- OG Image Upload -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 items-start">
                    <div>
                        <label class="block text-[13px] font-medium text-slate-400 mb-2">
                            OG Image
                            <span class="text-slate-500 font-normal normal-case text-[11px]"> — Recommended: 1200×630 px</span>
                        </label>
                        <input type="file" name="seo_og_image" accept="image/*"
                            class="block w-full text-[12px] text-slate-400 file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-600/10 file:text-indigo-400 file:text-[12px] hover:file:bg-indigo-600/20 cursor-pointer">
                        <p class="text-[11px] text-slate-600 mt-1.5">Upload a new image to replace the current one. Leave blank to keep existing.</p>
                    </div>
                    <div>
                        @if(!empty($settings->seo_defaults['og_image']))
                        <label class="block text-[13px] font-medium text-slate-400 mb-2">Current OG Image</label>
                        <div class="relative group w-full max-w-xs">
                            <img src="{{ $settings->seo_defaults['og_image'] }}" alt="Current OG Image"
                                class="w-full h-28 object-cover rounded-xl border border-white/10 shadow-lg">
                            <div class="absolute inset-0 bg-black/40 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-[11px] text-white font-semibold">Current OG Image</span>
                            </div>
                        </div>
                        @else
                        <label class="block text-[13px] font-medium text-slate-400 mb-2">OG Image Preview</label>
                        <div class="w-full max-w-xs h-28 rounded-xl border border-dashed border-white/10 flex items-center justify-center">
                            <span class="text-[11px] text-slate-600">No OG image set yet</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- JSON-LD Schema Markup -->
            <div class="pt-6 border-t border-white/[0.06]">
                <label class="block text-[13px] font-medium text-slate-400 mb-2">Google JSON-LD Schema Markup</label>
                <textarea name="seo_schema_markup" rows="5" class="block w-full px-3 py-2 bg-[#0a0a0a] border border-white/10 focus:border-white/30 rounded-lg text-slate-200 text-[13px] font-mono outline-none transition-colors hover:border-white/20" placeholder='{ "@@context": "https://schema.org", "@@type": "Organization" }'>{{ $settings->seo_defaults['schema_markup'] ?? '' }}</textarea>
            </div>

        </div>
    </div>

    <!-- Submit Area -->
    <div class="flex items-center justify-end shrink-0">
        <button type="submit" class="px-5 py-2 bg-white hover:bg-slate-200 text-black text-[13px] font-medium rounded-md shadow-sm transition-colors flex items-center">
            Save Changes
        </button>
    </div>
</form>

<script>
    function switchTab(tabId) {
        // Hide all panes
        document.querySelectorAll('.tab-pane').forEach(el => el.classList.add('hidden'));
        
        // Show selected pane
        document.getElementById(tabId).classList.remove('hidden');

        // Reset all tab button styles
        document.querySelectorAll('#settings-tabs button').forEach(btn => {
            btn.classList.remove('border-indigo-500', 'text-indigo-400');
            btn.classList.add('border-transparent', 'text-slate-400');
        });

        // Set active tab button style
        const activeBtn = document.getElementById('btn-' + tabId);
        activeBtn.classList.remove('border-transparent', 'text-slate-400');
        activeBtn.classList.add('border-white', 'text-white');
    }
</script>
@endsection