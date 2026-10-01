@extends('layouts.publik')

@section('title', 'Kalender Budaya Gayo | Gayo Heritage Archive')

@push('styles')
<style>
    .hover-border-accent:hover { border-color: var(--t-accent) !important; }
</style>
@endpush

@section('content')
<main class="pt-toolbar-height min-h-screen">
<!-- Hero Section -->
<section class="relative h-[400px] flex items-center justify-center overflow-hidden">
<div class="absolute inset-0 z-0">
<div class="absolute inset-0 z-10" style="background: linear-gradient(to bottom, transparent 0%, var(--t-bg) 75%, var(--t-bg) 100%);"></div>
<img alt="Gayo Highlands" class="w-full h-full object-cover grayscale-[30%]" data-alt="A breathtaking view of the Gayo highlands at dusk, with rolling green mountains shrouded in misty clouds. The lighting is moody and cinematic, with deep shadows and golden highlights reflecting the heritage theme. The composition is expansive and atmospheric, capturing the pristine beauty of Central Aceh." src="https://lh3.googleusercontent.com/aida-public/AB6AXuChBkfFjAxZMvPRZYrQDXGdyUKxC_A2Lb5mT_d-yUpOlD4UImdiwvFk7WEACv6Oeez5vNZ-30O229g0jnG87dk30zG94wuRRgNmNo8iQNKLWS4IzOWC14BH2yfwDRBO3jlMyxZYtO5pIzRRL7ZeWaU0-F4Pngv4sIbZP0ORhpQ6Oa2I7BdvZe9GXvtfpqibMbVHNJ6anSWtwgIv_gWvKWioVzFcVshUMvmxUY7Ic53wWQsxPbDPjNVeDp-VYIyXLCBEQPRUsOdqKhBM">
</div>
<div class="relative z-20 text-center px-margin-mobile">
<h1 class="font-display-lg text-display-lg mb-4" style="color: var(--t-accent);">AGENDA SENI GAYO</h1>
<p class="font-body-lg text-body-lg max-w-2xl mx-auto" style="color: var(--t-on-surface-variant);">
                    Irama tradisi bergema melintasi dataran tinggi. Jelajahi linimasa pertunjukan ritual, perayaan suku, dan lokakarya seni yang telah dikurasi.
                </p>
<div class="mt-8 flex justify-center gap-4">
<div class="flex items-center gap-2 px-4 py-2 glass rounded-full">
<span class="material-symbols-outlined" style="color: var(--t-accent);">location_on</span>
<span class="font-label-md text-label-md" style="color: var(--t-on-surface);">Bener Meriah</span>
</div>
<a href="#kalender-nasional" class="flex items-center gap-2 px-4 py-2 glass rounded-full hover-border-accent transition-colors cursor-pointer">
<span class="material-symbols-outlined" style="color: var(--t-accent);">calendar_month</span>
<span class="font-label-md text-label-md" style="color: var(--t-on-surface);">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</span>
</a>
</div>
</div>
</section>
<!-- Main Content Grid -->
<section class="px-margin-desktop py-12 max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8">
<!-- Calendar Centerpiece -->
<div class="lg:col-span-12 space-y-8">

<!-- Number Calendar -->
<div id="kalender-nasional" class="w-full overflow-x-auto pb-4 scroll-mt-24 mb-10" style="scrollbar-width: thin; scrollbar-color: var(--t-outline) transparent;">
    @php
        $now = \Carbon\Carbon::now();
        $daysInMonth = $now->daysInMonth;
        $currentDay = $now->day;
    @endphp
    <div class="flex gap-3 w-max px-1 py-2">
        @for ($i = 1; $i <= $daysInMonth; $i++)
            @php
                $isToday = $i == $currentDay;
                $date = $now->copy()->startOfMonth()->addDays($i - 1);
                $dayName = $date->translatedFormat('D');
                $hasJadwal = isset($semuaJadwal) && $semuaJadwal->contains(function ($j) use ($date) {
                    return $j->tanggal->isSameDay($date);
                });
            @endphp
            <div class="relative flex flex-col items-center justify-center w-16 h-20 rounded-xl transition-all cursor-pointer hover:-translate-y-1 {{ $isToday ? '' : 'glass hover-border-accent' }}" 
                 style="border: 1px solid {{ $isToday ? 'var(--t-accent)' : 'rgba(255,255,255,0.1)' }}; {{ $isToday ? 'background: rgba(var(--t-accent-rgb), 0.1); box-shadow: 0 4px 12px rgba(var(--t-accent-rgb), 0.15);' : '' }}">
                <span class="text-[11px] font-mono-data uppercase mb-1" style="color: {{ $isToday ? 'var(--t-accent)' : 'var(--t-on-surface-variant)' }};">{{ $dayName }}</span>
                <span class="font-headline-md font-bold text-xl leading-none" style="color: {{ $isToday ? 'var(--t-accent)' : 'var(--t-on-surface)' }};">{{ $i }}</span>
                @if($hasJadwal)
                    <div class="absolute bottom-1.5 w-1.5 h-1.5 rounded-full shadow-sm" style="background-color: var(--t-accent); box-shadow: 0 0 4px var(--t-accent);"></div>
                @endif
            </div>
        @endfor
    </div>
</div>

<!-- Upcoming Events Cards -->
<div class="space-y-6">
<h3 class="font-headline-md text-headline-md flex items-center gap-3" style="color: var(--t-on-surface);">
                        Pertunjukan Mendatang
                        <span class="flex-grow h-px bg-outline-variant/30"></span>
</h3>
<div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
@forelse ($jadwalMendatang as $jadwal)
<a href="{{ route('publik.jadwal.show', $jadwal) }}" class="glass p-5 rounded-xl flex gap-6 hover-border-accent transition-all cursor-pointer group text-left">
<div class="w-32 h-32 rounded-lg overflow-hidden flex-shrink-0">
<img alt="{{ $jadwal->nama_acara }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" src="{{ $jadwal->poster_url }}">
</div>
<div class="flex flex-col justify-between w-full">
<div>
<div class="flex items-center gap-2 mb-2">
<span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded" style="background: rgba(var(--t-accent-rgb), 0.1); color: var(--t-accent);">{{ $jadwal->jenis_kesenian }}</span>
<span class="font-mono-data text-[10px] text-outline">{{ $jadwal->tanggal->translatedFormat('d F Y') }}</span>
</div>
<h4 class="font-headline-md text-body-lg font-bold mb-1" style="color: var(--t-on-surface);">{{ $jadwal->nama_acara }}</h4>
<p class="font-body-md text-label-sm line-clamp-2" style="color: var(--t-on-surface-variant);">{{ $jadwal->deskripsi }}</p>
</div>
<div class="flex items-center gap-2 text-outline text-[12px]">
<span class="material-symbols-outlined text-[16px]">location_on</span>
                                    {{ $jadwal->lokasi_acara }}
                                </div>
</div>
</a>
@empty
<div class="md:col-span-2 glass p-8 rounded-xl text-center flex flex-col items-center justify-center">
    <span class="material-symbols-outlined text-4xl text-outline mb-4">event_busy</span>
    <p class="font-body-md" style="color: var(--t-on-surface-variant);">Belum ada jadwal pertunjukan mendatang saat ini.</p>
</div>
@endforelse

</div>
</div>
</div>
</section>
</main>
@endsection
