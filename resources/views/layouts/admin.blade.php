<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — @yield('title', 'Dashboard') | SIG Kesenian Gayo</title>
    <link rel="icon" href="data:,">
    
    {{-- CSS Tailwind & Google Fonts --}}
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    {{-- Laravel Vite Bundler --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                                        "colors": {
                        "secondary-fixed-dim": "#E8B931",
                        "on-surface": "#1B3A2D",
                        "surface-tint": "#0D9373",
                        "primary-fixed-dim": "#80CBC4",
                        "on-error-container": "#93000a",
                        "surface-container-low": "#F0F7F4",
                        "surface-bright": "#F0F7F4",
                        "on-primary": "#FFFFFF",
                        "secondary-fixed": "#FFF0C2",
                        "surface-variant": "#DCE8E2",
                        "surface-container-high": "#E2EDE8",
                        "on-primary-fixed-variant": "#005740",
                        "on-background": "#1B3A2D",
                        "on-secondary-fixed": "#3D2E00",
                        "on-surface-variant": "#3E5B4F",
                        "on-primary-fixed": "#00382A",
                        "surface": "#F5FAF7",
                        "on-tertiary-fixed": "#1B3A2D",
                        "inverse-surface": "#1B3A2D",
                        "secondary": "#E8B931",
                        "tertiary": "#0B8264",
                        "inverse-primary": "#80CBC4",
                        "on-secondary": "#FFFFFF",
                        "primary-fixed": "#B2DFDB",
                        "on-secondary-fixed-variant": "#6B5100",
                        "surface-container": "#EAF3EF",
                        "outline-variant": "#BFC9C3",
                        "surface-dim": "#D4DDD8",
                        "outline": "#6F8A7E",
                        "surface-container-highest": "#DAE5E0",
                        "surface-container-lowest": "#FFFFFF",
                        "on-error": "#FFFFFF",
                        "tertiary-container": "#D4F0E6",
                        "on-tertiary": "#FFFFFF",
                        "background": "#F0F7F4",
                        "on-tertiary-fixed-variant": "#005740",
                        "on-primary-container": "#FFFFFF",
                        "error-container": "#FFDAD6",
                        "inverse-on-surface": "#E8F0EC",
                        "tertiary-fixed-dim": "#80CBC4",
                        "primary-container": "#0D9373",
                        "tertiary-fixed": "#B2DFDB",
                        "on-tertiary-container": "#0D9373",
                        "on-secondary-container": "#5C4500",
                        "secondary-container": "#FFEAA0",
                        "primary": "#0D9373",
                        "error": "#BA1A1A"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "margin-mobile": "16px",
                        "margin-desktop": "40px",
                        "container-max": "1440px",
                        "unit": "8px",
                        "gutter": "24px",
                        "sidebar-width": "320px",
                        "toolbar-height": "64px"
                    },
                    "fontFamily": {
                        "headline-lg-mobile": ["Poppins"],
                        "body-lg": ["Poppins"],
                        "label-sm": ["Poppins"],
                        "headline-xl": ["Poppins"],
                        "label-md": ["Poppins"],
                        "headline-md": ["Poppins"],
                        "body-md": ["Poppins"],
                        "headline-lg": ["Poppins"]
                    },
                    "fontSize": {
                        "headline-lg-mobile": ["24px", {"lineHeight": "32px", "fontWeight": "700"}],
                        "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}],
                        "label-sm": ["12px", {"lineHeight": "16px", "fontWeight": "500"}],
                        "headline-xl": ["48px", {"lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "label-md": ["14px", {"lineHeight": "20px", "letterSpacing": "0.01em", "fontWeight": "600"}],
                        "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                        "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                        "headline-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "700"}]
                    }
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .kerawang-strip {
            background-image: linear-gradient(90deg, #0D9373 25%, #E8B931 25%, #E8B931 50%, #0D9373 50%, #0D9373 75%, #E8B931 75%);
            background-size: 40px 4px;
            height: 4px;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }
    </style>
    @stack('styles')
</head>
<body class="bg-background text-on-surface font-body-md overflow-x-hidden">

    <!-- Top AppBar -->
    <header class="fixed top-0 left-0 w-full z-50 flex justify-between items-center px-gutter h-toolbar-height bg-surface-container/75 backdrop-blur-xl border-b border-[#0D9373]/10">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="font-headline-md text-headline-md font-bold text-primary hover:opacity-80 transition-opacity no-underline">ADMIN SENI DAN BUDAYA GAYO</a>
        </div>
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-2 px-3 py-1 bg-surface-container-low rounded-full border border-outline-variant">
                <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">search</span>
                <input class="bg-transparent border-none focus:ring-0 text-label-md w-48" placeholder="Cari data..." type="text"/>
            </div>
            <div class="flex items-center gap-4">
                <span class="material-symbols-outlined text-on-surface-variant cursor-pointer hover:text-primary transition-colors">notifications</span>
                <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-3 pl-4 border-l border-outline-variant hover:opacity-80 transition-opacity">
                    <div class="text-right">
                        <p class="font-label-md text-label-md">{{ auth()->check() ? auth()->user()->name : 'Admin' }}</p>
                        <p class="text-[10px] text-on-surface-variant uppercase tracking-wider">Admin Seni</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-on-primary font-bold overflow-hidden shadow-sm">
                        <img alt="Admin Profile" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCHA0OejV3FVaJo7xg0c_WGy2dG3OGrslWOESSFo3Jk9gQdgLYER-3bo92v68XTNf_v9n_htN-6Wis1--bE3x6g4i0hMHr0iHoEHZf5lvM72iNfPA1YoiDwW53X1nBLzlcCZapxwvKuU6h61pjuHTv9tuHtKpG5pYGouJZIbus-mqX9mHroaMBnReeSH_s7_aPrCvTzlCB3uWNuVU02lWjaj2BtM9rAprHQmj3wIeaHzvvnICUHTTSqLuByyenEtA-blc4CHqOW3XUU"/>
                    </div>
                </a>
            </div>
        </div>
    </header>

    <!-- Side NavBar -->
    <aside class="fixed left-6 top-24 bottom-6 rounded-xl w-sidebar-width bg-surface-container/75 backdrop-blur-xl border border-[#0D9373]/10 shadow-[0_0_20px_rgba(13,147,115,0.08)] flex flex-col py-6 overflow-y-auto">
        <div class="px-6 mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-white" style="font-variation-settings: 'FILL' 1;">account_balance</span>
                </div>
                <div>
                    <h2 class="font-headline-md text-[18px] text-primary font-bold">Admin Seni</h2>
                    <p class="text-[12px] text-on-surface-variant">Kabupaten Bener Meriah</p>
                </div>
            </div>
            <div class="kerawang-strip rounded-full mt-4"></div>
        </div>
        
        <nav class="flex flex-col gap-1 px-4 flex-1">
            <div class="px-4 py-2 text-[11px] font-bold text-on-surface-variant/60 uppercase tracking-[0.1em]">Utama</div>
            <a class="flex items-center gap-4 {{ request()->routeIs('admin.dashboard') ? 'bg-primary/20 text-primary border-l-4 border-primary px-4 py-3 rounded-r-lg transition-all translate-x-1' : 'text-on-surface-variant px-4 py-3 rounded-lg hover:bg-[#0D9373]/5 transition-all' }}" href="{{ route('admin.dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="font-label-md text-label-md">Dashboard</span>
            </a>
            
            <div class="px-4 py-2 mt-4 text-[11px] font-bold text-on-surface-variant/60 uppercase tracking-[0.1em]">Konten Peta</div>
            <a class="flex items-center gap-4 {{ request()->routeIs('admin.sanggars*') ? 'bg-primary/20 text-primary border-l-4 border-primary px-4 py-3 rounded-r-lg transition-all translate-x-1' : 'text-on-surface-variant px-4 py-3 rounded-lg hover:bg-[#0D9373]/5 transition-all' }}" href="{{ route('admin.sanggars.index') }}">
                <span class="material-symbols-outlined">theater_comedy</span>
                <span class="font-label-md text-label-md">Sanggar Seni</span>
            </a>
            <a class="flex items-center gap-4 {{ request()->routeIs('admin.tokos*') ? 'bg-primary/20 text-primary border-l-4 border-primary px-4 py-3 rounded-r-lg transition-all translate-x-1' : 'text-on-surface-variant px-4 py-3 rounded-lg hover:bg-[#0D9373]/5 transition-all' }}" href="{{ route('admin.tokos.index') }}">
                <span class="material-symbols-outlined">storefront</span>
                <span class="font-label-md text-label-md">Toko Kesenian</span>
            </a>
            <a class="flex items-center gap-4 {{ request()->routeIs('admin.jadwal*') ? 'bg-primary/20 text-primary border-l-4 border-primary px-4 py-3 rounded-r-lg transition-all translate-x-1' : 'text-on-surface-variant px-4 py-3 rounded-lg hover:bg-[#0D9373]/5 transition-all' }}" href="{{ route('admin.jadwal.index') }}">
                <span class="material-symbols-outlined">event_available</span>
                <span class="font-label-md text-label-md">Jadwal Penampilan</span>
            </a>
            <a class="flex items-center gap-4 {{ request()->routeIs('admin.berita*') ? 'bg-primary/20 text-primary border-l-4 border-primary px-4 py-3 rounded-r-lg transition-all translate-x-1' : 'text-on-surface-variant px-4 py-3 rounded-lg hover:bg-[#0D9373]/5 transition-all' }}" href="{{ route('admin.berita.index') }}">
                <span class="material-symbols-outlined">article</span>
                <span class="font-label-md text-label-md">Berita Terbaru</span>
            </a>
            

            <div class="px-4 py-2 mt-4 text-[11px] font-bold text-on-surface-variant/60 uppercase tracking-[0.1em]">Sistem</div>
            <a class="flex items-center gap-4 text-on-surface-variant px-4 py-3 rounded-lg hover:bg-[#0D9373]/5 transition-all" href="{{ route('publik.beranda') }}" target="_blank">
                <span class="material-symbols-outlined">language</span>
                <span class="font-label-md text-label-md">Lihat Website</span>
            </a>
            @if(auth()->user()->role === 'master')
            <a class="flex items-center gap-4 text-on-surface-variant px-4 py-3 rounded-lg hover:bg-[#0D9373]/5 transition-all" href="{{ route('master.dashboard') }}" target="_blank">
                <span class="material-symbols-outlined">admin_panel_settings</span>
                <span class="font-label-md text-label-md">Kembali ke Master</span>
            </a>
            @endif
            <a class="flex items-center gap-4 {{ request()->routeIs('admin.profile.edit') ? 'bg-primary/20 text-primary border-l-4 border-primary px-4 py-3 rounded-r-lg transition-all translate-x-1' : 'text-on-surface-variant px-4 py-3 rounded-lg hover:bg-[#0D9373]/5 transition-all' }}" href="{{ route('admin.profile.edit') }}">
                <span class="material-symbols-outlined">settings</span>
                <span class="font-label-md text-label-md">Pengaturan</span>
            </a>
            
            <form method="POST" action="{{ route('logout') }}" id="logout-form" class="hidden">
                @csrf
            </form>
            <a class="flex items-center gap-4 text-error px-4 py-3 rounded-lg hover:bg-error/5 transition-all mt-auto cursor-pointer" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <span class="material-symbols-outlined">logout</span>
                <span class="font-label-md text-label-md">Keluar</span>
            </a>
        </nav>
        

    </aside>

    <!-- Main Content Area -->
    <main class="ml-[360px] pt-24 pr-10 pb-10">
        <!-- Breadcrumbs -->
        <div class="mb-6 flex items-center gap-2 text-on-surface-variant/70 text-label-sm">
            <span class="material-symbols-outlined text-[16px]">home</span>
            <span>Admin</span>
            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            <span class="text-primary font-semibold">@yield('title', 'Dashboard')</span>
        </div>

        {{-- Flash messages --}}
        @if (session('sukses'))
            <div class="mb-6 flex items-center gap-3 p-4 bg-primary/10 border border-primary/20 text-primary rounded-2xl font-label-md text-label-md shadow-sm">
                <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                <span>{{ session('sukses') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 flex items-center gap-3 p-4 bg-error/10 border border-error/20 text-error rounded-2xl font-label-md text-label-md shadow-sm">
                <span class="material-symbols-outlined text-error" style="font-variation-settings: 'FILL' 1;">error</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>



    <script>
        // Smooth entry animations
        document.addEventListener('DOMContentLoaded', () => {
            const elements = document.querySelectorAll('section, .glass-panel, aside, header');
            elements.forEach((el, i) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(10px)';
                el.style.transition = 'all 0.6s cubic-bezier(0.22, 1, 0.36, 1)';
                setTimeout(() => {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, i * 100);
            });
        });

        // Hover interactions for cards
        const cards = document.querySelectorAll('.glass-panel');
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.classList.add('shadow-xl', 'shadow-primary/5');
            });
            card.addEventListener('mouseleave', () => {
                card.classList.remove('shadow-xl', 'shadow-primary/5');
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
