@extends('layouts.publik')

@section('title', $jadwal->nama_acara . ' | Agenda Budaya')

@push('styles')
<style>
    .kerawang-strip {
        height: 4px;
        background: linear-gradient(90deg, #0D9373 0%, #E8B931 50%, #0D9373 100%);
    }
    .glass-panel {
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        background: var(--t-surface-container);
        border: 1px solid var(--t-outline-variant);
    }
    .hero-mask {
        background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.7) 100%);
    }
</style>
@endpush

@section('content')
<main class="min-h-screen pt-16 pb-20">
    <!-- Hero Banner Section -->
    <section class="relative h-[65vh] w-full overflow-hidden">
        <img class="w-full h-full object-cover grayscale-[0.2] brightness-[0.5] contrast-[1.1]" 
             src="{{ $jadwal->poster_url }}" 
             alt="{{ $jadwal->nama_acara }}">
        
        <div class="absolute inset-0 hero-mask flex flex-col justify-end">
            <div class="max-w-6xl mx-auto px-6 w-full pb-12">
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-white bg-[#0D9373] px-3 py-1 rounded">
                        {{ $jadwal->jenis_kesenian }}
                    </span>
                    <span class="text-[10px] font-bold uppercase tracking-widest {{ $jadwal->status == 'Terjadwal' ? 'text-[#1B3A2D] bg-[#E8B931]' : 'text-white bg-error' }} px-3 py-1 rounded">
                        {{ $jadwal->status }}
                    </span>
                </div>
                <h1 class="font-headline-xl text-4xl md:text-6xl font-extrabold text-white tracking-tight mb-4">
                    {{ $jadwal->nama_acara }}
                </h1>
                <p class="text-white/80 max-w-2xl text-base md:text-lg leading-relaxed font-light">
                    Pertunjukan seni dan budaya khas Gayo. Diselenggarakan di {{ $jadwal->lokasi_acara }}.
                </p>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 kerawang-strip"></div>
    </section>

    <!-- Content Details Section -->
    <section class="max-w-6xl mx-auto px-6 mt-16 grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Event Info (Left side, takes 2 cols) -->
        <div class="lg:col-span-2 space-y-8">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest" style="color:var(--t-primary);">EVENT DETAILS</span>
                <h2 class="text-headline-md text-2xl font-bold mt-2 mb-4" style="color:var(--t-on-surface);">Informasi Pertunjukan</h2>
                <div class="leading-relaxed text-base font-light prose max-w-none" style="color:var(--t-on-surface-variant);">
                    {!! $jadwal->deskripsi ?: 'Mari saksikan pertunjukan seni budaya Gayo yang memukau. Acara ini merupakan upaya pelestarian kebudayaan dan tradisi leluhur Bener Meriah.' !!}
                </div>
            </div>

            <!-- Statistics / Info widgets -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-6 pt-6" style="border-top:1px solid var(--t-outline-variant);">
                <div class="p-4 rounded-xl shadow-sm" style="background:var(--t-surface-container); border:1px solid var(--t-outline-variant);">
                    <span class="material-symbols-outlined text-3xl mb-2" style="color:var(--t-primary);">event</span>
                    <p class="text-[11px] uppercase tracking-wider" style="color:var(--t-on-surface-variant);">Tanggal</p>
                    <p class="text-lg font-bold mt-1" style="color:var(--t-on-surface);">{{ $jadwal->tanggal->translatedFormat('d M Y') }}</p>
                </div>
                <div class="p-4 rounded-xl shadow-sm" style="background:var(--t-surface-container); border:1px solid var(--t-outline-variant);">
                    <span class="material-symbols-outlined text-3xl mb-2" style="color:var(--t-primary);">schedule</span>
                    <p class="text-[11px] uppercase tracking-wider" style="color:var(--t-on-surface-variant);">Waktu</p>
                    <p class="text-lg font-bold mt-1" style="color:var(--t-on-surface);">{{ $jadwal->waktu_mulai ? \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') : 'TBA' }} - {{ $jadwal->waktu_selesai ? \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') : 'Selesai' }}</p>
                </div>
                <div class="p-4 rounded-xl shadow-sm" style="background:var(--t-surface-container); border:1px solid var(--t-outline-variant);">
                    <span class="material-symbols-outlined text-3xl mb-2" style="color:var(--t-primary);">confirmation_number</span>
                    <p class="text-[11px] uppercase tracking-wider" style="color:var(--t-on-surface-variant);">Tiket</p>
                    <p class="text-lg font-bold mt-1" style="color:var(--t-on-surface);">{{ $jadwal->tiket_info ?: 'Gratis / Terbuka untuk Umum' }}</p>
                </div>
            </div>
        </div>

        <!-- Contacts Sidebar (Right side, takes 1 col) -->
        <div class="space-y-6">
            <!-- Event Location Card -->
            <div class="glass-panel p-6 rounded-2xl shadow-xl space-y-6">
                <h3 class="font-bold text-lg pb-3" style="color:var(--t-on-surface); border-bottom:1px solid var(--t-outline-variant);">Lokasi & Penyelenggara</h3>
                
                <div class="space-y-4 text-sm">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined" style="color:var(--t-primary);">location_on</span>
                        <div>
                            <p class="font-semibold" style="color:var(--t-on-surface);">{{ $jadwal->lokasi_acara }}</p>
                            <p class="text-xs mt-1 leading-normal" style="color:var(--t-on-surface-variant);">{{ $jadwal->alamat_acara }}</p>
                        </div>
                    </div>
                    
                    @if($jadwal->sanggar)
                    <div class="flex items-center gap-3 mt-4 pt-4" style="border-top:1px solid var(--t-outline-variant);">
                        <span class="material-symbols-outlined" style="color:var(--t-primary);">groups</span>
                        <div>
                            <p class="font-semibold" style="color:var(--t-on-surface);">Diselenggarakan oleh</p>
                            <a href="{{ route('publik.sanggar.show', $jadwal->sanggar->slug) }}" class="text-xs transition-colors mt-1 inline-block font-bold hover:underline" style="color:var(--t-primary);">{{ $jadwal->sanggar->nama_sanggar }}</a>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="pt-4">
                    <a href="{{ route('publik.peta') }}" 
                       class="w-full text-white font-bold py-3 rounded-xl shadow-lg hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2 text-sm text-center" style="background:var(--t-primary); color:var(--t-on-primary);">
                        <span class="material-symbols-outlined text-sm">explore</span> Tampilkan di Peta
                    </a>
                </div>
            </div>

            <!-- Back button -->
            <a href="{{ route('publik.beranda') }}" class="flex items-center gap-2 transition-colors text-sm font-bold hover:underline" style="color:var(--t-on-surface-variant);">
                <span class="material-symbols-outlined text-sm">arrow_back</span> Kembali ke Beranda
            </a>
        </div>
    </section>
</main>
@endsection
