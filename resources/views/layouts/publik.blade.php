<!DOCTYPE html>
<html class="light scroll-smooth" lang="id" id="htmlRoot">
<script>
    // Apply saved theme immediately to prevent flash
    (function(){
        if(localStorage.getItem('gayo-theme')==='dark'){
            document.documentElement.classList.add('theme-dark');
        }
    })();
</script>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gayo Heritage | SIG Kesenian Gayo')</title>
    <link rel="icon" href="data:,">
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                                        "colors": {
                        "on-tertiary-fixed": "#1B3A2D",
                        "surface-container-high": "#E2EDE8",
                        "on-tertiary-container": "#0D9373",
                        "surface-variant": "#DCE8E2",
                        "tertiary-container": "#D4F0E6",
                        "on-primary-container": "#FFFFFF",
                        "surface-tint": "#0D9373",
                        "on-secondary-fixed": "#3D2E00",
                        "on-secondary-fixed-variant": "#6B5100",
                        "on-error-container": "#93000a",
                        "secondary": "#E8B931",
                        "primary": "#0D9373",
                        "secondary-fixed": "#FFF0C2",
                        "surface-dim": "#D4DDD8",
                        "outline-variant": "#BFC9C3",
                        "on-surface": "#1B3A2D",
                        "on-secondary-container": "#5C4500",
                        "background": "#F0F7F4",
                        "tertiary": "#0B8264",
                        "primary-fixed-dim": "#0D9373",
                        "primary-container": "#0D9373",
                        "tertiary-fixed-dim": "#0D9373",
                        "on-surface-variant": "#3E5B4F",
                        "secondary-container": "#FFEAA0",
                        "primary-fixed": "#B2DFDB",
                        "surface-container-highest": "#DAE5E0",
                        "error-container": "#FFDAD6",
                        "on-tertiary": "#FFFFFF",
                        "secondary-fixed-dim": "#E8B931",
                        "surface-container": "#EAF3EF",
                        "surface-container-lowest": "#FFFFFF",
                        "surface-bright": "#F0F7F4",
                        "on-primary-fixed": "#00382A",
                        "inverse-on-surface": "#E8F0EC",
                        "error": "#BA1A1A",
                        "outline": "#6F8A7E",
                        "tertiary-fixed": "#B2DFDB",
                        "on-primary": "#FFFFFF",
                        "surface-container-low": "#F0F7F4",
                        "surface": "#F5FAF7",
                        "inverse-surface": "#1B3A2D",
                        "on-secondary": "#FFFFFF",
                        "on-primary-fixed-variant": "#005740",
                        "on-background": "#1B3A2D",
                        "on-error": "#FFFFFF",
                        "inverse-primary": "#80CBC4",
                        "on-tertiary-fixed-variant": "#005740"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "unit": "4px",
                        "sidebar-width": "350px",
                        "toolbar-height": "72px",
                        "gutter": "16px",
                        "margin-desktop": "24px",
                        "margin-mobile": "16px"
                    },
                    "fontFamily": {
                        "mono-data": ["JetBrains Mono"],
                        "label-md": ["Poppins"],
                        "label-sm": ["Poppins"],
                        "headline-lg": ["Poppins"],
                        "headline-lg-mobile": ["Poppins"],
                        "body-lg": ["Poppins"],
                        "display-lg": ["Poppins"],
                        "headline-md": ["Poppins"],
                        "body-md": ["Poppins"]
                    },
                },
            },
        }
    </script>
    <style>
        /* ===== THEME VARIABLES ===== */
        :root {
            --t-accent: #0D9373;
            --t-accent-hover: #087A5E;
            --t-accent-rgb: 13,147,115;
            --t-bg: #F0F7F4;
            --t-surface: #F5FAF7;
            --t-surface-container: #EAF3EF;
            --t-surface-high: #E2EDE8;
            --t-surface-highest: #DAE5E0;
            --t-surface-lowest: #FFFFFF;
            --t-on-surface: #1B3A2D;
            --t-on-surface-variant: #3E5B4F;
            --t-text-hero: #0D9373;
            --t-text-hero-sub: #1B3A2D;
            --t-card-bg: rgba(255, 255, 255, 0.80);
            --t-card-border: rgba(13,147,115,0.08);
            --t-action-grid-bg: #F0F7F4;
            --t-nav-bg: rgba(245,250,247,0.8);
            --t-nav-border: rgba(13,147,115,0.1);
            --t-nav-shadow: rgba(13,147,115,0.08);
            --t-search-bg: rgba(255,255,255,0.6);
            --t-btn-secondary-bg: #FFFFFF;
            --t-btn-secondary-text: #0D9373;
            --t-btn-secondary-border: rgba(13,147,115,0.2);
            --t-footer-bg: #FFFFFF;
            --t-footer-card-bg: #F0F7F4;
            --t-section-border: rgba(13,147,115,0.08);
            --t-hero-overlay: linear-gradient(to bottom, rgba(0,0,0,0.5), rgba(0,0,0,0.2), var(--t-bg));
            --t-icon-color: #0D9373;
        }

        .theme-dark {
            --t-accent: #E8B931;
            --t-accent-hover: #D4A520;
            --t-accent-rgb: 232,185,49;
            --t-bg: #121212;
            --t-surface: #1A1A1A;
            --t-surface-container: #1E1E1E;
            --t-surface-high: #252525;
            --t-surface-highest: #2C2C2C;
            --t-surface-lowest: #0E0E0E;
            --t-on-surface: #F0F0F0;
            --t-on-surface-variant: #B0B0B0;
            --t-text-hero: #FFFFFF;
            --t-text-hero-sub: #D0D0D0;
            --t-card-bg: rgba(30,30,30,0.85);
            --t-card-border: rgba(232,185,49,0.12);
            --t-action-grid-bg: #1E1E1E;
            --t-nav-bg: rgba(18,18,18,0.9);
            --t-nav-border: rgba(232,185,49,0.15);
            --t-nav-shadow: rgba(0,0,0,0.3);
            --t-search-bg: rgba(255,255,255,0.08);
            --t-btn-secondary-bg: transparent;
            --t-btn-secondary-text: #F0F0F0;
            --t-btn-secondary-border: rgba(255,255,255,0.2);
            --t-footer-bg: #0E0E0E;
            --t-footer-card-bg: #1E1E1E;
            --t-section-border: rgba(232,185,49,0.1);
            --t-hero-overlay: linear-gradient(to bottom, rgba(0,0,0,0.65), rgba(0,0,0,0.4), var(--t-bg));
            --t-icon-color: #E8B931;
        }

        body { background: var(--t-bg) !important; color: var(--t-on-surface) !important; transition: background 0.4s, color 0.4s; }

        .glass {
            background: var(--t-card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--t-card-border);
        }
        .active-marker {
            box-shadow: 0 0 12px rgba(var(--t-accent-rgb), 0.4);
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .kerawang-pattern {
            background-image: radial-gradient(var(--t-accent) 0.5px, transparent 0.5px);
            background-size: 12px 12px;
            opacity: 0.1;
        }
        
        /* Map Layout Specific Styles */
        body.map-layout {
            overflow: hidden;
        }
        .map-container {
            height: calc(100vh - 72px);
            margin-top: 72px;
        }
        
        /* Scrollbar for sidebar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: rgba(var(--t-accent-rgb),0.05); }
        ::-webkit-scrollbar-thumb { background: rgba(var(--t-accent-rgb),0.15); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(var(--t-accent-rgb),0.25); }

        /* Theme Toggle Button */
        .theme-toggle { position: relative; width: 52px; height: 28px; border-radius: 14px; cursor: pointer; border: 2px solid rgba(var(--t-accent-rgb),0.3); background: var(--t-surface-high); transition: all 0.3s; }
        .theme-toggle:hover { border-color: var(--t-accent); }
        .theme-toggle .toggle-knob { position: absolute; top: 2px; left: 2px; width: 20px; height: 20px; border-radius: 50%; background: var(--t-accent); display: flex; align-items: center; justify-content: center; transition: transform 0.3s cubic-bezier(0.4,0,0.2,1); box-shadow: 0 2px 4px rgba(0,0,0,0.2); }
        .theme-dark .theme-toggle .toggle-knob { transform: translateX(24px); }
        .toggle-knob .material-symbols-outlined { font-size: 14px; color: white; }
    </style>
    @stack('styles')
</head>
<body class="bg-background text-on-surface font-body-md overflow-x-hidden selection:bg-primary-container selection:text-on-primary-container @yield('body-class')">
    
    <!-- Top Navigation -->
    <nav class="fixed top-0 w-full z-50 backdrop-blur-[20px] border-b shadow-lg h-toolbar-height flex items-center" style="background:var(--t-nav-bg);border-color:var(--t-nav-border);box-shadow:0 10px 15px -3px var(--t-nav-shadow);">
        <div class="flex justify-between items-center px-margin-desktop w-full max-w-screen-2xl mx-auto">
            <div class="flex items-center gap-8">
                <a href="{{ route('publik.beranda') }}" class="text-headline-md font-headline-md font-bold tracking-tight" style="color:var(--t-accent);">SENI DAN BUDAYA GAYO</a>
                <div class="hidden md:flex items-center gap-6">
                    <a class="font-label-md transition-all duration-300 {{ request()->routeIs('publik.beranda') ? 'border-b-2 pb-1' : '' }}" style="color:{{ request()->routeIs('publik.beranda') ? 'var(--t-accent);border-color:var(--t-accent)' : 'var(--t-on-surface-variant)' }};" href="{{ route('publik.beranda') }}">Eksplorasi</a>
                    <a class="font-label-md transition-all duration-300" style="color:var(--t-on-surface-variant);" href="{{ request()->routeIs('publik.beranda') ? '#berita' : route('publik.beranda') . '#berita' }}">Berita</a>
                    <a class="font-label-md transition-all duration-300 {{ request()->routeIs('publik.jadwal') ? 'border-b-2 pb-1' : '' }}" style="color:{{ request()->routeIs('publik.jadwal') ? 'var(--t-accent);border-color:var(--t-accent)' : 'var(--t-on-surface-variant)' }};" href="{{ route('publik.jadwal') }}">Agenda</a>
                    <a class="font-label-md transition-all duration-300 {{ request()->routeIs('publik.peta') ? 'border-b-2 pb-1' : '' }}" style="color:{{ request()->routeIs('publik.peta') ? 'var(--t-accent);border-color:var(--t-accent)' : 'var(--t-on-surface-variant)' }};" href="{{ route('publik.peta') }}">Wilayah</a>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="hidden sm:flex items-center px-4 py-2 rounded-lg border w-64" style="background:var(--t-search-bg);border-color:var(--t-card-border);">
                    <span class="material-symbols-outlined text-sm mr-2" style="color:var(--t-on-surface-variant);">search</span>
                    <input class="bg-transparent border-none p-0 focus:ring-0 text-sm w-full" style="color:var(--t-on-surface);" placeholder="Cari budaya..." type="text">
                </div>
                <!-- Theme Toggle -->
                <button class="theme-toggle" onclick="toggleGayoTheme()" title="Ganti Tema" id="themeToggleBtn">
                    <div class="toggle-knob">
                        <span class="material-symbols-outlined" id="themeIcon">light_mode</span>
                    </div>
                </button>
            </div>
        </div>
    </nav>

    @yield('content')

    @stack('scripts')
    <script>
        function toggleGayoTheme() {
            const html = document.documentElement;
            const isDark = html.classList.toggle('theme-dark');
            localStorage.setItem('gayo-theme', isDark ? 'dark' : 'light');
            updateThemeIcon(isDark);
        }
        function updateThemeIcon(isDark) {
            const icon = document.getElementById('themeIcon');
            if(icon) icon.textContent = isDark ? 'dark_mode' : 'light_mode';
        }
        // Set icon on load
        document.addEventListener('DOMContentLoaded', function(){
            updateThemeIcon(document.documentElement.classList.contains('theme-dark'));
        });
    </script>
</body>
</html>
