@extends(auth()->user()->role === 'master' ? 'layouts.master' : 'layouts.admin')
@section('title', 'Berita Terbaru')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-headline-lg font-headline-lg font-bold text-primary">Daftar Berita</h1>
        <p class="text-on-surface-variant">Kelola berita/artikel terbaru yang Anda buat.</p>
    </div>
    <a href="{{ route('admin.berita.create') }}" class="flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/90 transition-colors">
        <span class="material-symbols-outlined text-[20px]">add</span>
        <span class="font-bold">Tambah Berita</span>
    </a>
</div>

<div class="glass-panel rounded-2xl border border-outline-variant/30 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-high/50 border-b border-outline-variant/30">
                    <th class="px-6 py-4 text-label-md font-bold text-on-surface-variant uppercase tracking-wider">Thumbnail</th>
                    <th class="px-6 py-4 text-label-md font-bold text-on-surface-variant uppercase tracking-wider">Judul</th>
                    <th class="px-6 py-4 text-label-md font-bold text-on-surface-variant uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-4 text-label-md font-bold text-on-surface-variant uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-label-md font-bold text-on-surface-variant uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/20">
                @forelse($beritas as $berita)
                <tr class="hover:bg-white/40 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="w-20 h-14 rounded-lg overflow-hidden bg-surface-container flex items-center justify-center">
                            @if($berita->thumbnail)
                                <img src="{{ $berita->thumbnail_url }}" alt="{{ $berita->judul }}" class="w-full h-full object-cover">
                            @else
                                <span class="material-symbols-outlined text-on-surface-variant/50">image</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-on-surface mb-1">{{ $berita->judul }}</div>
                        <div class="text-label-sm text-on-surface-variant">{{ $berita->created_at->format('d M Y, H:i') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-secondary-container text-on-secondary-container rounded-full text-label-sm font-bold">
                            {{ $berita->kategori }}
                        </span>
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
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-label-sm font-bold flex items-center gap-1 w-max">
                                <span class="material-symbols-outlined text-[16px]">schedule</span> Menunggu
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.berita.edit', $berita->id) }}" class="p-2 text-primary hover:bg-primary/10 rounded-lg transition-colors" title="Edit">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </a>
                            <form action="{{ route('admin.berita.destroy', $berita->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus berita ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-error hover:bg-error/10 rounded-lg transition-colors" title="Hapus">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-[48px] mb-2 opacity-50">article</span>
                        <p>Belum ada berita yang ditambahkan.</p>
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
