@extends('layouts.master')
@section('title', 'Persetujuan Berita')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-headline-lg font-headline-lg font-bold text-primary">Persetujuan Berita Terbaru</h1>
        <p class="text-on-surface-variant">Tinjau dan berikan persetujuan untuk berita yang dibuat oleh Admin Seni.</p>
    </div>
</div>

<div class="glass-panel rounded-2xl border border-outline-variant/30 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-high/50 border-b border-outline-variant/30">
                    <th class="px-6 py-4 text-label-md font-bold text-on-surface-variant uppercase tracking-wider">Thumbnail</th>
                    <th class="px-6 py-4 text-label-md font-bold text-on-surface-variant uppercase tracking-wider">Informasi Berita</th>
                    <th class="px-6 py-4 text-label-md font-bold text-on-surface-variant uppercase tracking-wider">Penulis</th>
                    <th class="px-6 py-4 text-label-md font-bold text-on-surface-variant uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-label-md font-bold text-on-surface-variant uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/20">
                @forelse($beritas as $berita)
                <tr class="hover:bg-white/40 transition-colors group {{ $berita->status == 'pending' ? 'bg-yellow-50/10' : '' }}">
                    <td class="px-6 py-4">
                        <div class="w-24 h-16 rounded-lg overflow-hidden bg-surface-container flex items-center justify-center border border-outline-variant/30">
                            @if($berita->thumbnail)
                                <img src="{{ $berita->thumbnail_url }}" alt="{{ $berita->judul }}" class="w-full h-full object-cover">
                            @else
                                <span class="material-symbols-outlined text-on-surface-variant/50">image</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-on-surface mb-1 text-[16px]">{{ $berita->judul }}</div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2 py-0.5 bg-secondary-container text-on-secondary-container rounded text-[10px] font-bold uppercase tracking-wider">
                                {{ $berita->kategori }}
                            </span>
                            <span class="text-label-sm text-on-surface-variant">{{ $berita->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <!-- Preview konten singkat -->
                        <div class="text-sm text-on-surface-variant line-clamp-2">
                            {{ Str::limit(strip_tags($berita->konten), 100) }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-semibold text-on-surface">{{ $berita->penulis->name ?? 'Admin Terhapus' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @if($berita->status == 'approved')
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-label-sm font-bold flex items-center gap-1 w-max">
                                <span class="material-symbols-outlined text-[16px]">check_circle</span> Disetujui
                            </span>
                        @elseif($berita->status == 'rejected')
                            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-label-sm font-bold flex items-center gap-1 w-max">
                                <span class="material-symbols-outlined text-[16px]">cancel</span> Ditolak
                            </span>
                        @else
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-label-sm font-bold flex items-center gap-1 w-max animate-pulse">
                                <span class="material-symbols-outlined text-[16px]">pending_actions</span> Menunggu
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-2">
                            @if($berita->status == 'pending')
                                <form action="{{ route('master.berita.status', $berita->id) }}" method="POST" onsubmit="return confirm('Setujui berita ini untuk tayang di Halaman Utama?');">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="w-full px-3 py-1.5 bg-primary text-white text-sm font-bold rounded-lg hover:bg-primary/90 transition-colors flex items-center justify-center gap-1">
                                        <span class="material-symbols-outlined text-[16px]">thumb_up</span> Setujui
                                    </button>
                                </form>
                                <form action="{{ route('master.berita.status', $berita->id) }}" method="POST" onsubmit="return confirm('Tolak berita ini?');">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="w-full px-3 py-1.5 bg-error/10 text-error text-sm font-bold rounded-lg hover:bg-error/20 transition-colors flex items-center justify-center gap-1">
                                        <span class="material-symbols-outlined text-[16px]">thumb_down</span> Tolak
                                    </button>
                                </form>
                            @else
                                <div class="text-center text-on-surface-variant/50 text-sm italic mb-1">
                                    Selesai
                                </div>
                            @endif
                            <form action="{{ route('master.berita.destroy', $berita->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini secara permanen?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full px-3 py-1.5 bg-[#BA1A1A] text-white text-sm font-bold rounded-lg hover:bg-[#93000A] transition-colors flex items-center justify-center gap-1 shadow-sm">
                                    <span class="material-symbols-outlined text-[16px]">delete</span> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-[48px] mb-2 opacity-50">inbox</span>
                        <p>Belum ada berita yang diajukan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($beritas->hasPages())
    <div class="p-4 border-t border-outline-variant/30">
        {{ $beritas->links() }}
    </div>
    @endif
</div>
@endsection
