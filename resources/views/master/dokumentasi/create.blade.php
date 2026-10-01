@extends(auth()->user()->role === 'master' ? 'layouts.master' : 'layouts.admin')

@section('title', 'Tambah Dokumentasi Seni')

@section('content')

<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-headline-lg font-headline-lg font-bold text-primary">Tambah Dokumentasi Seni</h1>
        <p class="text-on-surface-variant">Upload arsip dokumentasi budaya Gayo ke halaman publik.</p>
    </div>
    <a href="{{ route('master.dokumentasi.index') }}"
       class="flex items-center gap-2 bg-surface-container-high text-on-surface px-4 py-2 rounded-lg hover:bg-surface-dim transition-colors">
        <span class="material-symbols-outlined text-[20px]">arrow_back</span>
        <span class="font-bold">Kembali</span>
    </a>
</div>

@if($errors->any())
<div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 space-y-1">
    @foreach($errors->all() as $err)
        <p class="flex items-center gap-2 text-sm">
            <span class="material-symbols-outlined text-sm">error</span> {{ $err }}
        </p>
    @endforeach
</div>
@endif

<form action="{{ route('master.dokumentasi.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- Informasi Dasar --}}
    <div class="glass-panel rounded-2xl border border-outline-variant/30 p-8 max-w-4xl mb-6">

        <div class="mb-6">
            <label class="block text-label-md font-bold text-on-surface mb-2">
                Judul Dokumentasi <span class="text-red-500">*</span>
            </label>
            <input type="text" name="judul" value="{{ old('judul') }}" required
                   placeholder="Contoh: Rekaman Tari Saman 1987"
                   class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('judul') border-red-400 @enderror">
            @error('judul') <span class="text-red-500 text-label-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-label-md font-bold text-on-surface mb-2">
                    Tipe Dokumen <span class="text-red-500">*</span>
                </label>
                <select name="tipe" required
                        class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('tipe') border-red-400 @enderror">
                    <option value="">-- Pilih Tipe --</option>
                    @foreach(['Foto','Video','Naskah','Audio'] as $t)
                        <option value="{{ $t }}" {{ old('tipe') === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
                @error('tipe') <span class="text-red-500 text-label-sm mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-label-md font-bold text-on-surface mb-2">
                    Tanggal Dokumen <span class="text-red-500">*</span>
                </label>
                <input type="date" name="tanggal_dokumen" value="{{ old('tanggal_dokumen') }}" required
                       class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('tanggal_dokumen') border-red-400 @enderror">
                @error('tanggal_dokumen') <span class="text-red-500 text-label-sm mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-label-md font-bold text-on-surface mb-2">Status Publikasi</label>
            <select name="status"
                    class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                <option value="Aktif" {{ old('status', 'Aktif') === 'Aktif' ? 'selected' : '' }}>Aktif (tampil di halaman publik)</option>
                <option value="Arsip" {{ old('status') === 'Arsip' ? 'selected' : '' }}>Arsip (tersembunyi dari publik)</option>
            </select>
        </div>

        <div class="mb-2">
            <label class="block text-label-md font-bold text-on-surface mb-2">Deskripsi</label>
            <textarea name="deskripsi" rows="4"
                      placeholder="Keterangan singkat tentang dokumentasi ini..."
                      class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary transition-all resize-none">{{ old('deskripsi') }}</textarea>
        </div>
    </div>

    {{-- File & Media --}}
    <div class="glass-panel rounded-2xl border border-outline-variant/30 p-8 max-w-4xl mb-6">
        <h2 class="text-title-md font-bold text-on-surface mb-6 pb-3 border-b border-outline-variant/30">
            File & Media
        </h2>

        <div class="mb-6">
            <label class="block text-label-md font-bold text-on-surface mb-2">
                File Utama
                <span class="font-normal text-on-surface-variant text-sm ml-1">(Foto, Audio, Video, atau PDF — maks. 50MB)</span>
            </label>
            <input type="file" name="file_path" accept="image/*,audio/*,video/*,.pdf,.doc,.docx"
                   class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary transition-all cursor-pointer">
            @error('file_path') <span class="text-red-500 text-label-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="mb-6">
            <label class="block text-label-md font-bold text-on-surface mb-2">
                Gambar Thumbnail / Sampul
                <span class="font-normal text-on-surface-variant text-sm ml-1">(JPG/PNG/WebP — maks. 5MB. Wajib untuk Audio, Naskah, dan Video)</span>
            </label>
            <input type="file" name="thumbnail" accept="image/*"
                   class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary transition-all cursor-pointer">
            @error('thumbnail') <span class="text-red-500 text-label-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="mb-2">
            <label class="block text-label-md font-bold text-on-surface mb-2">
                URL Eksternal
                <span class="font-normal text-on-surface-variant text-sm ml-1">(Opsional — link YouTube, Google Drive, dll.)</span>
            </label>
            <input type="url" name="url_eksternal" value="{{ old('url_eksternal') }}"
                   placeholder="https://youtube.com/watch?v=..."
                   class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary transition-all">
            @error('url_eksternal') <span class="text-red-500 text-label-sm mt-1 block">{{ $message }}</span> @enderror
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex items-center gap-4 max-w-4xl">
        <button type="submit"
                class="bg-primary text-white font-bold px-8 py-3 rounded-lg hover:bg-primary/90 transition-colors shadow-md">
            Simpan Dokumentasi
        </button>
        <a href="{{ route('master.dokumentasi.index') }}"
           class="px-6 py-3 border border-outline-variant/50 rounded-lg text-on-surface-variant hover:bg-surface-container transition-colors">
            Batal
        </a>
    </div>
</form>

@endsection
