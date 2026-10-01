@extends(auth()->user()->role === 'master' ? 'layouts.master' : 'layouts.admin')
@section('title', 'Edit Berita')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-headline-lg font-headline-lg font-bold text-primary">Edit Berita</h1>
        <p class="text-on-surface-variant">Ubah informasi berita (akan mengubah status kembali menjadi Menunggu Persetujuan).</p>
    </div>
    <a href="{{ route('admin.berita.index') }}" class="flex items-center gap-2 bg-surface-container-high text-on-surface px-4 py-2 rounded-lg hover:bg-surface-dim transition-colors">
        <span class="material-symbols-outlined text-[20px]">arrow_back</span>
        <span class="font-bold">Kembali</span>
    </a>
</div>

<div class="glass-panel rounded-2xl border border-outline-variant/30 p-8 max-w-4xl">
    <form action="{{ route('admin.berita.update', $beritum->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="mb-6">
            <label for="judul" class="block text-label-md font-bold text-on-surface mb-2">Judul Berita</label>
            <input type="text" name="judul" id="judul" class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('judul') border-error @enderror" value="{{ old('judul', $beritum->judul) }}" required>
            @error('judul') <span class="text-error text-label-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="mb-6">
            <label for="kategori" class="block text-label-md font-bold text-on-surface mb-2">Kategori</label>
            <select name="kategori" id="kategori" class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('kategori') border-error @enderror" required>
                <option value="Artikel Budaya" {{ old('kategori', $beritum->kategori) == 'Artikel Budaya' ? 'selected' : '' }}>Artikel Budaya</option>
                <option value="Kriya Gayo" {{ old('kategori', $beritum->kategori) == 'Kriya Gayo' ? 'selected' : '' }}>Kriya Gayo</option>
                <option value="Sejarah" {{ old('kategori', $beritum->kategori) == 'Sejarah' ? 'selected' : '' }}>Sejarah</option>
                <option value="Event" {{ old('kategori', $beritum->kategori) == 'Event' ? 'selected' : '' }}>Event / Acara</option>
                <option value="Lainnya" {{ old('kategori', $beritum->kategori) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
            @error('kategori') <span class="text-error text-label-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="mb-6">
            <label class="block text-label-md font-bold text-on-surface mb-2">Thumbnail Saat Ini</label>
            @if($beritum->thumbnail)
                <img src="{{ $beritum->thumbnail_url }}" alt="Thumbnail" class="w-64 h-36 object-cover rounded-lg mb-4 border border-outline-variant/50">
            @else
                <div class="text-on-surface-variant text-sm mb-4">Belum ada thumbnail.</div>
            @endif
            
            <label for="thumbnail" class="block text-label-md font-bold text-on-surface mb-2">Ganti Thumbnail Baru (Opsional)</label>
            <input type="file" name="thumbnail" id="thumbnail" accept="image/*" class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('thumbnail') border-error @enderror">
            @error('thumbnail') <span class="text-error text-label-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="mb-8">
            <label for="konten" class="block text-label-md font-bold text-on-surface mb-2">Konten Berita</label>
            <textarea name="konten" id="konten" rows="10" class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('konten') border-error @enderror" required>{{ old('konten', $beritum->konten) }}</textarea>
            @error('konten') <span class="text-error text-label-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-primary text-white font-bold px-8 py-3 rounded-lg hover:bg-primary/90 transition-colors shadow-md">
                Perbarui & Ajukan Ulang
            </button>
        </div>
    </form>
</div>
@endsection
