@extends('layouts.publik')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    .leaflet-div-icon { background: transparent !important; border: none !important; }
    .mini-map-container .leaflet-control-container { display: none; }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<header class="relative h-[85vh] flex items-center justify-center overflow-hidden pt-toolbar-height">
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 z-10" style="background:var(--t-hero-overlay);"></div>
        <img class="w-full h-full object-cover" alt="Tugu Rimba Raya" src="{{ asset('images/tugu_rimba_raya.jpg') }}">
    </div>
    <div class="relative z-20 text-center px-margin-mobile">
        <h1 class="font-display-lg text-3xl md:text-5xl font-bold uppercase drop-shadow-2xl mb-4 tracking-wide" style="color:var(--t-text-hero);">SENI DAN BUDAYA GAYO</h1>
        <p class="font-body-lg text-body-lg max-w-2xl mx-auto mb-10" style="color:var(--t-text-hero-sub);">Tradisi, Seni, dan Budaya Dataran Tinggi Gayo. Menjaga warisan untuk masa depan yang lebih bermakna.</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="javascript:void(0)" onclick="openTentangKamiModal()" class="flex items-center gap-2 text-white px-6 py-2.5 rounded-lg hover:opacity-90 transition-all active:scale-95 text-sm font-semibold shadow-lg" style="background:var(--t-accent);">
                <span class="material-symbols-outlined text-[18px]">info</span>Tentang Kami
            </a>
            <a class="flex items-center gap-2 px-6 py-2.5 rounded-lg transition-all active:scale-95 text-sm font-semibold shadow-lg border" style="background:var(--t-btn-secondary-bg);color:var(--t-btn-secondary-text);border-color:var(--t-btn-secondary-border);" href="#kontak-kami">
                <span class="material-symbols-outlined text-[18px]">support_agent</span>Pusat Layanan
            </a>
        </div>
    </div>
</header>

<!-- Action Grid -->
<section class="relative -mt-24 z-30 px-margin-desktop max-w-screen-2xl mx-auto">
    <div class="grid grid-cols-2 md:grid-cols-5 gap-6 justify-center">
        <a href="{{ route('publik.peta') }}" class="flex flex-col items-center gap-4 group text-center">
            <div class="w-24 h-24 md:w-32 md:h-32 rounded-2xl shadow-xl flex items-center justify-center transition-all duration-500 cursor-pointer" style="background:var(--t-action-grid-bg);border:1px solid var(--t-card-border);">
                <span class="material-symbols-outlined text-4xl" style="color:var(--t-icon-color);font-variation-settings: 'FILL' 1;">palette</span>
            </div>
            <span class="font-label-md text-sm transition-colors" style="color:var(--t-on-surface);">Sanggar Seni</span>
        </a>
        <a href="{{ route('publik.jadwal') }}" class="flex flex-col items-center gap-4 group text-center">
            <div class="w-24 h-24 md:w-32 md:h-32 rounded-2xl shadow-xl flex items-center justify-center transition-all duration-500 cursor-pointer" style="background:var(--t-action-grid-bg);border:1px solid var(--t-card-border);">
                <span class="material-symbols-outlined text-4xl" style="color:var(--t-icon-color);font-variation-settings: 'FILL' 1;">calendar_month</span>
            </div>
            <span class="font-label-md text-sm transition-colors" style="color:var(--t-on-surface);">Kalender Event</span>
        </a>
        <a href="{{ route('publik.peta') }}" class="flex flex-col items-center gap-4 group text-center">
            <div class="w-24 h-24 md:w-32 md:h-32 rounded-2xl shadow-xl flex items-center justify-center transition-all duration-500 cursor-pointer" style="background:var(--t-action-grid-bg);border:1px solid var(--t-card-border);">
                <span class="material-symbols-outlined text-4xl" style="color:var(--t-icon-color);font-variation-settings: 'FILL' 1;">map</span>
            </div>
            <span class="font-label-md text-sm transition-colors" style="color:var(--t-on-surface);">Peta Budaya</span>
        </a>
        <a href="{{ route('publik.arsip') }}" class="flex flex-col items-center gap-4 group text-center">
            <div class="w-24 h-24 md:w-32 md:h-32 rounded-2xl shadow-xl flex items-center justify-center transition-all duration-500 cursor-pointer" style="background:var(--t-action-grid-bg);border:1px solid var(--t-card-border);">
                <span class="material-symbols-outlined text-4xl" style="color:var(--t-icon-color);font-variation-settings: 'FILL' 1;">inventory_2</span>
            </div>
            <span class="font-label-md text-sm transition-colors" style="color:var(--t-on-surface);">Arsip Dokumentasi</span>
        </a>
        <a href="{{ route('publik.peta') }}" class="flex flex-col items-center gap-4 group text-center">
            <div class="w-24 h-24 md:w-32 md:h-32 rounded-2xl shadow-xl flex items-center justify-center transition-all duration-500 cursor-pointer" style="background:var(--t-action-grid-bg);border:1px solid var(--t-card-border);">
                <span class="material-symbols-outlined text-4xl" style="color:var(--t-icon-color);font-variation-settings: 'FILL' 1;">shopping_basket</span>
            </div>
            <span class="font-label-md text-sm transition-colors" style="color:var(--t-on-surface);">Produk Lokal</span>
        </a>
    </div>
</section>

<!-- Latest Updates -->
<section id="berita" class="py-24 px-margin-desktop max-w-screen-2xl mx-auto">
    <div class="flex justify-between items-end mb-12">
        <h2 class="font-headline-lg text-headline-lg" style="color:var(--t-accent);">Berita Terbaru</h2>
    </div>
    
    @if($beritaTerbaru->count() > 0)
    <div class="grid lg:grid-cols-2 gap-12">
        <!-- Featured Article -->
        @php $featured = $beritaTerbaru->first(); @endphp
        <a href="{{ route('publik.berita.show', $featured) }}" class="group relative rounded-xl overflow-hidden glass aspect-video md:aspect-[4/3] lg:aspect-auto lg:h-full min-h-[400px] block">
            <img class="w-full h-full object-cover object-center transition-transform duration-700 group-hover:scale-110" alt="{{ $featured->judul }}" src="{{ $featured->thumbnail_url }}">
            <div class="absolute top-6 left-6 text-white px-4 py-1 rounded font-label-sm uppercase" style="background:var(--t-accent);">{{ $featured->kategori }}</div>
            <div class="absolute bottom-0 left-0 right-0 p-8 bg-gradient-to-t from-black/70 via-black/30 to-transparent">
                <h3 class="text-headline-md font-headline-md text-white mb-4 line-clamp-2">{{ $featured->judul }}</h3>
                <p class="text-white/70 mb-6 line-clamp-2">{{ Str::limit(strip_tags($featured->konten), 150) }}</p>
                <span class="flex items-center gap-2 font-bold group-hover:translate-x-2 transition-transform" style="color:var(--t-accent);">
                    Baca Selengkapnya <span class="material-symbols-outlined">trending_flat</span>
                </span>
            </div>
        </a>
        
        <!-- News List -->
        <div class="flex flex-col gap-8">
            @foreach($beritaTerbaru->skip(1) as $berita)
            <a href="{{ route('publik.berita.show', $berita) }}" class="pb-8 group cursor-pointer block" style="border-bottom:1px solid var(--t-section-border);">
                <div class="px-3 py-0.5 rounded inline-block font-label-sm mb-4 uppercase" style="background:rgba(var(--t-accent-rgb),0.15);color:var(--t-accent);">{{ $berita->kategori }}</div>
                <h4 class="text-headline-md font-headline-md mb-2 transition-colors line-clamp-2" style="color:var(--t-on-surface);">{{ $berita->judul }}</h4>
                <p class="text-body-md mb-4 line-clamp-2" style="color:var(--t-on-surface-variant);">{{ Str::limit(strip_tags($berita->konten), 120) }}</p>
                <span class="flex items-center gap-2 font-label-sm font-bold group-hover:translate-x-1 transition-transform" style="color:var(--t-accent);">Baca Selengkapnya <span class="material-symbols-outlined text-sm">chevron_right</span></span>
            </a>
            @endforeach
        </div>
    </div>
    @else
    <div class="text-center py-12" style="color:var(--t-on-surface-variant);">
        Belum ada berita terbaru.
    </div>
    @endif
</section>

<!-- Agenda Budaya Section -->
<section class="py-24 px-margin-desktop max-w-screen-2xl mx-auto" style="border-top:1px solid var(--t-section-border);">
    <div class="flex justify-between items-end mb-12">
        <div>
            <h2 class="font-headline-lg text-headline-lg" style="color:var(--t-accent);">Agenda Budaya</h2>
            <p class="text-body-md mt-2" style="color:var(--t-on-surface-variant);">Ikuti berbagai perhelatan seni dan budaya yang akan datang di wilayah Gayo.</p>
        </div>
        <div class="flex gap-2">
            <button class="w-10 h-10 rounded-full flex items-center justify-center transition-colors" style="border:1px solid rgba(var(--t-accent-rgb),0.15);">
                <span class="material-symbols-outlined" style="color:var(--t-on-surface);">chevron_left</span>
            </button>
            <button class="w-10 h-10 rounded-full flex items-center justify-center transition-colors" style="border:1px solid rgba(var(--t-accent-rgb),0.15);">
                <span class="material-symbols-outlined" style="color:var(--t-on-surface);">chevron_right</span>
            </button>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($agendaBudaya as $agenda)
        <a href="{{ route('publik.jadwal.show', $agenda) }}" class="glass rounded-xl overflow-hidden group cursor-pointer hover:shadow-lg transition-all duration-300 flex flex-col h-full block">
            <div class="relative aspect-video overflow-hidden" style="background:var(--t-surface-high);">
                <img src="{{ $agenda->poster_url }}" class="w-full h-full object-cover object-center group-hover:scale-110 transition-transform duration-700" alt="{{ $agenda->nama_acara }}">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-80"></div>
                <div class="absolute bottom-4 left-4 z-10 text-white text-xs font-bold px-2 py-1 rounded shadow-lg" style="background:var(--t-accent);">{{ strtoupper($agenda->tanggal->translatedFormat('d F Y')) }}</div>
            </div>
            <div class="p-6 flex flex-col flex-grow">
                <h3 class="text-xl font-bold mb-2 transition-colors line-clamp-1" style="color:var(--t-on-surface);">{{ $agenda->nama_acara }}</h3>
                <p class="text-sm mb-6 line-clamp-2 flex-grow" style="color:var(--t-on-surface-variant);">{{ $agenda->deskripsi }}</p>
                <span class="inline-flex items-center gap-2 text-sm font-bold w-max group-hover:translate-x-1 transition-transform" style="color:var(--t-accent);">
                    Selengkapnya <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </span>
            </div>
        </a>
        @empty
        <div class="col-span-1 md:col-span-3 text-center py-10" style="color:var(--t-on-surface-variant);">
            Belum ada agenda budaya mendatang.
        </div>
        @endforelse
    </div>
    
    <div class="text-center mt-12">
        <a href="{{ route('publik.jadwal') }}" class="inline-flex items-center gap-2 px-8 py-3 rounded-xl transition-colors text-sm font-bold" style="border:1px solid rgba(var(--t-accent-rgb),0.2);color:var(--t-on-surface);">
            LIHAT SEMUA EVENT
        </a>
    </div>
</section>

<!-- Arsip Dokumentasi Seni -->
<section class="py-24 px-margin-desktop max-w-screen-2xl mx-auto" style="border-top:1px solid var(--t-section-border);">
    <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
        <div>
            <h2 class="font-headline-lg text-headline-lg" style="color:var(--t-accent);">Arsip Dokumentasi</h2>
            <p class="text-body-md mt-2" style="color:var(--t-on-surface-variant);">Jejak visual pelestarian seni dan budaya Gayo dari masa ke masa.</p>
        </div>
        <a href="{{ route('publik.arsip') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl transition-colors text-sm font-bold" style="border:1px solid rgba(var(--t-accent-rgb),0.2);color:var(--t-on-surface);">
            LIHAT SEMUA ARSIP
        </a>
    </div>

    @if($dokumentasiTerbaru->count() > 0)
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach($dokumentasiTerbaru as $dok)
        <a href="{{ route('publik.arsip.show', $dok) }}" class="group relative aspect-square rounded-xl overflow-hidden block transition-all" style="background:var(--t-surface-high);border:1px solid var(--t-card-border);">
            @if($dok->thumbnail_url || $dok->file_url)
                <img src="{{ $dok->thumbnail_url ?? $dok->file_url }}" alt="{{ $dok->judul }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
            @else
                <div class="w-full h-full flex items-center justify-center" style="background:var(--t-surface-high);color:var(--t-on-surface-variant);">
                    <span class="material-symbols-outlined text-4xl">{{ $dok->icon }}</span>
                </div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-80 group-hover:opacity-100 transition-opacity"></div>
            
            <div class="absolute top-3 left-3 bg-black/60 backdrop-blur-md px-2.5 py-1 rounded-md flex items-center gap-1.5" style="border:1px solid rgba(var(--t-accent-rgb),0.1);">
                <span class="material-symbols-outlined text-[14px]" style="color:var(--t-accent);font-variation-settings: 'FILL' 1;">{{ $dok->icon }}</span>
                <span class="text-[10px] font-bold text-white uppercase">{{ $dok->tipe }}</span>
            </div>
            
            <div class="absolute bottom-0 left-0 right-0 p-4">
                <h3 class="text-white font-bold text-sm line-clamp-2 mb-1 transition-colors">{{ $dok->judul }}</h3>
                <p class="text-white/60 text-[10px]">{{ $dok->tanggal_dokumen->translatedFormat('d M Y') }}</p>
            </div>
        </a>
        @endforeach
    </div>
    @else
    <div class="text-center py-10 rounded-xl" style="color:var(--t-on-surface-variant);background:var(--t-surface-high);border:1px solid var(--t-card-border);">
        <span class="material-symbols-outlined text-4xl mb-3 block">inventory_2</span>
        Belum ada dokumentasi seni yang diunggah.
    </div>
    @endif
</section>

<!-- Territory Section -->
<section class="relative py-24 overflow-hidden">
    <div class="absolute inset-0 kerawang-pattern z-0"></div>
    <div class="px-margin-desktop max-w-screen-2xl mx-auto flex flex-col lg:flex-row items-center gap-16 relative z-10">
        <div class="lg:w-1/2">
            <h2 class="font-headline-lg text-headline-lg mb-6" style="color:var(--t-accent);">Jelajahi Wilayah Gayo</h2>
            <div class="space-y-6 text-body-md" style="color:var(--t-on-surface-variant);">
                <p>Kabupaten Bener Meriah merupakan wilayah yang kaya akan situs budaya dan sejarah seni. Di kawasan dataran tinggi ini, setiap jengkal tanah menyimpan cerita peradaban Gayo yang agung.</p>
                <p>Melalui peta interaktif ini, Anda dapat menelusuri lokasi sanggar seni, toko alat musik, pengrajin kerawang, dan berbagai pusat kesenian tradisional yang tersebar di Bener Meriah.</p>
            </div>
            <div class="mt-10 flex flex-wrap gap-4">
                <div class="flex items-center gap-3 p-4 rounded-xl" style="background:var(--t-surface-high);border:1px solid var(--t-card-border);">
                    <div class="w-2 h-2 rounded-full" style="background:var(--t-accent);"></div>
                    <span class="font-mono-data text-xs" style="color:var(--t-on-surface);">Bener Meriah</span>
                </div>
            </div>
            <a href="{{ route('publik.peta') }}" class="inline-flex mt-12 text-white px-10 py-4 rounded-full font-label-md hover:opacity-90 transition-all items-center gap-3 group" style="background:var(--t-accent);">
                LIHAT PETA INTERAKTIF <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">explore</span>
            </a>
        </div>
        <div class="lg:w-1/2 relative hidden md:block">
            <div class="relative w-full aspect-square max-w-md mx-auto rounded-3xl overflow-hidden shadow-2xl z-20 mini-map-container" style="border:1px solid var(--t-card-border);">
                <div id="miniMap" class="absolute inset-0 z-0" style="background:var(--t-surface-high);"></div>
                <a href="{{ route('publik.peta') }}" class="absolute inset-0 z-10 flex flex-col items-center justify-center bg-black/40 opacity-0 hover:opacity-100 transition-opacity duration-300">
                    <div class="text-white px-6 py-3 rounded-full font-bold flex items-center gap-2 transform translate-y-4 hover:translate-y-0 transition-transform duration-300" style="background:var(--t-accent);">
                        Buka Peta Interaktif <span class="material-symbols-outlined">explore</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Modal Tentang Kami -->
<div id="tentangKamiModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-6 opacity-0 transition-opacity duration-300">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeTentangKamiModal()"></div>
    <div class="relative rounded-2xl w-full max-w-2xl shadow-2xl overflow-hidden transform scale-95 transition-transform duration-300" id="modalContent" style="background:var(--t-surface-lowest);border:1px solid var(--t-card-border);">
        <!-- Close button -->
        <button onclick="closeTentangKamiModal()" class="absolute top-4 right-4 text-on-surface-variant hover:text-white bg-white/5 hover:bg-[#0D9373]/5 rounded-full w-8 h-8 flex items-center justify-center transition-colors z-10">
            <span class="material-symbols-outlined text-sm">close</span>
        </button>
        
        <!-- Header Image -->
        <div class="h-48 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-t from-surface-container-lowest to-transparent z-10"></div>
            <img class="w-full h-full object-cover" src="{{ asset('images/tugu_rimba_raya.jpg') }}" alt="Gayo Heritage">
            <div class="absolute bottom-4 left-6 z-20">
                <div class="text-white text-xs font-bold px-2 py-1 rounded inline-block mb-2" style="background:var(--t-accent);">FILOSOFI KAMI</div>
                <h3 class="text-2xl md:text-3xl font-display-lg font-bold text-white drop-shadow-md">Menjaga Nyala Tradisi</h3>
            </div>
        </div>
        
        <!-- Content -->
        <div class="p-6 md:p-8 space-y-4 text-body-lg leading-relaxed" style="color:var(--t-on-surface-variant);">
            <p><strong style="color:var(--t-on-surface);">Seni dan Budaya Gayo (Gayo Heritage)</strong> hadir sebagai jembatan penghubung antara masa lalu yang luhur dan masa depan yang terus berkembang.</p>
            
            <p>Kami percaya bahwa seni dan budaya bukanlah sekadar peninggalan sejarah yang usang, melainkan sebuah <span style="color:var(--t-accent);">identitas yang hidup</span> dan bernapas di setiap denyut nadi masyarakat Dataran Tinggi Gayo.</p>
            
            <p>Beranjak dari filosofi tersebut, platform ini dibangun dengan komitmen teguh untuk melestarikan, mendokumentasikan, dan mempromosikan kearifan lokal. Mulai dari gemulai penuh makna <strong style="color:var(--t-on-surface);">Tari Guel</strong>, harmonisasi magis <strong style="color:var(--t-on-surface);">Didong</strong>, ketelitian dalam rincian <strong style="color:var(--t-on-surface);">Kerawang Gayo</strong>, hingga kekayaan alat musik tradisional.</p>
            
            <p class="italic pl-4 my-6" style="color:var(--t-on-surface);border-left:4px solid var(--t-accent);">"Ini adalah dedikasi kami agar warisan leluhur Bener Meriah tidak lekang oleh waktu, senantiasa menjadi kebanggaan, dan terus menginspirasi peradaban dari generasi ke generasi."</p>
        </div>
    </div>
</div>

<!-- Saran dan Ulasan Section -->
<section id="testimoni" class="py-24 px-margin-desktop max-w-screen-2xl mx-auto" style="border-top:1px solid var(--t-section-border);">
    <div class="flex flex-col lg:flex-row gap-12">
        
        <div class="lg:w-1/2">
            <h2 class="font-headline-lg text-headline-lg mb-4" style="color:var(--t-accent);">Suara Pengunjung</h2>
            <p class="text-body-md mb-8" style="color:var(--t-on-surface-variant);">Apa kata mereka tentang pengalaman menjelajahi seni dan budaya Dataran Tinggi Gayo.</p>
            
            @if(isset($testimonis) && $testimonis->count() > 0)
            <div class="grid gap-6">
                @foreach($testimonis as $testi)
                <div class="glass p-6 rounded-xl relative overflow-hidden group transition-all duration-300">
                    <div class="flex items-center gap-2 mb-3">
                        @for($i=1; $i<=5; $i++)
                            <span class="material-symbols-outlined text-[18px] {{ $i <= $testi->rating ? 'text-[#E8B931]' : '' }}" style="font-variation-settings: 'FILL' 1;{{ $i > $testi->rating ? 'color:var(--t-surface-high)' : '' }}">star</span>
                        @endfor
                    </div>
                    <p class="italic mb-4 text-sm leading-relaxed" style="color:var(--t-on-surface);">"{{ $testi->pesan }}"</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-lg shadow-inner" style="background:var(--t-surface-highest);color:var(--t-accent);border:1px solid var(--t-card-border);">
                            {{ strtoupper(substr($testi->nama, 0, 1)) }}
                        </div>
                        <div>
                            <h6 class="font-bold text-sm" style="color:var(--t-on-surface);">{{ $testi->nama }}</h6>
                            <p class="text-[10px] uppercase tracking-wider" style="color:var(--t-on-surface-variant);">{{ $testi->asal ?? 'Pengunjung' }}</p>
                        </div>
                        <span class="ml-auto text-[10px]" style="color:var(--t-on-surface-variant);opacity:0.5;">{{ $testi->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="glass p-10 rounded-xl border border-[#0D9373]/10 text-center flex flex-col items-center justify-center h-full min-h-[300px]">
                <span class="material-symbols-outlined text-5xl text-on-surface-variant/50 mb-4">forum</span>
                <p class="text-on-surface-variant text-sm">Belum ada ulasan yang disetujui.<br>Jadilah yang pertama memberikan ulasan!</p>
            </div>
            @endif
        </div>
        
        <!-- Right: Form Testimoni -->
        <div class="lg:w-1/2">
            <div class="glass p-8 rounded-2xl h-full flex flex-col justify-center relative overflow-hidden shadow-2xl">
                <div class="absolute top-0 right-0 w-64 h-64 rounded-full blur-3xl" style="background:rgba(var(--t-accent-rgb),0.05);"></div>
                
                <h3 class="font-headline-md text-2xl font-bold mb-2 relative z-10" style="color:var(--t-on-surface);">Bagikan Pengalaman Anda</h3>
                <p class="text-sm mb-8 relative z-10" style="color:var(--t-on-surface-variant);">Ulasan Anda akan ditampilkan setelah disetujui oleh admin.</p>
                
                @if(session('testimoni_sukses'))
                <div class="px-4 py-3 rounded-lg mb-6 flex items-start gap-3 relative z-10 text-sm" style="background:rgba(var(--t-accent-rgb),0.1);border:1px solid rgba(var(--t-accent-rgb),0.3);color:var(--t-accent);">
                    <span class="material-symbols-outlined text-[18px] mt-0.5" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    <div>{{ session('testimoni_sukses') }}</div>
                </div>
                @endif
                
                <form action="{{ route('publik.testimoni.store') }}" method="POST" class="relative z-10">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider mb-2" style="color:var(--t-on-surface-variant);">Nama <span class="text-error">*</span></label>
                            <input type="text" name="nama" required class="w-full rounded-lg px-4 py-3 text-sm transition-colors" style="background:var(--t-surface-highest);border:1px solid var(--t-card-border);color:var(--t-on-surface);" placeholder="Nama Anda" value="{{ old('nama') }}">
                            @error('nama') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider mb-2" style="color:var(--t-on-surface-variant);">Asal Daerah</label>
                            <input type="text" name="asal" class="w-full rounded-lg px-4 py-3 text-sm transition-colors" style="background:var(--t-surface-highest);border:1px solid var(--t-card-border);color:var(--t-on-surface);" placeholder="Kota/Kabupaten" value="{{ old('asal') }}">
                            @error('asal') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-2">Rating <span class="text-error">*</span></label>
                        <div class="flex flex-row-reverse justify-end gap-2 star-rating">
                            <input type="radio" id="star5" name="rating" value="5" class="hidden" {{ old('rating', '5') == '5' ? 'checked' : '' }}/>
                            <label for="star5" class="cursor-pointer material-symbols-outlined text-2xl text-surface-variant hover:text-[#E8B931] transition-colors" style="font-variation-settings: 'FILL' 1;">star</label>
                            
                            <input type="radio" id="star4" name="rating" value="4" class="hidden" {{ old('rating') == '4' ? 'checked' : '' }}/>
                            <label for="star4" class="cursor-pointer material-symbols-outlined text-2xl text-surface-variant hover:text-[#E8B931] transition-colors" style="font-variation-settings: 'FILL' 1;">star</label>
                            
                            <input type="radio" id="star3" name="rating" value="3" class="hidden" {{ old('rating') == '3' ? 'checked' : '' }}/>
                            <label for="star3" class="cursor-pointer material-symbols-outlined text-2xl text-surface-variant hover:text-[#E8B931] transition-colors" style="font-variation-settings: 'FILL' 1;">star</label>
                            
                            <input type="radio" id="star2" name="rating" value="2" class="hidden" {{ old('rating') == '2' ? 'checked' : '' }}/>
                            <label for="star2" class="cursor-pointer material-symbols-outlined text-2xl text-surface-variant hover:text-[#E8B931] transition-colors" style="font-variation-settings: 'FILL' 1;">star</label>
                            
                            <input type="radio" id="star1" name="rating" value="1" class="hidden" {{ old('rating') == '1' ? 'checked' : '' }}/>
                            <label for="star1" class="cursor-pointer material-symbols-outlined text-2xl text-surface-variant hover:text-[#E8B931] transition-colors" style="font-variation-settings: 'FILL' 1;">star</label>
                        </div>
                        <style>
                            .star-rating input:checked ~ label { color: #E8B931; }
                            .star-rating label:hover, .star-rating label:hover ~ label { color: #0D9373 !important; }
                        </style>
                        @error('rating') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-[10px] font-bold uppercase tracking-wider mb-2" style="color:var(--t-on-surface-variant);">Ulasan / Pesan <span class="text-error">*</span></label>
                        <textarea name="pesan" rows="4" required class="w-full rounded-lg px-4 py-3 text-sm resize-none transition-colors" style="background:var(--t-surface-highest);border:1px solid var(--t-card-border);color:var(--t-on-surface);" placeholder="Ceritakan pengalaman Anda mengunjungi website atau budaya Gayo...">{{ old('pesan') }}</textarea>
                        @error('pesan') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <button type="submit" class="w-full text-white font-bold px-6 py-3.5 rounded-lg transition-all active:scale-95 shadow-lg" style="background:var(--t-accent);">
                        Kirim Ulasan
                    </button>
                </form>
            </div>
        </div>
        
    </div>
</section>

<!-- Footer -->
<footer id="kontak-kami" class="pt-16 pb-8 mt-12" style="background:var(--t-footer-bg);border-top:1px solid var(--t-section-border);">
    <div class="px-margin-desktop max-w-screen-2xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 mb-16">
            <div class="lg:col-span-4">
                <div class="text-headline-md font-headline-md font-bold mb-4" style="color:var(--t-accent);">SENI DAN BUDAYA</div>
                <p class="text-body-md" style="color:var(--t-on-surface-variant);">Sistem Informasi Geografis pemetaan Sanggar Seni dan Toko Kesenian tradisional di wilayah Bener Meriah.</p>
            </div>
            
            <div class="lg:col-span-4">
                <h5 class="font-bold mb-6 uppercase" style="color:var(--t-accent);">PORTAL & APLIKASI</h5>
                <div class="flex flex-col gap-4">
                    <div class="rounded-xl p-6 relative overflow-hidden group transition-all duration-300 shadow-xl" style="background:var(--t-footer-card-bg);border:1px solid var(--t-card-border);">
                        <div class="absolute top-0 right-0 w-32 h-32 rounded-full blur-2xl transition-all" style="background:rgba(var(--t-accent-rgb),0.1);"></div>
                        <div class="flex items-start gap-4 relative z-10 mb-6">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0" style="background:var(--t-surface-highest);color:var(--t-accent);">
                                <span class="material-symbols-outlined text-2xl">admin_panel_settings</span>
                            </div>                                                                                                          
                            <div>
                                <h6 class="font-bold mb-1" style="color:var(--t-on-surface);">Manajemen Sistem</h6>
                                <p class="text-xs leading-relaxed" style="color:var(--t-on-surface-variant);">Jika ingin mempunyai konten seni di dalam Website ini?.</p>
                            </div>
                        </div>
                        <a href="{{ route('login') }}" class="w-full flex justify-center items-center gap-2 text-white px-4 py-3 rounded-lg font-label-md transition-all active:scale-95 relative z-10" style="background:var(--t-accent);">
                            <span class="material-symbols-outlined text-[18px]">login</span> Masuk Panel Admin
                        </a>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <h5 class="font-bold mb-6" style="color:var(--t-accent);">TAUTAN CEPAT</h5>
                <ul class="space-y-3 text-label-md" style="color:var(--t-on-surface-variant);">
                    <li><a href="{{ route('publik.peta') }}">Peta Interaktif</a></li>
                    <li><a href="{{ route('publik.jadwal') }}">Jadwal Pertunjukan</a></li>
                    <li><a href="{{ route('login') }}">Login Admin</a></li>
                </ul>
            </div>
            
            <div class="lg:col-span-2 space-y-6">
                <h5 class="font-bold mb-2" style="color:var(--t-accent);">KONTAK KAMI</h5>
                <ul class="space-y-3 text-label-md" style="color:var(--t-on-surface-variant);">
                    <li class="flex items-start gap-2"><span class="material-symbols-outlined text-sm mt-0.5">location_on</span> <span>Bener Meriah</span></li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-sm">mail</span> info@gayoheritage.test</li>
                </ul>
            </div>


        </div>
        <div class="flex flex-col md:flex-row justify-between items-center pt-8 gap-4" style="border-top:1px solid var(--t-section-border);">
            <p class="text-label-sm" style="color:var(--t-on-surface-variant);">© {{ date('Y') }} SENI DAN BUDAYA. All rights reserved.</p>
        </div>
    </div>
</footer>
<!-- Interactive Chatbot Widget -->
<div class="fixed bottom-6 right-6 z-50 flex flex-col items-end">
    <div id="chatWindow" class="hidden w-80 sm:w-96 rounded-2xl shadow-2xl overflow-hidden mb-4 transition-all duration-300 origin-bottom-right scale-95 opacity-0" style="background:var(--t-surface-lowest);border:1px solid rgba(var(--t-accent-rgb),0.15);">
        <div class="p-4 flex justify-between items-center text-white" style="background:var(--t-accent);">
            <div class="flex items-center gap-3">
                <div class="relative bg-white/20 p-2 rounded-full backdrop-blur-sm">
                    <span class="material-symbols-outlined text-lg">support_agent</span>
                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-[#E8B931] rounded-full border border-white"></span>
                </div>
                <div>
                    <h3 class="font-bold text-sm leading-tight">Master Admin</h3>
                    <p class="text-[10px] text-white/70">Online, siap membantu</p>
                </div>
            </div>
            <button onclick="toggleChat()" class="text-white/80 hover:text-white transition-colors">
                <span class="material-symbols-outlined text-sm">close</span>
            </button>
        </div>
        
        <div id="chatBody" class="h-80 p-4 overflow-y-auto flex flex-col gap-3" style="background:var(--t-surface);">
            <div class="flex flex-col items-center justify-center h-full" style="color:var(--t-on-surface-variant);opacity:0.5;" id="chatEmptyState">
                <span class="material-symbols-outlined text-3xl mb-2">forum</span>
                <p class="text-xs">Kirim pesan untuk memulai obrolan</p>
            </div>
        </div>

        <form id="chatForm" class="p-3 flex gap-2" style="background:var(--t-surface-lowest);border-top:1px solid var(--t-card-border);" onsubmit="sendChatMessage(event)">
            <input type="text" id="chatMessage" class="flex-1 border-none rounded-full px-4 py-2 text-sm focus:ring-1" style="background:var(--t-surface-container);color:var(--t-on-surface);" placeholder="Ketik pesan..." required autocomplete="off">
            <button type="submit" class="text-white w-10 h-10 rounded-full flex items-center justify-center transition-colors disabled:opacity-50" id="btnSendChat" style="background:var(--t-accent);">
                <span class="material-symbols-outlined text-[18px]">send</span>
            </button>
        </form>
    </div>

    <button onclick="toggleChat()" id="chatButton" class="flex items-center gap-3 text-white px-5 py-3 rounded-full transition-all duration-300 hover:-translate-y-1 active:scale-95 group relative" style="background:var(--t-accent);box-shadow:0 10px 30px rgba(var(--t-accent-rgb),0.3);">
        <div class="relative flex items-center justify-center bg-white/20 p-2 rounded-full backdrop-blur-sm">
            <span class="material-symbols-outlined text-xl group-hover:animate-pulse">chat</span>
            <span class="absolute -top-1 -right-1 flex h-3 w-3" id="chatNotificationBadge">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-[#E8B931] border border-white"></span>
            </span>
        </div>
        <div class="flex flex-col pr-2 text-left">
            <span class="text-[10px] font-bold uppercase tracking-widest text-white/70">Tanya Sesuatu?</span>
            <span class="text-sm font-bold leading-tight">Chat Admin</span>
        </div>
    </button>
</div>

@endsection

@push('scripts')
<script>
    // Subtle mouse tracking interaction for glass cards

    document.querySelectorAll('.glass').forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            card.style.setProperty('--mouse-x', `${x}px`);
            card.style.setProperty('--mouse-y', `${y}px`);
        });
    });

    // Modal Tentang Kami Logic
    function openTentangKamiModal() {
        const modal = document.getElementById('tentangKamiModal');
        const modalContent = document.getElementById('modalContent');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // setTimeout for animation to trigger after display flex is applied
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
        }, 10);
        document.body.style.overflow = 'hidden'; // prevent scrolling
    }
    
    function closeTentangKamiModal() {
        const modal = document.getElementById('tentangKamiModal');
        const modalContent = document.getElementById('modalContent');
        modal.classList.add('opacity-0');
        modalContent.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300); // wait for animation transition
        document.body.style.overflow = '';
    }

    // Chat Logic
    let chatInterval = null;
    let isChatOpen = false;
    let lastMessageCount = 0;

    function toggleChat() {
        const chatWindow = document.getElementById('chatWindow');
        const chatNotificationBadge = document.getElementById('chatNotificationBadge');
        isChatOpen = !isChatOpen;
        
        if (isChatOpen) {
            chatWindow.classList.remove('hidden');
            setTimeout(() => {
                chatWindow.classList.remove('opacity-0', 'scale-95');
            }, 10);
            chatNotificationBadge.classList.add('hidden');
            loadMessages();
            chatInterval = setInterval(loadMessages, 3000); // Poll every 3 seconds
        } else {
            chatWindow.classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                chatWindow.classList.add('hidden');
            }, 300);
            clearInterval(chatInterval);
        }
    }

    async function loadMessages() {
        try {
            const response = await fetch('{{ route("publik.chat.messages") }}');
            if (!response.ok) return;
            const messages = await response.json();
            
            if (messages.length > 0) {
                document.getElementById('chatEmptyState')?.remove();
            }

            // Only update DOM if new messages arrived
            if (messages.length !== lastMessageCount) {
                const chatBody = document.getElementById('chatBody');
                chatBody.innerHTML = '';
                
                messages.forEach(msg => {
                    const isVisitor = !msg.is_admin;
                    const alignment = isVisitor ? 'self-end bg-primary-container text-white' : 'self-start bg-surface-container-high text-on-surface';
                    const borderRadius = isVisitor ? 'rounded-2xl rounded-tr-sm' : 'rounded-2xl rounded-tl-sm';
                    const time = new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                    
                    const messageHtml = `
                        <div class="max-w-[80%] ${alignment} ${borderRadius} px-4 py-2 text-sm shadow-sm relative group">
                            <p>${msg.message.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')}</p>
                            <span class="text-[9px] opacity-70 mt-1 block text-right">${time}</span>
                        </div>
                    `;
                    chatBody.insertAdjacentHTML('beforeend', messageHtml);
                });
                
                chatBody.scrollTop = chatBody.scrollHeight;
                lastMessageCount = messages.length;
                
                // Show notification if chat is closed and new message arrived from admin
                if (!isChatOpen && messages[messages.length - 1].is_admin) {
                    document.getElementById('chatNotificationBadge').classList.remove('hidden');
                }
            }
        } catch (error) {
            console.error('Failed to load messages', error);
        }
    }

    async function sendChatMessage(e) {
        e.preventDefault();
        const input = document.getElementById('chatMessage');
        const message = input.value.trim();
        if (!message) return;
        
        const btnSend = document.getElementById('btnSendChat');
        input.disabled = true;
        btnSend.disabled = true;

        try {
            const response = await fetch('{{ route("publik.chat.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ message: message })
            });

            if (response.ok) {
                input.value = '';
                loadMessages();
            }
        } catch (error) {
            console.error('Error sending message', error);
        } finally {
            input.disabled = false;
            btnSend.disabled = false;
            input.focus();
        }
    }
</script>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener("DOMContentLoaded", async function() {
        if (!document.getElementById('miniMap')) return;
        
        const miniMap = L.map('miniMap', {
            center: [4.62, 96.84],
            zoom: 10,
            zoomControl: false,
            dragging: false,
            scrollWheelZoom: false,
            doubleClickZoom: false,
            touchZoom: false,
            boxZoom: false,
            keyboard: false
        });

        L.tileLayer('https://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
        }).addTo(miniMap);

        try {
            const response = await fetch('{{ url("/api/peta/markers") }}');
            const data = await response.json();
            
            if (data.success && data.data) {
                const markersData = data.data;
                markersData.forEach(item => {
                    const lat = parseFloat(item.lat);
                    const lng = parseFloat(item.lng);
                    if (isNaN(lat) || isNaN(lng)) return;

                    const isSanggar = item.tipe === 'sanggar';
                    const markerColor = isSanggar ? '#0D9373' : '#E8B931';
                    const iconColor = isSanggar ? 'white' : '#1B3A2D';
                    const iconName = isSanggar ? 'theater_comedy' : 'storefront';
                    
                    const customIcon = L.divIcon({
                        className: 'custom-leaflet-marker',
                        html: `<div style="display:flex; flex-direction:column; align-items:center; width: 40px; margin-left: -20px; margin-top: -20px;">
                                <div style="background:${markerColor}; color:${iconColor}; width:20px; height:20px; border-radius:50%; display:flex; align-items:center; justify-content:center; border: 1px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.4);">
                                    <span class="material-symbols-outlined" style="font-size:12px;">${iconName}</span>
                                </div>
                            </div>`,
                        iconSize: [0, 0],
                        iconAnchor: [0, 0],
                    });

                    L.marker([lat, lng], { icon: customIcon }).addTo(miniMap);
                });
            }
        } catch (e) {
            console.error('Failed to load markers for minimap', e);
        }
    });
</script>
@endpush
