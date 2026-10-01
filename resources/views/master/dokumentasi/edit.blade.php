@extends(auth()->user()->role === 'master' ? 'layouts.master' : 'layouts.admin')

@section('title', 'Edit Dokumentasi Seni')

@section('content')

<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-headline-lg font-headline-lg font-bold text-primary">Edit Dokumentasi Seni</h1>
        <p class="text-on-surface-variant">Perbarui informasi dan file dokumentasi.</p>
    </div>
    <a href="{{ route('master.dokumentasi.index') }}"
       class="flex items-center gap-2 bg-surface-container-high text-on-surface px-4 py-2 rounded-lg hover:bg-surface-dim transition-colors">
        <span class="material-symbols-outlined text-[20px]">arrow_back</span>
        <span class="font-bold">Kembali</span>
    </a>
</div>

@if(session('sukses'))
<div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 flex items-center gap-2">
    <span class="material-symbols-outlined text-sm">check_circle</span>
    {{ session('sukses') }}
</div>
@endif

@if($errors->any())
<div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 space-y-1">
    @foreach($errors->all() as $err)
        <p class="flex items-center gap-2 text-sm">
            <span class="material-symbols-outlined text-sm">error</span> {{ $err }}
        </p>
    @endforeach
</div>
@endif

<form action="{{ route('master.dokumentasi.update', $dokumentasi) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')

    {{-- Informasi Dasar --}}
    <div class="glass-panel rounded-2xl border border-outline-variant/30 p-8 max-w-4xl mb-6">

        <div class="mb-6">
            <label class="block text-label-md font-bold text-on-surface mb-2">
                Judul Dokumentasi <span class="text-red-500">*</span>
            </label>
            <input type="text" name="judul" value="{{ old('judul', $dokumentasi->judul) }}" required
                   class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('judul') border-red-400 @enderror">
            @error('judul') <span class="text-red-500 text-label-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-label-md font-bold text-on-surface mb-2">
                    Tipe Dokumen <span class="text-red-500">*</span>
                </label>
                <select name="tipe" required
                        class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                    @foreach(['Foto','Video','Naskah','Audio'] as $t)
                        <option value="{{ $t }}" {{ old('tipe', $dokumentasi->tipe) === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
                @error('tipe') <span class="text-red-500 text-label-sm mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-label-md font-bold text-on-surface mb-2">
                    Tanggal Dokumen <span class="text-red-500">*</span>
                </label>
                <input type="date" name="tanggal_dokumen"
                       value="{{ old('tanggal_dokumen', $dokumentasi->tanggal_dokumen->format('Y-m-d')) }}" required
                       class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                @error('tanggal_dokumen') <span class="text-red-500 text-label-sm mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-label-md font-bold text-on-surface mb-2">Status Publikasi</label>
            <select name="status"
                    class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                <option value="Aktif" {{ old('status', $dokumentasi->status) === 'Aktif' ? 'selected' : '' }}>Aktif (tampil di halaman publik)</option>
                <option value="Arsip" {{ old('status', $dokumentasi->status) === 'Arsip' ? 'selected' : '' }}>Arsip (tersembunyi dari publik)</option>
            </select>
        </div>

        <div class="mb-2">
            <label class="block text-label-md font-bold text-on-surface mb-2">Deskripsi</label>
            <textarea name="deskripsi" rows="4"
                      class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary transition-all resize-none">{{ old('deskripsi', $dokumentasi->deskripsi) }}</textarea>
        </div>
    </div>

    {{-- File & Media --}}
    <div class="glass-panel rounded-2xl border border-outline-variant/30 p-8 max-w-4xl mb-6">
        <h2 class="text-title-md font-bold text-on-surface mb-6 pb-3 border-b border-outline-variant/30">
            File & Media
        </h2>

        {{-- File Utama --}}
        <div class="mb-6">
            <label class="block text-label-md font-bold text-on-surface mb-2">
                Ganti File Utama
                <span class="font-normal text-on-surface-variant text-sm ml-1">(Kosongkan untuk mempertahankan file lama)</span>
            </label>
            @if($dokumentasi->file_path)
            <div class="mb-3 flex items-center gap-3 p-3 bg-surface-container border border-outline-variant/30 rounded-lg">
                <span class="material-symbols-outlined text-primary">{{ $dokumentasi->icon }}</span>
                <a href="{{ $dokumentasi->file_url }}" target="_blank"
                   class="text-sm text-primary hover:underline truncate">
                    File saat ini: {{ basename($dokumentasi->file_path) }}
                </a>
            </div>
            @endif
            <input type="file" name="file_path" accept="image/*,audio/*,video/*,.pdf,.doc,.docx"
                   class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-4 py-3 cursor-pointer">
            @error('file_path') <span class="text-red-500 text-label-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        {{-- Thumbnail --}}
        <div class="mb-6">
            <label class="block text-label-md font-bold text-on-surface mb-2">
                Ganti Thumbnail / Sampul
                <span class="font-normal text-on-surface-variant text-sm ml-1">(Kosongkan untuk mempertahankan)</span>
            </label>
            @if($dokumentasi->thumbnail_url)
            <div class="mb-3">
                <img src="{{ $dokumentasi->thumbnail_url }}"
                     class="h-28 rounded-lg object-cover border border-outline-variant/30">
            </div>
            @endif
            <input type="file" name="thumbnail" accept="image/*"
                   class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-4 py-3 cursor-pointer">
            @error('thumbnail') <span class="text-red-500 text-label-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        {{-- URL Eksternal --}}
        <div class="mb-2">
            <label class="block text-label-md font-bold text-on-surface mb-2">
                URL Eksternal
                <span class="font-normal text-on-surface-variant text-sm ml-1">(Opsional)</span>
            </label>
            <input type="url" name="url_eksternal"
                   value="{{ old('url_eksternal', $dokumentasi->url_eksternal) }}"
                   placeholder="https://youtube.com/watch?v=..."
                   class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary transition-all">
            @error('url_eksternal') <span class="text-red-500 text-label-sm mt-1 block">{{ $message }}</span> @enderror
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex items-center gap-4 max-w-4xl">
        <button type="submit"
                class="bg-primary text-white font-bold px-8 py-3 rounded-lg hover:bg-primary/90 transition-colors shadow-md">
            Simpan Perubahan
        </button>
        <a href="{{ route('master.dokumentasi.index') }}"
           class="px-6 py-3 border border-outline-variant/50 rounded-lg text-on-surface-variant hover:bg-surface-container transition-colors">
            Batal
        </a>
    </div>
</form>

@endsection
