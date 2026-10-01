@extends('layouts.publik')

@push('styles')
<style>
    .glass-premium {
        background: var(--t-card-bg);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--t-card-border);
    }
    .glass-premium:hover {
        border: 1px solid rgba(var(--t-accent-rgb), 0.3);
        background: var(--t-surface-high);
    }
    .img-zoom {
        transition: transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    .group:hover .img-zoom {
        transform: scale(1.05);
    }
    .group:hover .title-hover {
        color: var(--t-accent) !important;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen pt-28 pb-24 relative">
    <div class="absolute right-0 top-32 w-96 h-96 rounded-full blur-3xl -z-10" style="background: rgba(var(--t-accent-rgb), 0.05);"></div>
    <div class="absolute left-0 bottom-32 w-96 h-96 rounded-full blur-3xl -z-10" style="background: rgba(var(--t-accent-rgb), 0.05);"></div>

    <div class="max-w-screen-xl mx-auto px-6 relative z-20">
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight mb-4" style="color: var(--t-accent);">Portal Berita</h1>
            <p class="text-lg font-light max-w-2xl mx-auto" style="color: var(--t-on-surface-variant);">Kumpulan informasi, berita terkini, dan pengumuman seputar kegiatan seni dan budaya di Dataran Tinggi Gayo.</p>
        </div>
        
        @if($beritas->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($beritas as $berita)
            <a href="{{ route('publik.berita.show', $berita) }}" class="group glass-premium rounded-3xl overflow-hidden flex flex-col h-full transition-all hover:-translate-y-2">
                <div class="relative h-64 overflow-hidden" style="background: var(--t-surface-highest);">
                    @if($berita->thumbnail_url)
                    <img src="{{ $berita->thumbnail_url }}" alt="{{ $berita->judul }}" class="w-full h-full object-cover img-zoom opacity-80 group-hover:opacity-100">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-4xl" style="color: var(--t-on-surface-variant); opacity: 0.2;">newspaper</span>
                    </div>
                    @endif
                    <div class="absolute inset-0" style="background: linear-gradient(to top, var(--t-surface-container), transparent);"></div>
                    <div class="absolute top-4 left-4">
                        <span class="backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest" style="background: rgba(var(--t-accent-rgb), 0.1); color: var(--t-accent); border: 1px solid rgba(var(--t-accent-rgb), 0.3);">{{ $berita->kategori }}</span>
                    </div>
                </div>
                
                <div class="p-6 sm:p-8 flex-1 flex flex-col justify-between" style="background: var(--t-surface-container);">
                    <div>
                        <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest mb-4" style="color: var(--t-on-surface-variant);">
                            <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                            <span>{{ $berita->created_at->translatedFormat('d F Y') }}</span>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold mb-4 line-clamp-2 leading-tight transition-colors title-hover" style="color: var(--t-on-surface);">{{ $berita->judul }}</h2>
                        <p class="text-sm line-clamp-3 font-light leading-relaxed mb-6" style="color: var(--t-on-surface-variant);">{{ Str::limit(strip_tags($berita->konten), 120) }}</p>
                    </div>
                    
                    <div class="mt-auto flex items-center gap-2 text-[10px] sm:text-xs font-bold uppercase tracking-widest group-hover:translate-x-2 transition-transform" style="color: var(--t-accent);">
                        Baca Selengkapnya <span class="material-symbols-outlined text-sm">trending_flat</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        
        <div class="mt-16 flex justify-center">
            {{ $beritas->links() }}
        </div>
        @else
        <div class="text-center py-20 rounded-3xl border-dashed border-2" style="color: var(--t-on-surface-variant); border-color: rgba(var(--t-accent-rgb), 0.2);">
            <span class="material-symbols-outlined text-6xl mb-4">article</span>
            <p>Belum ada rilis berita.</p>
        </div>
        @endif
    </div>
</div>
@endsection
