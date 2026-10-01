@extends(auth()->user()->role === 'master' ? 'layouts.master' : 'layouts.admin')
@section('title', 'Dashboard')

@section('content')

<!-- Hero Section -->
<section class="relative overflow-hidden rounded-3xl mb-8 bg-primary-container text-white p-10 min-h-[280px] flex flex-col justify-center shadow-md">
    <!-- Decorative background element -->
    <div class="absolute right-0 top-0 w-1/2 h-full opacity-10 pointer-events-none overflow-hidden">
        <div class="scale-150 rotate-12 grid grid-cols-4 gap-4">
            <div class="w-32 h-32 border-4 border-white rounded-full"></div>
            <div class="w-32 h-32 border-4 border-white rounded-full"></div>
            <div class="w-32 h-32 border-4 border-white rounded-full"></div>
            <div class="w-32 h-32 border-4 border-white rounded-full"></div>
        </div>
    </div>
    <div class="relative z-10 max-w-2xl">
        <h1 class="font-headline-xl text-headline-xl mb-4">Selamat Datang, {{ auth()->user()->name }}! 👋</h1>
        <p class="text-body-lg opacity-90 leading-relaxed">
            Selamat datang di panel administrasi seni dan budaya Gayo, setiap kegiatan seni bisa memasukkan tentang Informasi seni budaya di kabupaten Bener meriah, Salam seni Budaya
        </p>
    </div>
</section>

<!-- Stats Grid -->
<h3 class="font-headline-md text-headline-md mb-6 text-on-surface/80 flex items-center gap-3">
    <span class="w-8 h-1 bg-primary rounded-full"></span>
    Ringkasan Data
</h3>

<div class="grid grid-cols-1 md:grid-cols-3 gap-gutter mb-8">
    {{-- Card Sanggar --}}
    <div class="glass-panel p-6 rounded-2xl hover:border-primary/50 transition-all group relative overflow-hidden shadow-sm">
        <div class="absolute top-0 left-0 w-1 h-full bg-primary"></div>
        <div class="flex justify-between items-start">
            <div>
                <p class="text-on-surface-variant font-label-md text-label-md mb-1">Sanggar Seni</p>
                <h4 class="text-4xl font-extrabold text-on-surface">{{ number_format($stats['sanggar'] ?? 0) }}</h4>
            </div>
            <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-[32px]">palette</span>
            </div>
        </div>
        <div class="mt-6 flex items-center text-[12px] text-primary font-semibold">
            <span class="material-symbols-outlined text-[16px] mr-1">trending_up</span>
            Terdaftar & Aktif
        </div>
    </div>

    {{-- Card Toko --}}
    <div class="glass-panel p-6 rounded-2xl hover:border-secondary transition-all group relative overflow-hidden shadow-sm">
        <div class="absolute top-0 left-0 w-1 h-full bg-secondary"></div>
        <div class="flex justify-between items-start">
            <div>
                <p class="text-on-surface-variant font-label-md text-label-md mb-1">Toko Kesenian</p>
                <h4 class="text-4xl font-extrabold text-on-surface">{{ number_format($stats['toko'] ?? 0) }}</h4>
            </div>
            <div class="w-12 h-12 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-[32px]">storefront</span>
            </div>
        </div>
        <div class="mt-6 flex items-center text-[12px] text-secondary font-semibold">
            <span class="material-symbols-outlined text-[16px] mr-1">shopping_bag</span>
            Mitra Kesenian Gayo
        </div>
    </div>

    {{-- Card Acara Hari Ini --}}
    <div class="glass-panel p-6 rounded-2xl hover:border-tertiary transition-all group relative overflow-hidden shadow-sm">
        <div class="absolute top-0 left-0 w-1 h-full bg-tertiary"></div>
        <div class="flex justify-between items-start">
            <div>
                <p class="text-on-surface-variant font-label-md text-label-md mb-1">Acara Hari Ini</p>
                <h4 class="text-4xl font-extrabold text-on-surface">{{ number_format($stats['jadwal'] ?? 0) }}</h4>
            </div>
            <div class="w-12 h-12 rounded-xl bg-tertiary/10 flex items-center justify-center text-tertiary group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-[32px]">event_note</span>
            </div>
        </div>
        <div class="mt-6 flex items-center text-[12px] text-tertiary font-semibold">
            <span class="material-symbols-outlined text-[16px] mr-1">event_available</span>
            Penampilan Terjadwal
        </div>
    </div>
</div>

<!-- Quick Guide Section -->
<div class="glass-panel p-8 rounded-3xl border-l-4 border-primary shadow-sm">
    <div class="flex items-center gap-3 mb-6">
        <span class="material-symbols-outlined text-secondary-fixed-dim text-[32px]" style="font-variation-settings: 'FILL' 1;">lightbulb</span>
        <h3 class="font-headline-md text-headline-md">Panduan Cepat Navigasi</h3>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="space-y-6">
            <div class="flex gap-4">
                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-sm">1</div>
                <p class="text-on-surface-variant leading-relaxed">
                    Gunakan menu <strong class="text-primary">Sanggar Seni</strong> untuk menambah, mengedit, atau menghapus titik lokasi sanggar di peta interaktif kami.
                </p>
            </div>
            <div class="flex gap-4">
                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-sm">2</div>
                <p class="text-on-surface-variant leading-relaxed">
                    Gunakan menu <strong class="text-primary">Toko Kesenian</strong> untuk mengelola toko-toko yang menjual kerajinan otentik atau alat musik tradisional Gayo.
                </p>
            </div>
        </div>
        <div class="space-y-6">
            <div class="flex gap-4">
                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-sm">3</div>
                <p class="text-on-surface-variant leading-relaxed">
                    Gunakan menu <strong class="text-primary">Jadwal Penampilan</strong> untuk menambahkan event seni budaya atau pertunjukan live yang akan datang di wilayah Gayo.
                </p>
            </div>
            <div class="flex gap-4">
                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-secondary/10 flex items-center justify-center text-secondary font-bold text-sm">!</div>
                <p class="text-on-surface-variant leading-relaxed italic">
                    Pastikan <strong class="text-on-surface">Titik Koordinat (Latitude &amp; Longitude)</strong> diisi dengan benar dengan cara mengklik peta pada saat form tambah data untuk akurasi pemetaan.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- System Overview Section (Asymmetric Bento Element) -->
<div class="mt-gutter grid grid-cols-1 gap-gutter">
    <div class="bg-white border border-outline-variant p-8 rounded-3xl shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-headline-md text-[20px]">Aktivitas Terakhir</h3>
            <button class="text-primary font-label-md text-label-md flex items-center gap-1">
                Lihat Semua <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </button>
        </div>
        <div class="flex flex-col items-center justify-center py-16 text-on-surface-variant/40">
            <span class="material-symbols-outlined text-[64px] mb-4">history</span>
            <p class="font-label-md text-label-md">Belum ada aktivitas yang tercatat baru-baru ini.</p>
        </div>
    </div>
</div>

@endsection
