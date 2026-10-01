@extends('layouts.publik')

@section('title', $sanggar->nama_sanggar . ' | Gayo Heritage')

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
             src="{{ $sanggar->foto_utama_url }}" 
             alt="{{ $sanggar->nama_sanggar }}">
        
        <div class="absolute inset-0 hero-mask flex flex-col justify-end">
            <div class="max-w-6xl mx-auto px-6 w-full pb-12">
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-white px-3 py-1 rounded" style="background: var(--t-accent);">
                        {{ $sanggar->kategori }}
                    </span>
                    <span class="text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded" style="background: var(--t-surface-container); color: var(--t-on-surface);">
                        {{ $sanggar->status }}
                    </span>
                </div>
                <h1 class="font-headline-xl text-4xl md:text-6xl font-extrabold tracking-tight mb-4" style="color: var(--t-text-hero);">
                    {{ $sanggar->nama_sanggar }}
                </h1>
                <p class="max-w-2xl text-base md:text-lg leading-relaxed font-light" style="color: var(--t-text-hero-sub);">
                    Preserving the soul of the Gayo Highlands through the precision and ethnic heritage of {{ $sanggar->kategori }} arts.
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
                <span class="text-xs font-bold uppercase tracking-widest" style="color: var(--t-icon-color);">HISTORY & HERITAGE</span>
                <h2 class="text-headline-md text-2xl font-bold mt-2 mb-4" style="color: var(--t-accent);">The Legacy of {{ $sanggar->kecamatan }}</h2>
                <p class="leading-relaxed text-base font-light" style="color: var(--t-on-surface-variant);">
                    {{ $sanggar->deskripsi ?: 'Sanggar ini didirikan untuk menjadi wadah pelestarian adat, seni musik, tari, dan warisan budaya luhur masyarakat Gayo di Bener Meriah. Menjadi mercusuar edukasi generasi muda agar cinta dan bangga akan warisan leluhur mereka.' }}
                </p>
            </div>


        </div>

        <!-- Master Artists & Contacts Sidebar (Right side, takes 1 col) -->
        <div class="space-y-6">
            <!-- Artist Contact Card -->
            <div class="glass p-6 rounded-2xl shadow-xl space-y-6">
                <h3 class="font-bold text-lg pb-3" style="color: var(--t-accent); border-bottom: 1px solid var(--t-section-border);">Informasi Kontak</h3>
                
                <div class="space-y-4 text-sm" style="color: var(--t-on-surface-variant);">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined" style="color: var(--t-accent);">map</span>
                        <div>
                            <p class="font-semibold" style="color: var(--t-on-surface);">Alamat Lengkap</p>
                            <p class="text-xs mt-1 leading-normal">{{ $sanggar->alamat }}, Kec. {{ $sanggar->kecamatan }}, {{ $sanggar->kabupaten }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined" style="color: var(--t-accent);">phone</span>
                        <div>
                            <p class="font-semibold" style="color: var(--t-on-surface);">Hubungi</p>
                            <a href="tel:{{ $sanggar->no_telepon }}" class="text-xs hover-text-accent transition-colors font-mono">{{ $sanggar->no_telepon ?: '-' }}</a>
                        </div>
                    </div>

                    @if($sanggar->email)
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined" style="color: var(--t-accent);">mail</span>
                        <div>
                            <p class="font-semibold" style="color: var(--t-on-surface);">Email</p>
                            <p class="text-xs font-mono">{{ $sanggar->email }}</p>
                        </div>
                    </div>
                    @endif

                    @if($sanggar->instagram)
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined" style="color: var(--t-accent);">photo_camera</span>
                        <div>
                            <p class="font-semibold" style="color: var(--t-on-surface);">Instagram</p>
                            <a href="https://instagram.com/{{ ltrim($sanggar->instagram, '@') }}" target="_blank" class="text-xs hover-text-accent transition-colors">
                                {{ $sanggar->instagram }}
                            </a>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="pt-4">
                    <a href="{{ url('/peta#sanggar-' . $sanggar->slug) }}" 
                       class="w-full font-bold py-3 rounded-xl shadow-lg hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2 text-sm text-center"
                       style="background: var(--t-accent); color: white;">
                        <span class="material-symbols-outlined text-sm">explore</span> Tampilkan di Peta
                    </a>
                </div>
            </div>

            @if($sanggar->nama_pimpinan)
            <!-- Pimpinan Sanggar -->
            <div class="glass p-6 rounded-2xl shadow-xl space-y-4">
                <h3 class="font-bold text-lg pb-3" style="color: var(--t-accent); border-bottom: 1px solid var(--t-section-border);">Pimpinan Sanggar</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold" style="background: var(--t-surface-container); color: var(--t-accent);">
                            {{ strtoupper(substr($sanggar->nama_pimpinan, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold" style="color: var(--t-on-surface);">{{ $sanggar->nama_pimpinan }}</p>
                            <p class="text-[10px] uppercase" style="color: var(--t-on-surface-variant);">Pimpinan</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>

    <!-- Visual Gallery (Performances & Répétitions) -->
    <section class="max-w-6xl mx-auto px-6 mt-20 pt-16" style="border-top: 1px solid var(--t-section-border);">
        <div class="flex justify-between items-center mb-8">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest" style="color: var(--t-icon-color);">VISUAL ARCHIVES</span>
                <h2 class="text-headline-md text-2xl font-bold mt-2" style="color: var(--t-accent);">Performances & Répétitions</h2>
            </div>
        </div>

        @if($sanggar->galeri && $sanggar->galeri->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($sanggar->galeri as $galeri)
                    <a href="{{ $galeri->url }}" target="_blank" class="group relative rounded-xl overflow-hidden shadow-lg aspect-square transform hover:scale-[1.02] transition-transform duration-300 block" style="background: var(--t-surface-container); border: 1px solid var(--t-section-border);">
                        @if($galeri->tipe_media === 'foto' || $galeri->tipe_media === 'video')
                            <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
                                 src="{{ $galeri->url }}" 
                                 alt="{{ $galeri->judul }}">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center p-6 text-center">
                                <span class="material-symbols-outlined text-6xl mb-4" style="color: var(--t-icon-color);">insert_drive_file</span>
                                <p class="text-sm opacity-70" style="color: var(--t-on-surface);">{{ $galeri->tipe_media }}</p>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-end p-4">
                            <p class="text-sm font-bold text-white">{{ $galeri->judul ?: 'Galeri Sanggar' }}</p>
                            @if($galeri->tipe_media === 'video')
                                <span class="material-symbols-outlined absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white text-4xl opacity-80">play_circle</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <!-- Beautiful visual mockup cards as fallback matching Gambar 2 -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="rounded-xl overflow-hidden shadow-lg aspect-[4/3] relative group" style="background: var(--t-surface-container); border: 1px solid var(--t-section-border);">
                    <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
                         src="https://images.unsplash.com/photo-1547153760-18fc86324498?q=80&w=1000" 
                         alt="Mockup Gallery 1">
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-end p-4">
                        <p class="text-sm font-bold text-white">Tari Guel Tradisional Gayo</p>
                    </div>
                </div>
                <div class="rounded-xl overflow-hidden shadow-lg aspect-[4/3] relative group" style="background: var(--t-surface-container); border: 1px solid var(--t-section-border);">
                    <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
                         src="https://images.unsplash.com/photo-1605722243979-fe0be8158232?q=80&w=1000" 
                         alt="Mockup Gallery 2">
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-end p-4">
                        <p class="text-sm font-bold text-white">Latihan Didong Bersama Maestro</p>
                    </div>
                </div>
                <div class="rounded-xl overflow-hidden shadow-lg aspect-[4/3] relative group" style="background: var(--t-surface-container); border: 1px solid var(--t-section-border);">
                    <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
                         src="https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=1000" 
                         alt="Mockup Gallery 3">
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-end p-4">
                        <p class="text-sm font-bold text-white">Pementasan Seni Budaya Gayo</p>
                    </div>
                </div>
            </div>
        @endif
    </section>
</main>
@endsection
