@extends('layouts.admin')
@section('title', 'Manajemen Testimoni')

@section('content')
<div class="space-y-8">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-headline-lg text-headline-lg text-on-surface font-bold">Manajemen Testimoni</h1>
            <p class="text-on-surface-variant text-label-md mt-1">Moderasi ulasan dan testimoni dari pengunjung website.</p>
        </div>
        <div class="flex items-center gap-3 px-5 py-3 bg-secondary-container/30 rounded-xl border border-secondary-container">
            <span class="material-symbols-outlined text-secondary" style="font-variation-settings:'FILL' 1;">pending_actions</span>
            <div>
                <p class="text-[11px] text-on-surface-variant uppercase tracking-wider">Menunggu</p>
                <p class="font-bold text-xl text-on-surface">{{ $menunggu->count() }}</p>
            </div>
        </div>
    </div>

    {{-- Menunggu Persetujuan --}}
    @if($menunggu->count() > 0)
    <div>
        <h2 class="font-semibold text-amber-600 mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-lg" style="font-variation-settings:'FILL' 1;">hourglass_top</span>
            Menunggu Persetujuan ({{ $menunggu->count() }})
        </h2>
        <div class="grid gap-4">
            @foreach($menunggu as $item)
            <div class="bg-surface-container-lowest border border-amber-200 rounded-xl p-5 flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-1">
                        <div class="w-9 h-9 rounded-full bg-primary flex items-center justify-center text-white font-bold text-sm">
                            {{ strtoupper(substr($item->nama, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-on-surface">{{ $item->nama }}</p>
                            @if($item->asal)<p class="text-xs text-on-surface-variant">{{ $item->asal }}</p>@endif
                        </div>
                        <div class="ml-2 flex gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="text-sm {{ $i <= $item->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                            @endfor
                        </div>
                        <span class="ml-auto text-xs text-on-surface-variant">{{ $item->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-on-surface-variant mt-2 text-sm leading-relaxed">{{ $item->pesan }}</p>
                </div>
                <div class="flex gap-2 shrink-0">
                    <form action="{{ route('admin.testimoni.status', $item) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="disetujui">
                        <button type="submit" class="flex items-center gap-1.5 px-4 py-2 bg-primary text-white text-sm rounded-lg hover:bg-primary/80 transition-colors">
                            <span class="material-symbols-outlined text-sm">check</span> Setujui
                        </button>
                    </form>
                    <form action="{{ route('admin.testimoni.status', $item) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="ditolak">
                        <button type="submit" class="flex items-center gap-1.5 px-4 py-2 bg-error/10 text-error text-sm rounded-lg hover:bg-error/20 transition-colors">
                            <span class="material-symbols-outlined text-sm">close</span> Tolak
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Sudah Disetujui --}}
    <div>
        <h2 class="font-semibold text-primary mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-lg" style="font-variation-settings:'FILL' 1;">verified</span>
            Sudah Disetujui ({{ $disetujui->count() }})
        </h2>
        @if($disetujui->count() > 0)
        <div class="grid gap-4">
            @foreach($disetujui as $item)
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5 flex flex-col sm:flex-row sm:items-start gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-1">
                        <div class="w-9 h-9 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold text-sm">
                            {{ strtoupper(substr($item->nama, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-on-surface">{{ $item->nama }}</p>
                            @if($item->asal)<p class="text-xs text-on-surface-variant">{{ $item->asal }}</p>@endif
                        </div>
                        <div class="ml-2 flex gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="text-sm {{ $i <= $item->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                            @endfor
                        </div>
                        <span class="ml-auto text-xs text-on-surface-variant">{{ $item->created_at->format('d M Y') }}</span>
                    </div>
                    <p class="text-on-surface-variant mt-2 text-sm leading-relaxed">{{ $item->pesan }}</p>
                </div>
                <form action="{{ route('admin.testimoni.destroy', $item) }}" method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus testimoni ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="flex items-center gap-1 px-3 py-2 text-error/70 hover:text-error hover:bg-error/10 rounded-lg transition-colors text-sm">
                        <span class="material-symbols-outlined text-base">delete</span>
                    </button>
                </form>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12 text-on-surface-variant">
            <span class="material-symbols-outlined text-5xl block mb-3">rate_review</span>
            <p>Belum ada testimoni yang disetujui.</p>
        </div>
        @endif
    </div>
</div>
@endsection
