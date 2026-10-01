<!DOCTYPE html>
<html class="light" lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Admin Login | Gayo Heritage</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-tertiary-container": "#ffedf0",
                        "surface-dim": "#dadada",
                        "on-primary-fixed-variant": "#005312",
                        "outline-variant": "#bfcaba",
                        "inverse-primary": "#80CBC4",
                        "surface-tint": "#1b6d24",
                        "surface-container-highest": "#e2e2e2",
                        "secondary-container": "#FFEAA0",
                        "on-surface-variant": "#40493d",
                        "tertiary": "#923357",
                        "on-primary-container": "#cbffc2",
                        "tertiary-fixed": "#ffd9e2",
                        "on-secondary-container": "#6e5c00",
                        "on-background": "#1a1c1c",
                        "surface-variant": "#e2e2e2",
                        "outline": "#707a6c",
                        "on-tertiary-fixed": "#3f001c",
                        "error": "#ba1a1a",
                        "surface": "#f9f9f9",
                        "surface-container-high": "#e8e8e8",
                        "primary": "#0D9373",
                        "on-tertiary": "#ffffff",
                        "background": "#f9f9f9",
                        "primary-container": "#0D9373",
                        "primary-fixed-dim": "#80CBC4",
                        "on-secondary-fixed": "#221b00",
                        "error-container": "#ffdad6",
                        "surface-container-lowest": "#ffffff",
                        "on-primary-fixed": "#002204",
                        "on-secondary-fixed-variant": "#544600",
                        "tertiary-container": "#b14b6f",
                        "on-error-container": "#93000a",
                        "secondary-fixed": "#ffe16d",
                        "tertiary-fixed-dim": "#ffb1c7",
                        "surface-container": "#eeeeee",
                        "primary-fixed": "#a3f69c",
                        "on-primary": "#ffffff",
                        "surface-bright": "#f9f9f9",
                        "on-error": "#ffffff",
                        "surface-container-low": "#f3f3f4",
                        "secondary": "#705d00",
                        "inverse-on-surface": "#f0f1f1",
                        "on-tertiary-fixed-variant": "#7f2448",
                        "secondary-fixed-dim": "#e9c400",
                        "on-surface": "#1a1c1c",
                        "inverse-surface": "#2f3131",
                        "on-secondary": "#ffffff"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "gutter": "24px",
                        "margin-desktop": "40px",
                        "unit": "8px",
                        "margin-mobile": "16px",
                        "container-max": "1440px"
                    },
                    "fontFamily": {
                        "label-md": ["Inter"],
                        "headline-md": ["Inter"],
                        "headline-lg-mobile": ["Inter"],
                        "label-sm": ["Inter"],
                        "headline-xl": ["Inter"],
                        "body-lg": ["Inter"],
                        "body-md": ["Inter"],
                        "headline-lg": ["Inter"]
                    },
                    "fontSize": {
                        "label-md": ["14px", {"lineHeight": "20px", "letterSpacing": "0.01em", "fontWeight": "600"}],
                        "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                        "headline-lg-mobile": ["24px", {"lineHeight": "32px", "fontWeight": "700"}],
                        "label-sm": ["12px", {"lineHeight": "16px", "fontWeight": "500"}],
                        "headline-xl": ["48px", {"lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}],
                        "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                        "headline-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "700"}]
                    }
                }
            }
        }
    </script>
<style>.kerawang-pattern {
    background-color: #f9f9f9;
    background-image: url(https://lh3.googleusercontent.com/aida-public/AB6AXuDw_LXlYP6BX8BFI35vBLjz5GGnw5miPDCm8D1195miyv_c8iA7wLmjPki7NKoCD-0jyXMEWo7OZz3Wk2yCMl-hs4KVKKGrWfDoVQMQt_aa-nIklsO8IcA5Amuq8xL7BB12AXrzRkxqqeUFc_jTw4TAp53Rct7lsZEl1piFc2WBy74KMyHMfAUE5iFq6vpxKLoidwse57fB_CVc8QGcJVEiilJS4bSGyWxbXwxJWZyn69pvJxmchqqVcIWRKj3nUHuXSMEvbUHM2cxk);
    /* Placeholder for geometric texture */
    background-repeat: repeat;
    opacity: 0.03
    }
/* Custom scrollbar for professional look */
::-webkit-scrollbar {
    width: 6px
    }
::-webkit-scrollbar-track {
    background: #f1f1f1
    }
::-webkit-scrollbar-thumb {
    background: #0D9373;
    border-radius: 10px
    }
.material-symbols-outlined {
    font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24
    }</style>
</head>
<body class="bg-background font-body-md text-on-surface selection:bg-primary-fixed selection:text-on-primary-fixed">
<!-- Top Navigation Suppression (Login context) -->
<main class="min-h-screen relative flex items-center justify-center px-margin-mobile md:px-margin-desktop overflow-hidden">
<!-- Watermark Background Layer -->
<div class="absolute inset-0 kerawang-pattern pointer-events-none"></div>
<!-- Animated Background Accent -->
<div class="absolute -top-24 -left-24 w-96 h-96 bg-primary opacity-5 rounded-full blur-3xl"></div>
<div class="absolute -bottom-24 -right-24 w-96 h-96 bg-secondary-container opacity-10 rounded-full blur-3xl"></div>
<!-- Login Card Container -->
<div class="relative w-full max-w-md z-10">
<!-- Branding Header -->
<div class="flex flex-col items-center mb-8">
<div class="mb-4 p-4 rounded-xl bg-surface-container-lowest shadow-sm border border-outline-variant">
<span class="material-symbols-outlined text-primary text-[48px]" style="font-variation-settings: 'FILL' 1;">account_balance</span>
</div>
<h1 class="font-headline-md text-headline-md text-primary tracking-tight text-center">ADMIN SENI DAN BUDAYA <br> BENER MERIAH</h1>
<p class="font-label-md text-label-md text-on-surface-variant mt-1 text-center">Sistem Informasi pengelolaan adminstrasi Budaya</p>
</div>
<!-- Main Form Card -->
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm p-8 md:p-10">
<div class="mb-8">
<h2 class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface font-bold">Selamat Datang</h2>
<p class="font-body-md text-body-md text-on-surface-variant">Silakan masuk untuk mengelola konten warisan budaya.</p>
</div>
<form action="{{ route('login') }}" class="space-y-6" method="POST">
    @csrf
<!-- Username/Email Field -->
<div class="space-y-2">
<label class="font-label-md text-label-md text-on-surface-variant" for="login">Nama Pengguna atau Email</label>
<div class="relative group">
<div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-outline">
<span class="material-symbols-outlined text-[20px]">person</span>
</div>
<input class="w-full pl-11 pr-4 py-3 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 outline-none placeholder:text-outline-variant" id="login" name="login" value="{{ old('login') }}" placeholder="admin@gayoheritage.id atau admin" required="" autofocus type="text"/>
</div>
@error('login')
    <span class="text-error text-xs mt-1 text-red-500">{{ $message }}</span>
@enderror
</div>
<!-- Password Field -->
<div class="space-y-2">
<div class="flex justify-between items-center">
<label class="font-label-md text-label-md text-on-surface-variant" for="password">Kata Sandi</label>
@if (Route::has('password.request'))
<a class="font-label-sm text-label-sm text-primary hover:text-primary-container transition-colors" href="{{ route('password.request') }}">Lupa Kata Sandi?</a>
@endif
</div>
<div class="relative group">
<div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-outline">
<span class="material-symbols-outlined text-[20px]">lock</span>
</div>
<input class="w-full pl-11 pr-12 py-3 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 outline-none placeholder:text-outline-variant" id="password" name="password" placeholder="••••••••" required="" type="password"/>
<button class="absolute inset-y-0 right-0 pr-4 flex items-center text-outline hover:text-primary transition-colors" onclick="togglePassword()" type="button">
<span class="material-symbols-outlined text-[20px]" id="eye-icon">visibility</span>
</button>
</div>
@error('password')
    <span class="text-error text-xs mt-1 text-red-500">{{ $message }}</span>
@enderror
</div>
<!-- Remember Me Toggle -->
<div class="flex items-center">
<input class="h-4 w-4 text-primary focus:ring-primary border-outline-variant rounded cursor-pointer" id="remember-me" name="remember" type="checkbox"/>
<label class="ml-2 block font-label-sm text-label-sm text-on-surface-variant cursor-pointer" for="remember-me">
                            Ingat saya di perangkat ini
                        </label>
</div>
<!-- Submit Button -->
<button class="w-full py-4 bg-primary text-on-primary font-label-md text-label-md rounded-lg shadow-md hover:bg-primary-container active:opacity-80 transition-all duration-200 flex items-center justify-center gap-2 group" type="submit">
<span>Masuk</span>
<span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
</button>
<div class="mt-6 text-center">
    <a href="{{ route('publik.chat.index') }}" class="font-label-sm text-label-sm text-primary hover:underline ml-1 font-bold">Hubungi Master Admin Seni</a>
</div>
</form>
<!-- Footer Accents in Card -->
<div class="mt-8 pt-6 border-t border-outline-variant flex flex-col items-center gap-4">
<div class="flex items-center gap-1">
<div class="h-1 w-8 bg-primary rounded-full"></div>
<div class="h-1 w-2 bg-secondary-container rounded-full"></div>
<div class="h-1 w-1 bg-tertiary-container rounded-full"></div>
</div>
<p class="font-label-sm text-label-sm text-on-surface-variant text-center px-4">
                        Keamanan data dijamin oleh sistem enkripsi standar industri Seni budaya Gayo
                    </p>
</div>
</div>
<!-- Sub-footer links -->
<div class="mt-8 flex justify-center gap-6">
<a class="font-label-sm text-label-sm text-on-surface-variant hover:text-primary transition-colors" href="#">Bantuan</a>
<span class="text-outline-variant">•</span>
<a class="font-label-sm text-label-sm text-on-surface-variant hover:text-primary transition-colors" href="#">Kebijakan Privasi</a>
<span class="text-outline-variant">•</span>
<a class="font-label-sm text-label-sm text-on-surface-variant hover:text-primary transition-colors" href="#">Kontak Kami</a>
</div>
</div>
</main>
<!-- Shared Component: Footer -->
<footer class="w-full py-8 flex flex-col md:flex-row justify-between items-center px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto border-t border-outline-variant">
<div class="flex flex-col md:items-start items-center gap-2">
<span class="font-headline-md text-headline-md font-bold text-primary">SENI DAN BUDAYA GAYO</span>
<p class="font-label-sm text-label-sm text-on-surface-variant">© 2026 SENI DAN BUDAYA  Platform. All rights reserved</p>
</div>
<div class="flex gap-6 mt-6 md:mt-0">
<a class="font-label-sm text-label-sm text-on-surface-variant hover:text-secondary-fixed-variant underline transition-all duration-200" href="#">Privacy Policy</a>
<a class="font-label-sm text-label-sm text-on-surface-variant hover:text-secondary-fixed-variant underline transition-all duration-200" href="#">Terms of Service</a>
<a class="font-label-sm text-label-sm text-on-surface-variant hover:text-secondary-fixed-variant underline transition-all duration-200" href="#">Support</a>
</div>
</footer>
<script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerText = 'visibility_off';
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerText = 'visibility';
            }
        }
        
        // Add a subtle entrance animation
        window.addEventListener('load', () => {
            const card = document.querySelector('.relative.w-full.max-w-md');
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.6s ease-out';
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body></html>
