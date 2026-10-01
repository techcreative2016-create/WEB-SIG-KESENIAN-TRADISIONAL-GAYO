@extends('layouts.publik')

@section('title', $berita->judul . ' | Berita')

@push('styles')
<style>
    .glass-premium {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(13, 147, 115, 0.1);
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.08);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen pt-28 pb-24 bg-background relative">
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-background z-10"></div>
        @if($berita->thumbnail_url)
        <img class="w-full h-full object-cover transform scale-110 blur-xl opacity-30" alt="Background" src="{{ $berita->thumbnail_url }}">
        @endif
    </div>

    <div class="max-w-4xl mx-auto px-6 relative z-20">
        <div class="mb-8">
            <a href="{{ route('publik.beranda') }}" class="text-[#3E5B4F] hover:text-[#0D9373] transition-colors text-sm font-bold flex items-center gap-2 uppercase tracking-widest">
                <span class="material-symbols-outlined text-sm">arrow_back</span> Kembali ke Beranda
            </a>
        </div>
        
        <div class="glass-premium rounded-3xl overflow-hidden shadow-2xl">
            @if($berita->thumbnail_url)
            <div class="relative w-full h-[400px] sm:h-[500px]">
                <img src="{{ $berita->thumbnail_url }}" alt="{{ $berita->judul }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="absolute bottom-6 sm:bottom-8 left-6 sm:left-10">
                    <span class="bg-[#0D9373] text-white px-4 py-2 rounded-full text-xs font-bold uppercase tracking-widest shadow-lg">{{ $berita->kategori }}</span>
                </div>
            </div>
            @endif
            
            <div class="p-8 sm:p-12 bg-white">
                <div class="flex items-center gap-4 text-[#3E5B4F] text-[10px] sm:text-xs font-bold uppercase tracking-widest mb-6">
                    <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                    <span>{{ $berita->created_at->translatedFormat('d F Y') }}</span>
                    @if($berita->penulis)
                    <span class="w-1 h-1 rounded-full bg-[#BFC9C3]"></span>
                    <span class="material-symbols-outlined text-[14px]">person</span>
                    <span>{{ $berita->penulis->name }}</span>
                    @endif
                </div>
                
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-[#1B3A2D] mb-10 leading-tight">
                    {{ $berita->judul }}
                </h1>
                
                <div class="prose prose-lg max-w-none text-[#3E5B4F] font-light leading-relaxed prose-a:text-[#0D9373] prose-headings:text-[#1B3A2D] prose-img:rounded-xl prose-strong:text-[#1B3A2D]">
                    {!! $berita->konten !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
