@extends('layouts.master')

@section('title', 'Banner Utama Mobile')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-on-surface">Kelola Banner Utama Mobile</h1>
        <p class="text-sm text-on-surface-variant mt-1">Atur gambar, judul, dan detail yang muncul di bagian paling atas aplikasi mobile (Hero Carousel).</p>
    </div>
    <a href="{{ route('master.mobile-hero.create') }}" class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90 transition-all font-label-md">
        <span class="material-symbols-outlined text-[18px]">add</span>
        Tambah Banner Baru
    </a>
</div>

<div class="bg-surface-lowest rounded-2xl shadow-sm border border-outline-variant overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-surface-container-low border-b border-outline-variant text-on-surface-variant font-label-md text-[13px]">
                <th class="px-6 py-4 font-semibold uppercase tracking-wider">Gambar & Judul</th>
                <th class="px-6 py-4 font-semibold uppercase tracking-wider">Tag</th>
                <th class="px-6 py-4 font-semibold uppercase tracking-wider">Status</th>
                <th class="px-6 py-4 font-semibold uppercase tracking-wider">Urutan</th>
                <th class="px-6 py-4 font-semibold uppercase tracking-wider text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant">
            @forelse($banners as $banner)
            <tr class="hover:bg-surface-container-low/50 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-4">
                        <div class="w-20 h-14 rounded-lg overflow-hidden bg-surface-container border border-outline-variant flex-shrink-0">
                            <img src="{{ asset($banner->image_path) }}" alt="Banner" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="font-bold text-on-surface">{{ $banner->title }}</p>
                            <p class="text-sm text-on-surface-variant line-clamp-1">{{ $banner->subtitle }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    @if($banner->tag)
                    <span class="px-2 py-1 bg-primary/10 text-primary text-xs font-semibold rounded-md border border-primary/20">
                        {{ $banner->tag }}
                    </span>
                    @else
                    <span class="text-on-surface-variant text-sm">-</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    @if($banner->is_active)
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full flex items-center w-max gap-1">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span> Aktif
                    </span>
                    @else
                    <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full flex items-center w-max gap-1">
                        <span class="w-2 h-2 rounded-full bg-red-500"></span> Tidak Aktif
                    </span>
                    @endif
                </td>
                <td class="px-6 py-4 text-on-surface font-semibold text-center">
                    {{ $banner->urutan }}
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('master.mobile-hero.edit', $banner) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors tooltip-btn" title="Edit">
                            <span class="material-symbols-outlined text-[20px]">edit</span>
                        </a>
                        <form action="{{ route('master.mobile-hero.destroy', $banner) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus banner ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-error hover:bg-error/10 rounded-lg transition-colors tooltip-btn" title="Hapus">
                                <span class="material-symbols-outlined text-[20px]">delete</span>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center text-on-surface-variant">
                    <span class="material-symbols-outlined text-[48px] opacity-50 mb-3 block">hide_image</span>
                    <p class="font-semibold text-on-surface">Belum ada banner</p>
                    <p class="text-sm mt-1">Klik tombol tambah untuk membuat banner hero pertama.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
