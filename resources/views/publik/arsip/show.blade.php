@extends('layouts.publik')

@section('title', $dokumentasi->judul . ' | Arsip Dokumentasi')

@section('content')
<main class="pt-toolbar-height min-h-screen pb-24">
    <div class="px-margin-desktop max-w-4xl mx-auto pt-16">

        {{-- Back --}}
        <a href="{{ route('publik.arsip') }}" class="inline-flex items-center gap-2 text-on-surface-variant hover:text-white transition-colors mb-8">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            Kembali ke Arsip
        </a>

        {{-- Header --}}
        <div class="mb-8">
            <span class="inline-block px-3 py-1 bg-[#fff6df] text-black text-xs font-bold rounded uppercase tracking-wider mb-4">
                {{ $dokumentasi->tipe }}
            </span>
            <h1 class="font-display-md text-display-md text-white mb-3">{{ $dokumentasi->judul }}</h1>
            <p class="text-on-surface-variant">
                {{ $dokumentasi->tanggal_dokumen->translatedFormat('d F Y') }}
            </p>
        </div>

        {{-- Thumbnail / Viewer --}}
        @if($dokumentasi->tipe === 'Foto' && $dokumentasi->file_url)
            <div class="mb-8 rounded-2xl overflow-hidden border border-[#0D9373]/10">
                <img src="{{ $dokumentasi->file_url }}" class="w-full max-h-[600px] object-contain bg-black">
            </div>
        @elseif($dokumentasi->tipe === 'Video' && $dokumentasi->url_eksternal)
            <div class="mb-8 rounded-2xl overflow-hidden border border-[#0D9373]/10 aspect-video">
                @php
                    $ytId = null;
                    if(preg_match('/(?:youtu\.be\/|youtube\.com\/watch\?v=)([^&]+)/', $dokumentasi->url_eksternal, $m)){
                        $ytId = $m[1];
                    }
                @endphp
                @if($ytId)
                    <iframe src="https://www.youtube.com/embed/{{ $ytId }}"
                            class="w-full h-full" allowfullscreen></iframe>
                @endif
            </div>
        @elseif($dokumentasi->tipe === 'Audio' && $dokumentasi->file_url)
            <div class="mb-8 p-6 glass rounded-2xl border border-[#0D9373]/10">
                @if($dokumentasi->thumbnail_url)
                    <img src="{{ $dokumentasi->thumbnail_url }}" class="w-full h-64 object-cover rounded-xl mb-4 opacity-80">
                @endif
                <audio controls class="w-full">
                    <source src="{{ $dokumentasi->file_url }}">
                    Browser Anda tidak mendukung audio player.
                </audio>
            </div>
        @elseif($dokumentasi->thumbnail_url)
            <div class="mb-8 rounded-2xl overflow-hidden border border-[#0D9373]/10">
                <img src="{{ $dokumentasi->thumbnail_url }}" class="w-full max-h-[500px] object-cover">
            </div>
        @endif

        {{-- Deskripsi --}}
        @if($dokumentasi->deskripsi)
        <div class="glass rounded-xl p-8 border border-[#0D9373]/8 mb-8">
            <h2 class="font-title-lg text-white mb-4">Tentang Dokumentasi Ini</h2>
            <p class="text-on-surface-variant leading-relaxed">{{ $dokumentasi->deskripsi }}</p>
        </div>
        @endif

        {{-- Link file / eksternal --}}
        @if($dokumentasi->url_eksternal || ($dokumentasi->tipe !== 'Foto' && $dokumentasi->file_url))
        <div class="flex gap-4">
            @if($dokumentasi->url_eksternal)
            <a href="{{ $dokumentasi->url_eksternal }}" target="_blank" rel="noopener"
               class="flex items-center gap-2 px-6 py-3 bg-primary/20 border border-primary/40 text-primary rounded-xl hover:bg-primary/30 transition-colors">
                <span class="material-symbols-outlined">open_in_new</span>
                Buka Sumber Asli
            </a>
            @endif
            @if($dokumentasi->file_url && $dokumentasi->tipe !== 'Audio')
            <a href="{{ $dokumentasi->file_url }}" target="_blank"
               class="flex items-center gap-2 px-6 py-3 bg-white/5 border border-[#0D9373]/15 text-white rounded-xl hover:bg-[#0D9373]/5 transition-colors">
                <span class="material-symbols-outlined">download</span>
                Unduh File
            </a>
            @endif
        </div>
        @endif

    </div>
</main>
@endsection
