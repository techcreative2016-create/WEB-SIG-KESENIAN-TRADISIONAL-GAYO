@extends('layouts.publik')

@section('title', 'Arsip Dokumentasi | Seni & Budaya Gayo')

@section('content')
<main class="pt-toolbar-height min-h-screen pb-24">
    <div class="px-margin-desktop max-w-screen-2xl mx-auto pt-16">

        {{-- Header Section --}}
        <div class="max-w-3xl mb-12">
            <h1 class="font-display-lg text-display-lg text-white mb-4">Arsip Dokumentasi</h1>
            <p class="font-body-lg text-body-lg text-on-surface-variant">
                Menjelajahi warisan takbenda dan jejak sejarah Gayo melalui lensa digital yang dikurasi.
                Melestarikan Jantung Dataran Tinggi untuk generasi mendatang.
            </p>
        </div>

        {{-- Search and Filters --}}
        <form method="GET" action="{{ route('publik.arsip') }}"
              class="flex flex-col md:flex-row gap-6 items-center mb-12 p-6 glass rounded-xl border border-[#0D9373]/8">
            <div class="flex-grow flex items-center bg-[#1c1b1b] px-4 py-3 rounded-lg border border-[#0D9373]/10 w-full md:w-auto">
                <span class="material-symbols-outlined text-on-surface-variant mr-3">search</span>
                <input name="cari" value="{{ request('cari') }}"
                       class="bg-transparent border-none p-0 focus:ring-0 text-sm w-full text-white placeholder-on-surface-variant"
                       placeholder="Cari arsip budaya...">
            </div>
            <div class="flex flex-wrap gap-3 w-full md:w-auto">
                @foreach(['Foto','Video','Naskah','Audio'] as $tipe)
                <button type="submit" name="tipe" value="{{ $tipe }}"
                        class="px-6 py-2 rounded-full border text-sm font-label-md transition-colors
                            {{ $tipeAktif === $tipe
                                ? 'bg-primary border-primary text-on-primary'
                                : 'border-[#0D9373]/15 hover:bg-[#0D9373]/5 text-white' }}">
                    {{ $tipe }}
                </button>
                @endforeach
                @if($tipeAktif || request('cari'))
                <a href="{{ route('publik.arsip') }}"
                   class="px-4 py-2 rounded-full border border-[#0D9373]/15 text-sm text-on-surface-variant hover:bg-[#0D9373]/5 transition-colors">
                    ✕ Reset
                </a>
                @endif
            </div>
        </form>

        {{-- Archive Grid --}}
        @if($dokumentasis->isEmpty())
        <div class="text-center py-24 text-on-surface-variant">
            <span class="material-symbols-outlined text-6xl block mb-4 opacity-40">photo_library</span>
            <p class="text-lg">Belum ada dokumentasi yang tersedia.</p>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($dokumentasis as $dok)
            @php
                $tipeLabel = strtoupper($dok->tipe);
            @endphp
            <div class="glass rounded-xl overflow-hidden border border-[#0D9373]/8 hover:border-primary-container/30 transition-all group cursor-pointer">
                {{-- Thumbnail / Placeholder --}}
                <div class="relative h-56 overflow-hidden">
                    @if($dok->thumbnail_url)
                        <img src="{{ $dok->thumbnail_url }}"
                             class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700">
                    @elseif($dok->tipe === 'Foto' && $dok->file_url)
                        <img src="{{ $dok->file_url }}"
                             class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700">
                    @else
                        <div class="absolute inset-0 bg-[#2a2a2a] flex items-center justify-center">
                            <span class="material-symbols-outlined text-6xl text-outline/50">{{ $dok->icon }}</span>
                        </div>
                    @endif
                    {{-- Tipe Badge --}}
                    <div class="absolute top-4 left-4 bg-[#fff6df] text-black text-[10px] font-bold px-2 py-1 rounded shadow-lg uppercase tracking-wider">
                        {{ $tipeLabel }}
                    </div>
                </div>

                <div class="p-6">
                    <div class="font-mono-data text-[10px] text-outline mb-2 tracking-widest uppercase">
                        {{ $dok->tanggal_dokumen->translatedFormat('d M Y') }}
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-white group-hover:text-primary-container transition-colors line-clamp-2">
                        {{ $dok->judul }}
                    </h3>
                    @if($dok->deskripsi)
                    <p class="text-on-surface-variant text-sm mb-4 line-clamp-2">{{ $dok->deskripsi }}</p>
                    @endif
                    <div class="flex items-center justify-between mt-auto">
                        {{-- Lihat --}}
                        @if($dok->url_eksternal)
                            <a href="{{ $dok->url_eksternal }}" target="_blank" rel="noopener"
                               class="bg-[#fff6df] text-black px-6 py-2 rounded font-bold text-sm hover:bg-opacity-80 transition-colors">
                                Lihat
                            </a>
                        @elseif($dok->file_url)
                            <a href="{{ $dok->file_url }}" target="_blank"
                               class="bg-[#fff6df] text-black px-6 py-2 rounded font-bold text-sm hover:bg-opacity-80 transition-colors">
                                Lihat
                            </a>
                        @else
                            <a href="{{ route('publik.arsip.show', $dok) }}"
                               class="bg-[#fff6df] text-black px-6 py-2 rounded font-bold text-sm hover:bg-opacity-80 transition-colors">
                                Lihat
                            </a>
                        @endif
                        {{-- Share --}}
                        <button onclick="navigator.share ? navigator.share({title:'{{ addslashes($dok->judul) }}', url:window.location.href}) : null"
                                class="w-10 h-10 rounded-full flex items-center justify-center hover:bg-[#0D9373]/5 transition-colors text-white">
                            <span class="material-symbols-outlined text-[20px]">share</span>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($dokumentasis->hasPages())
        <div class="mt-16 flex items-center justify-between border-t border-[#0D9373]/8 pt-8">
            @if($dokumentasis->onFirstPage())
                <span class="flex items-center gap-2 text-on-surface-variant/40 font-label-sm uppercase tracking-wider cursor-not-allowed">
                    <span class="material-symbols-outlined text-sm">chevron_left</span> Sebelumnya
                </span>
            @else
                <a href="{{ $dokumentasis->previousPageUrl() }}"
                   class="flex items-center gap-2 text-on-surface-variant hover:text-white transition-colors font-label-sm uppercase tracking-wider">
                    <span class="material-symbols-outlined text-sm">chevron_left</span> Sebelumnya
                </a>
            @endif

            <div class="font-mono-data text-sm text-outline">
                <span class="text-white">{{ str_pad($dokumentasis->currentPage(), 2, '0', STR_PAD_LEFT) }}</span>
                / {{ str_pad($dokumentasis->lastPage(), 2, '0', STR_PAD_LEFT) }}
            </div>

            @if($dokumentasis->hasMorePages())
                <a href="{{ $dokumentasis->nextPageUrl() }}"
                   class="flex items-center gap-2 text-on-surface-variant hover:text-white transition-colors font-label-sm uppercase tracking-wider">
                    Berikutnya <span class="material-symbols-outlined text-sm">chevron_right</span>
                </a>
            @else
                <span class="flex items-center gap-2 text-on-surface-variant/40 font-label-sm uppercase tracking-wider cursor-not-allowed">
                    Berikutnya <span class="material-symbols-outlined text-sm">chevron_right</span>
                </span>
            @endif
        </div>
        @endif

        @endif {{-- end if dokumentasis not empty --}}

    </div>
</main>
@endsection
