@extends('layouts.publik')

@section('title', 'Sanggar Seni | Gayo Heritage')

@push('styles')
<style>
    .kerawang-strip {
        height: 4px;
        background: linear-gradient(90deg, var(--t-accent) 0%, var(--t-icon-color) 50%, var(--t-accent) 100%);
    }
    .card-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
</style>
@endpush

@section('content')
<main class="min-h-screen pb-20">
    {{-- Hero Banner --}}
    <section class="relative h-[45vh] w-full overflow-hidden flex items-center justify-center" style="background: linear-gradient(135deg, var(--t-accent) 0%, var(--t-icon-color) 100%);">
        <div class="absolute inset-0 opacity-20" style="background-image: url('https://images.unsplash.com/photo-1547153760-18fc86324498?q=80&w=1200'); background-size: cover; background-position: center;"></div>
        <div class="relative text-center px-6">
            <span class="text-[11px] font-bold uppercase tracking-widest text-white/80">Direktori Kesenian Gayo</span>
            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight mt-3 mb-4 text-white">Sanggar Seni</h1>
            <p class="max-w-xl mx-auto text-base text-white/80 leading-relaxed">
                Temukan sanggar seni dan budaya yang aktif melestarikan warisan leluhur di Kabupaten Bener Meriah dan Aceh Tengah.
            </p>
        </div>
        <div class="absolute bottom-0 left-0 right-0 kerawang-strip"></div>
    </section>

    {{-- Content --}}
    <section class="max-w-6xl mx-auto px-6 mt-12">
        @if($sanggars->count() > 0)
            <div class="flex justify-between items-center mb-8">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest" style="color: var(--t-icon-color);">MENAMPILKAN {{ $sanggars->total() }} SANGGAR</span>
                    <h2 class="text-2xl font-bold mt-1" style="color: var(--t-accent);">Daftar Sanggar Seni Aktif</h2>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($sanggars as $sanggar)
                <a href="{{ route('publik.sanggar.show', $sanggar->slug) }}"
                   class="block rounded-2xl overflow-hidden card-hover shadow-md"
                   style="background: var(--t-surface-container); border: 1px solid var(--t-section-border);">
                    {{-- Foto --}}
                    <div class="relative h-48 overflow-hidden">
                        <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                             src="{{ $sanggar->foto_utama_url }}"
                             alt="{{ $sanggar->nama_sanggar }}">
                        <div class="absolute top-3 left-3">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-white px-3 py-1 rounded-full"
                                  style="background: var(--t-accent);">
                                {{ $sanggar->kategori }}
                            </span>
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="p-5 space-y-3">
                        <h3 class="font-bold text-base leading-tight" style="color: var(--t-on-surface);">
                            {{ $sanggar->nama_sanggar }}
                        </h3>
                        <div class="flex items-center gap-2 text-sm" style="color: var(--t-on-surface-variant);">
                            <span class="material-symbols-outlined text-[16px]" style="color: var(--t-icon-color);">location_on</span>
                            <span>Kec. {{ $sanggar->kecamatan }}, {{ $sanggar->kabupaten }}</span>
                        </div>
                        @if($sanggar->deskripsi)
                        <p class="text-xs leading-relaxed line-clamp-2" style="color: var(--t-on-surface-variant);">
                            {{ $sanggar->deskripsi }}
                        </p>
                        @endif
                        <div class="pt-2 flex justify-between items-center">
                            <span class="text-[10px] font-bold uppercase px-2 py-1 rounded"
                                  style="background: var(--t-surface-container-high); color: var(--t-on-surface-variant);">
                                {{ $sanggar->status }}
                            </span>
                            <span class="text-xs font-semibold flex items-center gap-1" style="color: var(--t-accent);">
                                Lihat Detail
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-10 flex justify-center">
                {{ $sanggars->links() }}
            </div>

        @else
            {{-- Empty State --}}
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <div class="w-24 h-24 rounded-full flex items-center justify-center mb-6"
                     style="background: var(--t-surface-container);">
                    <span class="material-symbols-outlined text-[48px]" style="color: var(--t-icon-color);">theater_comedy</span>
                </div>
                <h2 class="text-2xl font-bold mb-3" style="color: var(--t-on-surface);">Belum Ada Sanggar Terdaftar</h2>
                <p class="max-w-md text-sm leading-relaxed" style="color: var(--t-on-surface-variant);">
                    Sanggar seni akan muncul di sini setelah didaftarkan oleh admin. Kembali lagi nanti!
                </p>
                <a href="{{ route('publik.beranda') }}"
                   class="mt-8 inline-flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-white transition-all hover:scale-105"
                   style="background: var(--t-accent);">
                    <span class="material-symbols-outlined text-[18px]">home</span>
                    Kembali ke Beranda
                </a>
            </div>
        @endif
    </section>
</main>
@endsection
