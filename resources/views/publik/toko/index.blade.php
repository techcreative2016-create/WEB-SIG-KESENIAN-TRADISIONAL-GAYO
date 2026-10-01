@extends('layouts.publik')

@section('title', 'Toko Kesenian | Gayo Heritage')

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
    <section class="relative h-[45vh] w-full overflow-hidden flex items-center justify-center" style="background: linear-gradient(135deg, #0B8264 0%, var(--t-accent) 100%);">
        <div class="absolute inset-0 opacity-20" style="background-image: url('https://images.unsplash.com/photo-1605722243979-fe0be8158232?q=80&w=1200'); background-size: cover; background-position: center;"></div>
        <div class="relative text-center px-6">
            <span class="text-[11px] font-bold uppercase tracking-widest text-white/80">Produk Asli Gayo</span>
            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight mt-3 mb-4 text-white">Toko Kesenian</h1>
            <p class="max-w-xl mx-auto text-base text-white/80 leading-relaxed">
                Temukan kerajinan tangan, busana adat, dan produk seni autentik khas Gayo dari para pengrajin lokal terpercaya.
            </p>
        </div>
        <div class="absolute bottom-0 left-0 right-0 kerawang-strip"></div>
    </section>

    {{-- Content --}}
    <section class="max-w-6xl mx-auto px-6 mt-12">
        @if($tokos->count() > 0)
            <div class="flex justify-between items-center mb-8">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest" style="color: var(--t-icon-color);">MENAMPILKAN {{ $tokos->total() }} TOKO</span>
                    <h2 class="text-2xl font-bold mt-1" style="color: var(--t-accent);">Daftar Toko Kesenian</h2>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($tokos as $toko)
                <a href="{{ route('publik.toko.show', $toko->slug) }}"
                   class="block rounded-2xl overflow-hidden card-hover shadow-md"
                   style="background: var(--t-surface-container); border: 1px solid var(--t-section-border);">
                    {{-- Foto --}}
                    <div class="relative h-48 overflow-hidden">
                        <img class="w-full h-full object-cover transition-transform duration-500"
                             src="{{ $toko->foto_utama_url }}"
                             alt="{{ $toko->nama_toko }}">
                        <div class="absolute top-3 left-3">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-white px-3 py-1 rounded-full"
                                  style="background: var(--t-accent);">
                                {{ $toko->jenis_produk }}
                            </span>
                        </div>
                        @if($toko->status === 'Buka')
                        <div class="absolute top-3 right-3">
                            <span class="text-[10px] font-bold uppercase text-white px-2 py-1 rounded-full bg-green-600">
                                Buka
                            </span>
                        </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="p-5 space-y-3">
                        <h3 class="font-bold text-base leading-tight" style="color: var(--t-on-surface);">
                            {{ $toko->nama_toko }}
                        </h3>
                        <div class="flex items-center gap-2 text-sm" style="color: var(--t-on-surface-variant);">
                            <span class="material-symbols-outlined text-[16px]" style="color: var(--t-icon-color);">location_on</span>
                            <span>Kec. {{ $toko->kecamatan }}, {{ $toko->kabupaten }}</span>
                        </div>
                        @if($toko->jam_buka)
                        <div class="flex items-center gap-2 text-xs" style="color: var(--t-on-surface-variant);">
                            <span class="material-symbols-outlined text-[14px]">schedule</span>
                            <span>{{ $toko->jam_buka }}</span>
                        </div>
                        @endif
                        @if($toko->deskripsi)
                        <p class="text-xs leading-relaxed line-clamp-2" style="color: var(--t-on-surface-variant);">
                            {{ $toko->deskripsi }}
                        </p>
                        @endif
                        <div class="pt-2 flex justify-between items-center">
                            <span class="text-xs font-semibold flex items-center gap-1" style="color: var(--t-icon-color);">
                                <span class="material-symbols-outlined text-[14px]">storefront</span>
                                {{ $toko->jenis_produk }}
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
                {{ $tokos->links() }}
            </div>

        @else
            {{-- Empty State --}}
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <div class="w-24 h-24 rounded-full flex items-center justify-center mb-6"
                     style="background: var(--t-surface-container);">
                    <span class="material-symbols-outlined text-[48px]" style="color: var(--t-icon-color);">storefront</span>
                </div>
                <h2 class="text-2xl font-bold mb-3" style="color: var(--t-on-surface);">Belum Ada Toko Terdaftar</h2>
                <p class="max-w-md text-sm leading-relaxed" style="color: var(--t-on-surface-variant);">
                    Toko kesenian akan muncul di sini setelah didaftarkan oleh admin. Kembali lagi nanti!
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
