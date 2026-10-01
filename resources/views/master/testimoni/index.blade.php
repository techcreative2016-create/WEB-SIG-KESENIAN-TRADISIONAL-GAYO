@extends('layouts.master')
@section('title', 'Saran dan Ulasan')

@section('content')
<div class="space-y-8">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-headline-lg text-headline-lg text-on-surface font-bold">Saran dan Ulasan</h1>
            <p class="text-on-surface-variant text-label-md mt-1">Moderasi saran dan ulasan dari pengunjung website kesenian.</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-3 px-5 py-3 bg-amber-50 rounded-xl border border-amber-200">
                <span class="material-symbols-outlined text-amber-500" style="font-variation-settings:'FILL' 1;">hourglass_top</span>
                <div>
                    <p class="text-[11px] text-on-surface-variant uppercase tracking-wider">Menunggu</p>
                    <p class="font-bold text-xl text-on-surface">{{ $menunggu->count() }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3 px-5 py-3 bg-primary/5 rounded-xl border border-primary/20">
                <span class="material-symbols-outlined text-primary" style="font-variation-settings:'FILL' 1;">verified</span>
                <div>
                    <p class="text-[11px] text-on-surface-variant uppercase tracking-wider">Disetujui</p>
                    <p class="font-bold text-xl text-on-surface">{{ $disetujui->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Menunggu Persetujuan --}}
    @if($menunggu->count() > 0)
    <div class="glass-panel p-6 rounded-2xl">
        <h2 class="font-semibold text-amber-600 mb-4 flex items-center gap-2 text-[17px]">
            <span class="material-symbols-outlined text-lg" style="font-variation-settings:'FILL' 1;">pending_actions</span>
            Menunggu Persetujuan ({{ $menunggu->count() }})
        </h2>
        <div class="grid gap-4">
            @foreach($menunggu as $item)
            <div class="bg-amber-50/60 border border-amber-200 rounded-xl p-5 flex flex-col sm:flex-row sm:items-center gap-4 hover:shadow-md transition-shadow">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-bold text-sm shrink-0">
                            {{ strtoupper(substr($item->nama, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-on-surface">{{ $item->nama }}</p>
                            @if($item->asal)<p class="text-xs text-on-surface-variant">{{ $item->asal }}</p>@endif
                        </div>
                        <div class="ml-2 flex gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="text-base {{ $i <= $item->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                            @endfor
                        </div>
                        <span class="ml-auto text-xs text-on-surface-variant whitespace-nowrap">{{ $item->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-on-surface-variant text-sm leading-relaxed pl-13">{{ $item->pesan }}</p>
                </div>
                <div class="flex gap-2 shrink-0">
                    <form action="{{ route('master.testimoni.status', $item) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="disetujui">
                        <button type="submit" class="flex items-center gap-1.5 px-4 py-2 bg-primary text-white text-sm rounded-lg hover:bg-primary/80 transition-colors font-medium">
                            <span class="material-symbols-outlined text-sm">check</span> Setujui
                        </button>
                    </form>
                    <form action="{{ route('master.testimoni.status', $item) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="ditolak">
                        <button type="submit" class="flex items-center gap-1.5 px-4 py-2 bg-error/10 text-error text-sm rounded-lg hover:bg-error/20 transition-colors font-medium">
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
    <div class="glass-panel p-6 rounded-2xl">
        <h2 class="font-semibold text-primary mb-4 flex items-center gap-2 text-[17px]">
            <span class="material-symbols-outlined text-lg" style="font-variation-settings:'FILL' 1;">verified</span>
            Sudah Disetujui ({{ $disetujui->count() }})
        </h2>
        @if($disetujui->count() > 0)
        <div class="grid gap-4">
            @foreach($disetujui as $item)
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5 flex flex-col sm:flex-row sm:items-start gap-4 hover:shadow-sm transition-shadow">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold text-sm shrink-0">
                            {{ strtoupper(substr($item->nama, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-on-surface">{{ $item->nama }}</p>
                            @if($item->asal)<p class="text-xs text-on-surface-variant">{{ $item->asal }}</p>@endif
                        </div>
                        <div class="ml-2 flex gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="text-base {{ $i <= $item->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                            @endfor
                        </div>
                        <span class="ml-auto text-xs text-on-surface-variant whitespace-nowrap">{{ $item->created_at->format('d M Y') }}</span>
                    </div>
                    <p class="text-on-surface-variant text-sm leading-relaxed">{{ $item->pesan }}</p>
                </div>
                <form action="{{ route('master.testimoni.destroy', $item) }}" method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus ulasan ini?')">
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
            <p>Belum ada ulasan yang disetujui.</p>
        </div>
        @endif
    </div>

    {{-- Ditolak --}}
    @if($ditolak->count() > 0)
    <div class="glass-panel p-6 rounded-2xl">
        <h2 class="font-semibold text-error mb-4 flex items-center gap-2 text-[17px]">
            <span class="material-symbols-outlined text-lg" style="font-variation-settings:'FILL' 1;">cancel</span>
            Ditolak ({{ $ditolak->count() }})
        </h2>
        <div class="grid gap-4">
            @foreach($ditolak as $item)
            <div class="bg-error/5 border border-error/20 rounded-xl p-5 flex flex-col sm:flex-row sm:items-start gap-4 opacity-75 hover:opacity-100 transition-opacity">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-full bg-error/20 flex items-center justify-center text-error font-bold text-sm shrink-0">
                            {{ strtoupper(substr($item->nama, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-on-surface">{{ $item->nama }}</p>
                            @if($item->asal)<p class="text-xs text-on-surface-variant">{{ $item->asal }}</p>@endif
                        </div>
                        <div class="ml-2 flex gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="text-base {{ $i <= $item->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                            @endfor
                        </div>
                        <span class="ml-auto text-xs text-on-surface-variant whitespace-nowrap">{{ $item->created_at->format('d M Y') }}</span>
                    </div>
                    <p class="text-on-surface-variant text-sm leading-relaxed">{{ $item->pesan }}</p>
                </div>
                <form action="{{ route('master.testimoni.destroy', $item) }}" method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus ulasan ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="flex items-center gap-1 px-3 py-2 text-error/70 hover:text-error hover:bg-error/10 rounded-lg transition-colors text-sm">
                        <span class="material-symbols-outlined text-base">delete</span>
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
