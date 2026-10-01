@extends('layouts.publik')

@section('title', $toko->nama_toko . ' | Gayo Heritage')

@push('styles')
<style>
    .kerawang-strip {
        height: 4px;
        background: linear-gradient(90deg, var(--t-accent) 0%, var(--t-icon-color) 50%, var(--t-accent) 100%);
    }
    .hero-mask {
        background: var(--t-hero-overlay);
    }
</style>
@endpush

@section('content')
<main class="min-h-screen pt-16 pb-20">
    <!-- Hero Banner Section -->
    <section class="relative h-[65vh] w-full overflow-hidden">
        <img class="w-full h-full object-cover grayscale-[0.2] brightness-[0.5] contrast-[1.1]" 
             src="{{ $toko->foto_utama_url }}" 
             alt="{{ $toko->nama_toko }}">
        
        <div class="absolute inset-0 hero-mask flex flex-col justify-end">
            <div class="max-w-6xl mx-auto px-6 w-full pb-12">
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded" style="background: var(--t-accent); color: white;">
                        {{ $toko->jenis_produk }}
                    </span>
                    <span class="text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded" style="background: var(--t-surface-container); color: var(--t-on-surface);">
                        {{ $toko->status }}
                    </span>
                </div>
                <h1 class="font-headline-xl text-4xl md:text-6xl font-extrabold tracking-tight mb-4" style="color: var(--t-text-hero);">
                    {{ $toko->nama_toko }}
                </h1>
                <p class="max-w-2xl text-base md:text-lg leading-relaxed font-light" style="color: var(--t-text-hero-sub);">
                    Karya seni dan kerajinan khas Gayo autentik dengan kualitas tinggi dari {{ $toko->jenis_produk }}.
                </p>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 kerawang-strip"></div>
    </section>

    <!-- Content Details Section -->
    <section class="max-w-6xl mx-auto px-6 mt-16 grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Legacy Info (Left side, takes 2 cols) -->
        <div class="lg:col-span-2 space-y-8">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest" style="color: var(--t-icon-color);">ARTISANAL PRODUCTS</span>
                <h2 class="text-headline-md text-2xl font-bold mt-2 mb-4" style="color: var(--t-accent);">The Legacy of Gayo Crafts</h2>
                <p class="leading-relaxed text-base font-light" style="color: var(--t-on-surface-variant);">
                    {{ $toko->deskripsi ?: 'Toko kerajinan tangan khas Gayo ini menyediakan berbagai produk kerawang otentik, kerajinan seni pahat kayu, busana adat, dan cinderamata berkualitas premium langsung dari pengrajin lokal Bener Meriah.' }}
                </p>
            </div>
        </div>

        <!-- Contacts Sidebar (Right side, takes 1 col) -->
        <div class="space-y-6">
            <!-- Artist Contact Card -->
            <div class="glass p-6 rounded-2xl shadow-xl space-y-6">
                <h3 class="font-bold text-lg pb-3" style="color: var(--t-accent); border-bottom: 1px solid var(--t-section-border);">Informasi Toko</h3>
                
                <div class="space-y-4 text-sm" style="color: var(--t-on-surface-variant);">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined" style="color: var(--t-accent);">map</span>
                        <div>
                            <p class="font-semibold" style="color: var(--t-on-surface);">Alamat</p>
                            <p class="text-xs mt-1 leading-normal">{{ $toko->alamat }}, Kec. {{ $toko->kecamatan }}, {{ $toko->kabupaten }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined" style="color: var(--t-accent);">phone</span>
                        <div>
                            <p class="font-semibold" style="color: var(--t-on-surface);">Hubungi</p>
                            <a href="tel:{{ $toko->no_telepon }}" class="text-xs hover-text-accent transition-colors font-mono">{{ $toko->no_telepon ?: '-' }}</a>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined" style="color: var(--t-accent);">schedule</span>
                        <div>
                            <p class="font-semibold" style="color: var(--t-on-surface);">Jam Operasional</p>
                            <p class="text-xs">{{ $toko->jam_buka ?: 'Setiap Hari 08:00 - 18:00' }}</p>
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <a href="{{ url('/peta#toko-' . $toko->slug) }}" 
                       class="w-full font-bold py-3 rounded-xl shadow-lg hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2 text-sm text-center"
                       style="background: var(--t-accent); color: white;">
                        <span class="material-symbols-outlined text-sm">explore</span> Tampilkan di Peta
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Visual Gallery (Galeri Produk & Toko) -->
    <section class="max-w-6xl mx-auto px-6 mt-20 pt-16" style="border-top: 1px solid var(--t-section-border);">
        <div class="flex justify-between items-center mb-8">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest" style="color: var(--t-icon-color);">PRODUCT GALLERY</span>
                <h2 class="text-headline-md text-2xl font-bold mt-2" style="color: var(--t-accent);">Koleksi Kerajinan & Produk</h2>
            </div>
        </div>

        @if($toko->galeri && $toko->galeri->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($toko->galeri as $galeri)
                    <div class="group relative rounded-xl overflow-hidden shadow-lg aspect-square transform hover:scale-[1.02] transition-transform duration-300" style="background: var(--t-surface-container); border: 1px solid var(--t-section-border);">
                        <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
                             src="{{ $galeri->url }}" 
                             alt="{{ $galeri->judul }}">
                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-end p-4">
                            <p class="text-sm font-bold text-white">{{ $galeri->judul ?: 'Galeri Toko' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Beautiful visual mockup cards as fallback -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="rounded-xl overflow-hidden shadow-lg aspect-[4/3] relative group" style="background: var(--t-surface-container); border: 1px solid var(--t-section-border);">
                    <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
                         src="https://images.unsplash.com/photo-1605722243979-fe0be8158232?q=80&w=1000" 
                         alt="Mockup Gallery 1">
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-end p-4">
                        <p class="text-sm font-bold text-white">Kerawang Bordir Gayo Premium</p>
                    </div>
                </div>
                <div class="rounded-xl overflow-hidden shadow-lg aspect-[4/3] relative group" style="background: var(--t-surface-container); border: 1px solid var(--t-section-border);">
                    <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
                         src="https://images.unsplash.com/photo-1513519245088-0e12902e5a38?q=80&w=1000" 
                         alt="Mockup Gallery 2">
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-end p-4">
                        <p class="text-sm font-bold text-white">Kerajinan Pahat Kayu Etnik</p>
                    </div>
                </div>
                <div class="rounded-xl overflow-hidden shadow-lg aspect-[4/3] relative group" style="background: var(--t-surface-container); border: 1px solid var(--t-section-border);">
                    <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
                         src="https://images.unsplash.com/photo-1459411552884-841db9b3cc2a?q=80&w=1000" 
                         alt="Mockup Gallery 3">
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-end p-4">
                        <p class="text-sm font-bold text-white">Cinderamata dan Aksesoris Adat</p>
                    </div>
                </div>
            </div>
        @endif
    </section>
</main>
@endsection
