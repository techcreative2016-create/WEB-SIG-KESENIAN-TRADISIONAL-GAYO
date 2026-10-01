{{--
    Partial form reusable untuk Create dan Edit Sanggar.
    Di-include dari: admin/sanggars/create.blade.php dan edit.blade.php
    Variabel: $sanggar (opsional, null saat create)
--}}

@push('styles')
<style>
    .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
    .form-grid .full { grid-column:1/-1; }

    .form-group { display:flex; flex-direction:column; gap:5px; }
    .form-label { font-size:0.8rem; font-weight:600; color:#333; }
    .form-label .req { color:#e53935; margin-left:2px; }

    .form-control {
        padding:9px 12px; border:1px solid #ddd; border-radius:8px;
        font-size:0.85rem; font-family:inherit; transition:border 0.2s;
        background:#fff;
    }
    .form-control:focus { outline:none; border-color:#5c6bc0; box-shadow:0 0 0 3px rgba(92,107,192,0.1); }
    .form-control.error { border-color:#e53935; }

    .form-error { font-size:0.72rem; color:#e53935; margin-top:2px; }
    .form-hint  { font-size:0.72rem; color:#888; margin-top:2px; }

    .section-card {
        background:#fff; border-radius:12px;
        box-shadow:0 1px 4px rgba(0,0,0,0.06);
        margin-bottom:20px; overflow:hidden;
    }

    .section-head {
        padding:14px 20px; border-bottom:1px solid #f0f0f0;
        display:flex; align-items:center; gap:8px;
    }

    .section-head-icon {
        width:30px; height:30px; border-radius:8px;
        background:#ede7f6; color:#5c6bc0;
        display:flex; align-items:center; justify-content:center;
        font-size:0.85rem;
    }

    .section-title { font-size:0.9rem; font-weight:700; color:#1a1a2e; }
    .section-body  { padding:20px; }

    /* Preview foto */
    .foto-preview-wrap { position:relative; display:inline-block; }
    #preview-foto-utama {
        width:120px; height:120px; border-radius:10px;
        object-fit:cover; border:2px dashed #ddd;
        background:#f5f5f5; cursor:pointer;
    }

    /* Peta koordinat */
    #peta-koordinat {
        width:100%; height:300px; border-radius:10px;
        border:1px solid #ddd; overflow:hidden;
    }

    .koordinat-display {
        display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-top:10px;
    }

    /* Galeri upload area */
    .galeri-dropzone {
        border:2px dashed #c5cae9; border-radius:10px;
        padding:20px; text-align:center; cursor:pointer;
        background:#f8f9ff; transition:border 0.2s;
    }

    .galeri-dropzone:hover { border-color:#5c6bc0; background:#ede7f6; }
    .galeri-dropzone p { font-size:0.82rem; color:#777; margin-top:8px; }

    .galeri-preview-grid {
        display:grid; grid-template-columns:repeat(auto-fill, minmax(90px, 1fr));
        gap:8px; margin-top:12px;
    }

    .galeri-preview-item {
        position:relative; border-radius:8px; overflow:hidden;
        aspect-ratio:1; background:#f0f0f0;
    }

    .galeri-preview-item img {
        width:100%; height:100%; object-fit:cover; display:block;
    }

    .galeri-preview-remove {
        position:absolute; top:3px; right:3px;
        background:rgba(0,0,0,0.55); color:#fff;
        border:none; border-radius:50%; width:20px; height:20px;
        font-size:0.65rem; cursor:pointer; display:flex;
        align-items:center; justify-content:center;
    }

    /* Tombol aksi form */
    .form-actions {
        display:flex; gap:10px; padding:16px 20px;
        border-top:1px solid #f0f0f0; background:#fafafa;
    }

    .btn { display:inline-flex; align-items:center; gap:6px; padding:9px 20px; border-radius:8px; font-size:0.83rem; font-weight:600; cursor:pointer; border:none; text-decoration:none; transition:all 0.2s; }
    .btn-primary { background:#5c6bc0; color:#fff; }
    .btn-primary:hover { background:#3949ab; }
    .btn-secondary { background:#f0f0f0; color:#555; }
    .btn-secondary:hover { background:#e0e0e0; }
</style>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    /* Fix z-index issue with leaflet inside modal/form */
    .leaflet-container { z-index: 1; }
</style>
@endpush

{{-- ====================== INFORMASI DASAR ====================== --}}
<div class="section-card">
    <div class="section-head">
        <div class="section-head-icon"><i class="fas fa-info-circle"></i></div>
        <div class="section-title">Informasi Dasar</div>
    </div>
    <div class="section-body">
        <div class="form-grid">

            {{-- Nama Sanggar --}}
            <div class="form-group full">
                <label class="form-label" for="nama_sanggar">
                    Nama Sanggar <span class="req">*</span>
                </label>
                <input type="text" id="nama_sanggar" name="nama_sanggar"
                       class="form-control {{ $errors->has('nama_sanggar') ? 'error' : '' }}"
                       value="{{ old('nama_sanggar', $sanggar->nama_sanggar ?? '') }}"
                       placeholder="Contoh: Sanggar Seni Kerawang Gayo"
                       required>
                @error('nama_sanggar')
                    <span class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>

            {{-- Slug (auto-generated) --}}
            <div class="form-group full">
                <label class="form-label" for="slug">Slug URL</label>
                <input type="text" id="slug" name="slug"
                       class="form-control {{ $errors->has('slug') ? 'error' : '' }}"
                       value="{{ old('slug', $sanggar->slug ?? '') }}"
                       placeholder="Otomatis diisi dari nama sanggar">
                <span class="form-hint">Contoh: sanggar-seni-kerawang-gayo (hanya huruf kecil, angka, strip)</span>
                @error('slug') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            {{-- Kategori --}}
            <div class="form-group">
                <label class="form-label" for="kategori">
                    Kategori Kesenian <span class="req">*</span>
                </label>
                <select id="kategori" name="kategori"
                        class="form-control {{ $errors->has('kategori') ? 'error' : '' }}" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach(['Tari', 'Musik', 'Kriya'] as $kat)
                        <option value="{{ $kat }}"
                            {{ old('kategori', $sanggar->kategori ?? '') === $kat ? 'selected' : '' }}>
                            {{ $kat }}
                        </option>
                    @endforeach
                </select>
                @error('kategori') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            {{-- Status --}}
            <div class="form-group">
                <label class="form-label" for="status">
                    Status Sanggar <span class="req">*</span>
                </label>
                <select id="status" name="status"
                        class="form-control {{ $errors->has('status') ? 'error' : '' }}" required>
                    <option value="Aktif" {{ old('status', $sanggar->status ?? 'Aktif') === 'Aktif' ? 'selected' : '' }}>✅ Aktif</option>
                    <option value="Vakum" {{ old('status', $sanggar->status ?? '') === 'Vakum' ? 'selected' : '' }}>⛔ Vakum</option>
                </select>
                @error('status') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            {{-- Deskripsi --}}
            <div class="form-group full">
                <label class="form-label" for="deskripsi">Deskripsi Sanggar</label>
                <textarea id="deskripsi" name="deskripsi" rows="4"
                          class="form-control {{ $errors->has('deskripsi') ? 'error' : '' }}"
                          placeholder="Ceritakan sejarah, kegiatan, dan jenis kesenian yang dipelajari di sanggar ini...">{{ old('deskripsi', $sanggar->deskripsi ?? '') }}</textarea>
                @error('deskripsi') <span class="form-error">{{ $message }}</span> @enderror
            </div>

        </div>
    </div>
</div>

{{-- ====================== PIMPINAN SANGGAR ====================== --}}
<div class="section-card">
    <div class="section-head">
        <div class="section-head-icon"><i class="fas fa-user-tie"></i></div>
        <div class="section-title">Pimpinan Sanggar</div>
    </div>
    <div class="section-body">
        
        <div class="form-grid">
            {{-- Pimpinan --}}
            <div class="form-group full">
                <label class="form-label" for="nama_pimpinan">Nama Pimpinan</label>
                <input type="text" id="nama_pimpinan" name="nama_pimpinan"
                       class="form-control"
                       value="{{ old('nama_pimpinan', $sanggar->nama_pimpinan ?? '') }}"
                       placeholder="Contoh: Tengku Hamzah">
                @error('nama_pimpinan') <span class="form-error">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>
</div>

{{-- ====================== LOKASI & KOORDINAT ====================== --}}
<div class="section-card">
    <div class="section-head">
        <div class="section-head-icon"><i class="fas fa-map-marker-alt"></i></div>
        <div class="section-title">Lokasi & Koordinat GPS</div>
    </div>
    <div class="section-body">

        {{-- Alamat --}}
        <div class="form-group" style="margin-bottom:14px">
            <label class="form-label" for="alamat">
                Alamat Lengkap <span class="req">*</span>
            </label>
            <textarea id="alamat" name="alamat" rows="2"
                      class="form-control {{ $errors->has('alamat') ? 'error' : '' }}"
                      placeholder="Contoh: Jl. Bejeren No.12, Blang Bebangka, Bener Meriah" required>{{ old('alamat', $sanggar->alamat ?? '') }}</textarea>
            @error('alamat') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-grid" style="margin-bottom:14px">
            {{-- Kabupaten --}}
            <div class="form-group">
                <label class="form-label" for="kabupaten">
                    Kabupaten <span class="req">*</span>
                </label>
                <select id="kabupaten" name="kabupaten"
                        class="form-control {{ $errors->has('kabupaten') ? 'error' : '' }}" required>
                    <option value="">-- Pilih Kabupaten --</option>
                    @foreach(['Bener Meriah', 'Aceh Tengah'] as $kab)
                        <option value="{{ $kab }}"
                            {{ old('kabupaten', $sanggar->kabupaten ?? '') === $kab ? 'selected' : '' }}>
                            {{ $kab }}
                        </option>
                    @endforeach
                </select>
                @error('kabupaten') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            {{-- Kecamatan --}}
            <div class="form-group">
                <label class="form-label" for="kecamatan">Kecamatan</label>
                <input type="text" id="kecamatan" name="kecamatan"
                       class="form-control"
                       value="{{ old('kecamatan', $sanggar->kecamatan ?? '') }}"
                       placeholder="Contoh: Permata">
            </div>
        </div>

        {{-- Peta klik untuk pilih koordinat --}}
        <label class="form-label" style="margin-bottom:8px;display:block">
            <i class="fas fa-crosshairs" style="color:#5c6bc0"></i>
            Klik peta untuk menentukan koordinat
        </label>
        <div id="peta-koordinat"></div>
        <p style="font-size:0.72rem;color:#888;margin-top:6px">
            💡 Klik atau geser pin di peta untuk mendapatkan koordinat yang akurat.
        </p>

        {{-- Input koordinat (diisi otomatis dari klik peta) --}}
        <div class="koordinat-display">
            <div class="form-group">
                <label class="form-label" for="latitude">
                    Latitude <span class="req">*</span>
                </label>
                <input type="text" id="latitude" name="latitude"
                       class="form-control {{ $errors->has('latitude') ? 'error' : '' }}"
                       value="{{ old('latitude', $sanggar->latitude ?? '') }}"
                       placeholder="Contoh: 4.7234567" required>
                @error('latitude') <span class="form-error">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="longitude">
                    Longitude <span class="req">*</span>
                </label>
                <input type="text" id="longitude" name="longitude"
                       class="form-control {{ $errors->has('longitude') ? 'error' : '' }}"
                       value="{{ old('longitude', $sanggar->longitude ?? '') }}"
                       placeholder="Contoh: 96.8345678" required>
                @error('longitude') <span class="form-error">{{ $message }}</span> @enderror
            </div>
        </div>

    </div>
</div>

{{-- ====================== KONTAK ====================== --}}
<div class="section-card">
    <div class="section-head">
        <div class="section-head-icon"><i class="fas fa-address-book"></i></div>
        <div class="section-title">Informasi Kontak</div>
    </div>
    <div class="section-body">
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label" for="no_telepon">No. Telepon / WhatsApp</label>
                <input type="text" id="no_telepon" name="no_telepon"
                       class="form-control" placeholder="Contoh: 0812-3456-7890"
                       value="{{ old('no_telepon', $sanggar->no_telepon ?? '') }}">
                @error('no_telepon') <span class="form-error">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input type="email" id="email" name="email"
                       class="form-control" placeholder="Contoh: sanggar@email.com"
                       value="{{ old('email', $sanggar->email ?? '') }}">
                @error('email') <span class="form-error">{{ $message }}</span> @enderror
            </div>
            <div class="form-group full">
                <label class="form-label" for="instagram">Username Instagram</label>
                <div style="position:relative">
                    <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#aaa">@</span>
                    <input type="text" id="instagram" name="instagram"
                           class="form-control" style="padding-left:24px"
                           placeholder="username_instagram"
                           value="{{ old('instagram', $sanggar->instagram ?? '') }}">
                </div>
                @error('instagram') <span class="form-error">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>
</div>

{{-- ====================== FOTO UTAMA ====================== --}}
<div class="section-card">
    <div class="section-head">
        <div class="section-head-icon"><i class="fas fa-image"></i></div>
        <div class="section-title">Foto Utama</div>
    </div>
    <div class="section-body" style="display:flex;gap:20px;align-items:flex-start">

        <div class="foto-preview-wrap" onclick="document.getElementById('foto_utama').click()">
            <img id="preview-foto-utama"
                 src="{{ $sanggar->foto_utama_url ?? asset('images/placeholder-sanggar.jpg') }}"
                 alt="Preview foto utama">
            <div style="position:absolute;bottom:4px;right:4px;background:#5c6bc0;color:#fff;border-radius:50%;width:24px;height:24px;display:flex;align-items:center;justify-content:center;font-size:0.7rem">
                <i class="fas fa-camera"></i>
            </div>
        </div>

        <div style="flex:1">
            <input type="file" id="foto_utama" name="foto_utama"
                   accept="image/jpg,image/jpeg,image/png,image/webp"
                   style="display:none"
                   onchange="previewFotoUtama(this)">

            <label class="form-label">Klik foto untuk mengubah</label>
            <p class="form-hint" style="margin-top:4px">
                Format: JPG, PNG, WebP. Maks 3MB.<br>
                Rekomendasi ukuran: 800×600 pixel.
            </p>

            @if(isset($sanggar) && $sanggar->foto_utama)
                <p class="form-hint" style="margin-top:6px;color:#5c6bc0">
                    <i class="fas fa-check-circle"></i> Foto utama sudah ada.
                    Upload baru untuk mengganti.
                </p>
            @endif

            @error('foto_utama') <span class="form-error">{{ $message }}</span> @enderror
        </div>
    </div>
</div>

{{-- ====================== GALERI FOTO ====================== --}}
<div class="section-card">
    <div class="section-head">
        <div class="section-head-icon"><i class="fas fa-images"></i></div>
        <div class="section-title">Galeri Foto Tambahan</div>
    </div>
    <div class="section-body">

        {{-- Galeri yang sudah ada (edit mode) --}}
        @if(isset($sanggar) && $sanggar->galeri->count() > 0)
            <div class="galeri-preview-grid" id="grid-galeri-existing">
                @foreach($sanggar->galeri as $foto)
                <div class="galeri-preview-item" id="galeri-item-{{ $foto->id }}">
                    <img src="{{ $foto->url }}" alt="{{ $foto->judul ?? 'Foto galeri' }}">

                    {{-- Tombol hapus foto --}}
                    <button class="galeri-preview-remove btn-action-galeri" type="button" title="Hapus foto"
                            data-action="{{ route('admin.sanggars.galeri.hapus', [$sanggar, $foto]) }}"
                            data-method="DELETE"
                            data-confirm="Hapus foto ini?">
                        <i class="fas fa-times"></i>
                    </button>

                    {{-- Indikator foto utama --}}
                    @if($foto->is_utama)
                        <div style="position:absolute;bottom:3px;left:3px;background:#5c6bc0;color:#fff;border-radius:4px;padding:1px 5px;font-size:0.6rem;font-weight:700">UTAMA</div>
                    @else
                        <button type="button" class="btn-action-galeri"
                                style="position:absolute;bottom:3px;left:3px;background:rgba(0,0,0,0.45);color:#fff;border:none;border-radius:4px;padding:1px 5px;font-size:0.6rem;cursor:pointer"
                                title="Jadikan foto utama"
                                data-action="{{ route('admin.sanggars.galeri.utama', [$sanggar, $foto]) }}"
                                data-method="PATCH">
                            ★ Utama
                        </button>
                    @endif
                </div>
                @endforeach
            </div>
            <hr style="margin:16px 0;border:none;border-top:1px solid #f0f0f0">
        @endif

        {{-- Dropzone upload foto baru --}}
        <div class="galeri-dropzone" id="galeri-dropzone"
             onclick="document.getElementById('galeri-input').click()">
            <i class="fas fa-cloud-upload-alt" style="font-size:2rem;color:#9fa8da"></i>
            <p>Klik atau seret foto ke sini untuk upload galeri baru</p>
            <p style="font-size:0.7rem;color:#aaa">JPG, PNG, WebP — Maks 5MB per foto — Maks 10 foto sekaligus</p>
        </div>

        <input type="file" id="galeri-input" name="galeri[]"
               accept="image/*" multiple style="display:none"
               onchange="previewGaleri(this)">

        {{-- Preview galeri baru yang dipilih --}}
        <div class="galeri-preview-grid" id="galeri-preview-grid"></div>

        @error('galeri') <span class="form-error">{{ $message }}</span> @enderror
        @error('galeri.*') <span class="form-error">{{ $message }}</span> @enderror
    </div>
</div>

@push('scripts')
<script>
// ============================================================================
// JS ACTION GALERI DENGAN DYNAMIC FORM (Menghindari Nested Form di HTML)
// ============================================================================
document.querySelectorAll('.btn-action-galeri').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const action = this.getAttribute('data-action');
        const method = this.getAttribute('data-method');
        const confirmMsg = this.getAttribute('data-confirm');
        
        if (confirmMsg && !confirm(confirmMsg)) {
            return;
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = action;
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = method;
        form.appendChild(methodInput);
        
        document.body.appendChild(form);
        form.submit();
    });
});

// Preview Foto Utama
// ============================================================================
function previewFotoUtama(input) {
    if (!input.files.length) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('preview-foto-utama').src = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);
}

// ============================================================================
// Preview Galeri (multiple)
// ============================================================================
function previewGaleri(input) {
    const grid = document.getElementById('galeri-preview-grid');
    grid.innerHTML = '';

    Array.from(input.files).forEach((file, i) => {
        const reader = new FileReader();
        reader.onload = e => {
            const item = document.createElement('div');
            item.className = 'galeri-preview-item';
            item.innerHTML = `
                <img src="${e.target.result}" alt="Preview ${i+1}">
                <div style="position:absolute;bottom:3px;left:3px;background:rgba(0,0,0,0.5);color:#fff;font-size:0.6rem;padding:1px 4px;border-radius:3px">${i+1}</div>
            `;
            grid.appendChild(item);
        };
        reader.readAsDataURL(file);
    });
}

// ============================================================================
// Auto-generate slug dari nama sanggar
// ============================================================================
document.getElementById('nama_sanggar')?.addEventListener('input', function () {
    const slugInput = document.getElementById('slug');
    // Hanya auto-fill jika slug masih kosong (bukan edit yang sudah ada)
    if (!slugInput.dataset.manual) {
        slugInput.value = this.value
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
    }
});

document.getElementById('slug')?.addEventListener('input', function () {
    this.dataset.manual = 'true'; // Tandai: slug diisi manual
});

// ============================================================================
// Peta Koordinat (Leaflet) — Klik peta untuk isi koordinat
// ============================================================================
function initPetaKoordinat() {
    const defaultLat = parseFloat(document.getElementById('latitude').value)  || 4.7200;
    const defaultLng = parseFloat(document.getElementById('longitude').value) || 96.8500;

    const peta = L.map('peta-koordinat').setView([defaultLat, defaultLng], 13);
    
    L.tileLayer('https://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
        attribution: '&copy; Google Maps'
    }).addTo(peta);

    // Marker yang bisa di-drag
    const marker = L.marker([defaultLat, defaultLng], { 
        draggable: true,
        title: 'Geser untuk ubah lokasi'
    }).addTo(peta);

    // Fungsi update input koordinat
    function updateKoordinat(latLng) {
        document.getElementById('latitude').value  = latLng.lat.toFixed(7);
        document.getElementById('longitude').value = latLng.lng.toFixed(7);
    }

    // Klik di peta → pindah marker
    peta.on('click', e => {
        marker.setLatLng(e.latlng);
        updateKoordinat(e.latlng);
    });

    // Drag marker → update koordinat
    marker.on('dragend', e => {
        updateKoordinat(marker.getLatLng());
    });

    // Input manual koordinat → pindah marker
    ['latitude', 'longitude'].forEach(id => {
        document.getElementById(id)?.addEventListener('change', () => {
            const lat = parseFloat(document.getElementById('latitude').value);
            const lng = parseFloat(document.getElementById('longitude').value);
            if (!isNaN(lat) && !isNaN(lng)) {
                const pos = [lat, lng];
                marker.setLatLng(pos);
                peta.panTo(pos);
            }
        });
    });

    // Fix map loading issue in hidden elements (like tabs/modals)
    setTimeout(() => { peta.invalidateSize(); }, 500);
}

document.addEventListener("DOMContentLoaded", function() {
    initPetaKoordinat();
});
</script>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endpush
