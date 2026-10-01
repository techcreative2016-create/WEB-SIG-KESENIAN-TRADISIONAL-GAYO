@extends('layouts.master')

@section('title', 'Tambah Banner Mobile')

@section('content')
<div class="mb-6 flex items-center gap-3">
    <a href="{{ route('master.mobile-hero.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-surface border border-outline-variant text-on-surface-variant hover:bg-surface-container transition-colors">
        <span class="material-symbols-outlined text-[20px]">arrow_back</span>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-on-surface">Tambah Banner Baru</h1>
        <p class="text-sm text-on-surface-variant mt-1">Isi detail konten hero yang akan tampil di aplikasi mobile.</p>
    </div>
</div>

<form action="{{ route('master.mobile-hero.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Utama -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-surface-lowest p-6 rounded-2xl shadow-sm border border-outline-variant">
                <h2 class="text-lg font-bold text-on-surface mb-4 border-b border-outline-variant pb-2">Informasi Utama</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1">Judul Utama (Title) <span class="text-error">*</span></label>
                        <input type="text" name="title" required value="{{ old('title') }}" class="w-full px-4 py-2 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-on-surface" placeholder="Contoh: Keagungan Burni Telong">
                        @error('title') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1">Sub Judul (Subtitle) <span class="text-error">*</span></label>
                        <textarea name="subtitle" required rows="2" class="w-full px-4 py-2 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-on-surface" placeholder="Deskripsi singkat di bawah judul utama">{{ old('subtitle') }}</textarea>
                        @error('subtitle') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-on-surface mb-1">Tag (Kategori)</label>
                            <input type="text" name="tag" value="{{ old('tag') }}" class="w-full px-4 py-2 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-on-surface" placeholder="Contoh: Wisata Alam">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-on-surface mb-1">Urutan Tampil (Order)</label>
                            <input type="number" name="urutan" value="{{ old('urutan', 0) }}" class="w-full px-4 py-2 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-on-surface">
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-surface-lowest p-6 rounded-2xl shadow-sm border border-outline-variant">
                <h2 class="text-lg font-bold text-on-surface mb-4 border-b border-outline-variant pb-2">Konten Detail (Bottom Sheet)</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1">Judul Seksi (Section Title)</label>
                        <input type="text" name="section_title" value="{{ old('section_title') }}" class="w-full px-4 py-2 bg-surface border border-outline-variant rounded-lg text-on-surface" placeholder="Contoh: Tentang Destinasi Ini">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1">Deskripsi Lengkap (Description)</label>
                        <textarea name="description" rows="5" class="w-full px-4 py-2 bg-surface border border-outline-variant rounded-lg text-on-surface" placeholder="Ceritakan detail tentang topik ini...">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1">Kutipan (Quote) <span class="text-on-surface-variant font-normal text-xs">- Opsional</span></label>
                        <textarea name="quote" rows="2" class="w-full px-4 py-2 bg-surface border border-outline-variant rounded-lg text-on-surface italic" placeholder="Contoh: Setiap langkah menuju puncak adalah...">{{ old('quote') }}</textarea>
                    </div>
                </div>
            </div>
            
            <!-- Dynamic Arrays (Stats, Info Rows, Tips) -->
            <div class="bg-surface-lowest p-6 rounded-2xl shadow-sm border border-outline-variant">
                <h2 class="text-lg font-bold text-on-surface mb-4 border-b border-outline-variant pb-2">Data Dinamis (List)</h2>
                
                <!-- Stats -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-semibold text-on-surface">Statistik (Stats) - Tampil di bawah judul</label>
                        <button type="button" onclick="addStatRow()" class="text-xs text-primary font-bold hover:underline">+ Tambah Stat</button>
                    </div>
                    <div id="stats-container" class="space-y-2">
                        <div class="flex gap-2 stat-row">
                            <input type="text" name="stats_icon[]" placeholder="Icon name (e.g., location)" class="w-1/3 px-3 py-2 bg-surface border border-outline-variant rounded-lg text-sm">
                            <input type="text" name="stats_label[]" placeholder="Label (e.g., Bener Meriah)" class="flex-1 px-3 py-2 bg-surface border border-outline-variant rounded-lg text-sm">
                            <button type="button" onclick="this.parentElement.remove()" class="p-2 text-error hover:bg-error/10 rounded-lg"><span class="material-symbols-outlined text-[18px]">close</span></button>
                        </div>
                    </div>
                </div>

                <!-- Info Rows -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-semibold text-on-surface">Informasi Tambahan (Info Rows)</label>
                        <button type="button" onclick="addInfoRow()" class="text-xs text-primary font-bold hover:underline">+ Tambah Info</button>
                    </div>
                    <div id="info-container" class="space-y-2">
                        <div class="flex gap-2 info-row">
                            <input type="text" name="info_icon[]" placeholder="Icon (e.g., time)" class="w-1/4 px-3 py-2 bg-surface border border-outline-variant rounded-lg text-sm">
                            <input type="text" name="info_label[]" placeholder="Label (e.g., Waktu)" class="w-1/3 px-3 py-2 bg-surface border border-outline-variant rounded-lg text-sm">
                            <input type="text" name="info_value[]" placeholder="Value (e.g., Pagi)" class="flex-1 px-3 py-2 bg-surface border border-outline-variant rounded-lg text-sm">
                            <button type="button" onclick="this.parentElement.remove()" class="p-2 text-error hover:bg-error/10 rounded-lg"><span class="material-symbols-outlined text-[18px]">close</span></button>
                        </div>
                    </div>
                </div>

                <!-- Tips -->
                <div>
                    <div class="mb-2">
                        <label class="block text-sm font-semibold text-on-surface">Judul Tips</label>
                        <input type="text" name="tips_title" value="{{ old('tips_title', 'Tips Berkunjung') }}" class="w-full px-4 py-2 bg-surface border border-outline-variant rounded-lg text-on-surface mb-2" placeholder="Judul blok tips">
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-semibold text-on-surface">Daftar Tips</label>
                        <button type="button" onclick="addTipRow()" class="text-xs text-primary font-bold hover:underline">+ Tambah Tips</button>
                    </div>
                    <div id="tips-container" class="space-y-2">
                        <div class="flex gap-2 tip-row">
                            <input type="text" name="tips[]" placeholder="Tuliskan tip..." class="flex-1 px-3 py-2 bg-surface border border-outline-variant rounded-lg text-sm">
                            <button type="button" onclick="this.parentElement.remove()" class="p-2 text-error hover:bg-error/10 rounded-lg"><span class="material-symbols-outlined text-[18px]">close</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Sidebar -->
        <div class="space-y-6">
            <!-- Gambar Utama -->
            <div class="bg-surface-lowest p-6 rounded-2xl shadow-sm border border-outline-variant">
                <h2 class="text-lg font-bold text-on-surface mb-4 border-b border-outline-variant pb-2">Gambar Banner <span class="text-error">*</span></h2>
                
                <div class="mt-2 text-center">
                    <div class="w-full aspect-[4/5] rounded-xl border-2 border-dashed border-outline-variant bg-surface-container-low flex flex-col items-center justify-center relative overflow-hidden group">
                        <img id="preview-image" src="" alt="" class="absolute inset-0 w-full h-full object-cover hidden">
                        <div class="text-center group-hover:opacity-100 transition-opacity p-4 relative z-10" id="upload-placeholder">
                            <span class="material-symbols-outlined text-4xl text-on-surface-variant/50">add_photo_alternate</span>
                            <p class="text-xs text-on-surface-variant mt-2">Klik untuk upload (Max 2MB)</p>
                            <p class="text-[10px] text-on-surface-variant/60 mt-1">Rekomendasi rasio portrait untuk mobile</p>
                        </div>
                        <input type="file" name="image" id="image-upload" required accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" onchange="previewImage(this)">
                    </div>
                    @error('image') <p class="text-error text-xs mt-2 text-left">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Pengaturan Status -->
            <div class="bg-surface-lowest p-6 rounded-2xl shadow-sm border border-outline-variant">
                <h2 class="text-lg font-bold text-on-surface mb-4 border-b border-outline-variant pb-2">Status Publikasi</h2>
                
                <label class="flex items-center gap-3 cursor-pointer group">
                    <div class="relative">
                        <input type="checkbox" name="is_active" value="1" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-surface-variant peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                    </div>
                    <div>
                        <span class="font-semibold text-on-surface group-hover:text-primary transition-colors text-sm">Aktif (Tampilkan)</span>
                        <p class="text-xs text-on-surface-variant mt-0.5">Tampilkan banner ini di halaman utama aplikasi.</p>
                    </div>
                </label>

                <div class="mt-8 pt-4 border-t border-outline-variant flex justify-end gap-3">
                    <a href="{{ route('master.mobile-hero.index') }}" class="px-5 py-2.5 rounded-lg border border-outline-variant text-on-surface font-semibold text-sm hover:bg-surface-container transition-colors">Batal</a>
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-primary text-white font-semibold text-sm hover:bg-primary/90 transition-colors shadow-md flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">save</span> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-image').src = e.target.result;
                document.getElementById('preview-image').classList.remove('hidden');
                document.getElementById('upload-placeholder').classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function addStatRow() {
        const container = document.getElementById('stats-container');
        const row = document.createElement('div');
        row.className = 'flex gap-2 stat-row';
        row.innerHTML = `
            <input type="text" name="stats_icon[]" placeholder="Icon name" class="w-1/3 px-3 py-2 bg-surface border border-outline-variant rounded-lg text-sm">
            <input type="text" name="stats_label[]" placeholder="Label" class="flex-1 px-3 py-2 bg-surface border border-outline-variant rounded-lg text-sm">
            <button type="button" onclick="this.parentElement.remove()" class="p-2 text-error hover:bg-error/10 rounded-lg"><span class="material-symbols-outlined text-[18px]">close</span></button>
        `;
        container.appendChild(row);
    }

    function addInfoRow() {
        const container = document.getElementById('info-container');
        const row = document.createElement('div');
        row.className = 'flex gap-2 info-row';
        row.innerHTML = `
            <input type="text" name="info_icon[]" placeholder="Icon" class="w-1/4 px-3 py-2 bg-surface border border-outline-variant rounded-lg text-sm">
            <input type="text" name="info_label[]" placeholder="Label" class="w-1/3 px-3 py-2 bg-surface border border-outline-variant rounded-lg text-sm">
            <input type="text" name="info_value[]" placeholder="Value" class="flex-1 px-3 py-2 bg-surface border border-outline-variant rounded-lg text-sm">
            <button type="button" onclick="this.parentElement.remove()" class="p-2 text-error hover:bg-error/10 rounded-lg"><span class="material-symbols-outlined text-[18px]">close</span></button>
        `;
        container.appendChild(row);
    }

    function addTipRow() {
        const container = document.getElementById('tips-container');
        const row = document.createElement('div');
        row.className = 'flex gap-2 tip-row';
        row.innerHTML = `
            <input type="text" name="tips[]" placeholder="Tuliskan tip..." class="flex-1 px-3 py-2 bg-surface border border-outline-variant rounded-lg text-sm">
            <button type="button" onclick="this.parentElement.remove()" class="p-2 text-error hover:bg-error/10 rounded-lg"><span class="material-symbols-outlined text-[18px]">close</span></button>
        `;
        container.appendChild(row);
    }
</script>
@endsection
