<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Daftar Akun Baru | Gayo Heritage</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#0D9373",
                        "primary-container": "#0D9373",
                        "on-primary": "#ffffff",
                        "on-surface": "#1a1c1c",
                        "on-surface-variant": "#40493d",
                        "surface-container-low": "#f3f3f4",
                        "outline-variant": "#bfcaba",
                        "error": "#ba1a1a",
                    },
                    fontFamily: {
                        sans: ["Inter", "sans-serif"],
                    }
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .bg-gayo-pattern {
            background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDw_LXlYP6BX8BFI35vBLjz5GGnw5miPDCm8D1195miyv_c8iA7wLmjPki7NKoCD-0jyXMEWo7OZz3Wk2yCMl-hs4KVKKGrWfDoVQMQt_aa-nIklsO8IcA5Amuq8xL7BB12AXrzRkxqqeUFc_jTw4TAp53Rct7lsZEl1piFc2WBy74KMyHMfAUE5iFq6vpxKLoidwse57fB_CVc8QGcJVEiilJS4bSGyWxbXwxJWZyn69pvJxmchqqVcIWRKj3nUHuXSMEvbUHM2cxk');
            background-size: cover;
            background-position: center;
        }
        .glass-panel {
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 204, 0, 0.3);
        }
    </style>
</head>
<body class="font-sans text-on-surface antialiased bg-white overflow-hidden">
    <div class="flex h-screen w-full">
        <!-- Left Side: Banner / Info -->
        <div class="hidden lg:flex lg:w-5/12 bg-[#1b2b1c] relative flex-col justify-between p-12 bg-gayo-pattern">
            <!-- Overlay to darken background -->
            <div class="absolute inset-0 bg-[#0d2a13] bg-opacity-80"></div>
            
            <!-- Content -->
            <div class="relative z-10 flex flex-col h-full justify-between">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#0D9373] rounded flex items-center justify-center">
                        <span class="material-symbols-outlined text-[#1b2b1c] font-bold">map</span>
                    </div>
                    <span class="text-white text-xl font-bold tracking-wide">ADMIN SENI DAN BUDAYA GAYO</span>
                </div>

                <!-- Main Text Glass Card -->
                <div class="glass-panel p-8 rounded-xl max-w-md">
                    <h1 class="text-white text-2xl lg:text-3xl font-bold leading-tight mb-4">
                        Pemetaan Kesenian Tradisional Gayo Menggunakan Sistem Informasi Geografis (SIG) Di Kabupaten Bener Meriah
                    </h1>
                    <div class="w-12 h-1 bg-[#0D9373] mb-4"></div>
                    <p class="text-gray-300 text-sm leading-relaxed">
                        Platform digital untuk mendokumentasikan, melestarikan, dan memetakan kekayaan budaya dataran tinggi Gayo secara presisi dan informatif.
                    </p>
                </div>

                <!-- Footer Features -->
                <div class="flex items-center gap-6 text-xs text-white font-medium">
                    <div class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px] text-[#0D9373]">check_circle</span>
                        Warisan Budaya
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px] text-[#0D9373]">location_on</span>
                        Pemetaan Presisi
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px] text-[#0D9373]">public</span>
                        Akses Terbuka
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Registration Form -->
        <div class="w-full lg:w-7/12 flex items-center justify-center p-8 overflow-y-auto">
            <div class="w-full max-w-md space-y-8">
                <!-- Header -->
                <div>
                    <h2 class="text-3xl font-bold text-primary mb-2">Daftar Akun Baru</h2>
                    <p class="text-sm text-on-surface-variant">Silakan isi formulir di bawah ini untuk bergabung dalam platform SENI DAN BUDAYA GAYO.</p>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf
                    
                    <!-- Nama Lengkap -->
                    <div class="space-y-1">
                        <label for="name" class="block text-xs font-bold text-on-surface">Nama Lengkap</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-on-surface-variant">
                                <span class="material-symbols-outlined text-[18px]">person</span>
                            </div>
                            <input id="name" name="name" type="text" class="w-full pl-10 pr-4 py-2.5 bg-white border border-outline-variant rounded-md focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all text-sm" placeholder="Masukkan nama sesuai KTP" value="{{ old('name') }}" required autofocus />
                        </div>
                        @error('name')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Nama Pengguna -->
                    <div class="space-y-1">
                        <label for="username" class="block text-xs font-bold text-on-surface">Nama Pengguna</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-on-surface-variant">
                                <span class="material-symbols-outlined text-[18px]">alternate_email</span>
                            </div>
                            <input id="username" name="username" type="text" class="w-full pl-10 pr-4 py-2.5 bg-white border border-outline-variant rounded-md focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all text-sm" placeholder="Min. 8 huruf/angka" value="{{ old('username') }}" />
                        </div>
                        @error('username')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Alamat Email -->
                    <div class="space-y-1">
                        <label for="email" class="block text-xs font-bold text-on-surface">Alamat Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-on-surface-variant">
                                <span class="material-symbols-outlined text-[18px]">mail</span>
                            </div>
                            <input id="email" name="email" type="email" class="w-full pl-10 pr-4 py-2.5 bg-white border border-outline-variant rounded-md focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all text-sm" placeholder="contoh@email.com" value="{{ old('email') }}" required />
                        </div>
                        @error('email')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Nomor WhatsApp -->
                    <div class="space-y-1">
                        <label for="phone" class="block text-xs font-bold text-on-surface">Nomor WhatsApp</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-on-surface-variant">
                                <span class="material-symbols-outlined text-[18px]">call</span>
                            </div>
                            <input id="phone" name="phone" type="text" class="w-full pl-10 pr-4 py-2.5 bg-white border border-outline-variant rounded-md focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all text-sm" placeholder="0812XXXXXXXX" value="{{ old('phone') }}" />
                        </div>
                    </div>

                    <!-- Kata Sandi -->
                    <div class="space-y-1">
                        <label for="password" class="block text-xs font-bold text-on-surface">Kata Sandi</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-on-surface-variant">
                                <span class="material-symbols-outlined text-[18px]">lock</span>
                            </div>
                            <input id="password" name="password" type="password" class="w-full pl-10 pr-10 py-2.5 bg-white border border-outline-variant rounded-md focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all text-sm" placeholder="Minimal 8 karakter" required />
                            <button type="button" onclick="togglePassword('password', 'eye-icon-1')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-on-surface-variant hover:text-primary transition-colors">
                                <span id="eye-icon-1" class="material-symbols-outlined text-[18px]">visibility</span>
                            </button>
                        </div>
                        @error('password')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Konfirmasi Kata Sandi -->
                    <div class="space-y-1">
                        <label for="password_confirmation" class="block text-xs font-bold text-on-surface">Konfirmasi Kata Sandi</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-on-surface-variant">
                                <span class="material-symbols-outlined text-[18px]">lock_reset</span>
                            </div>
                            <input id="password_confirmation" name="password_confirmation" type="password" class="w-full pl-10 pr-10 py-2.5 bg-white border border-outline-variant rounded-md focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all text-sm" placeholder="Ulangi kata sandi" required />
                            <button type="button" onclick="togglePassword('password_confirmation', 'eye-icon-2')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-on-surface-variant hover:text-primary transition-colors">
                                <span id="eye-icon-2" class="material-symbols-outlined text-[18px]">visibility</span>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" class="w-full py-3 bg-primary text-white font-bold rounded-md shadow hover:bg-primary-container active:scale-[0.98] transition-all flex items-center justify-center gap-2 group">
                            Daftar Sekarang
                            <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </button>
                    </div>
                </form>

                <!-- Footer -->
                <div class="mt-6 pt-6 border-t border-gray-100 text-center">
                    <p class="text-xs text-on-surface-variant">
                        Sudah punya akun? <a href="{{ route('login') }}" class="text-primary font-bold hover:underline">Login di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerText = 'visibility_off';
            } else {
                input.type = 'password';
                icon.innerText = 'visibility';
            }
        }
    </script>
</body>
</html>
