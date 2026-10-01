{{--
    Form partial reusable untuk Create dan Edit Toko.
    Variabel: $toko (null saat create)
--}}

@push('styles')
<style>
    .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
    .form-grid .full{grid-column:1/-1}
    .form-group{display:flex;flex-direction:column;gap:5px}
    .form-label{font-size:.8rem;font-weight:600;color:#333}
    .form-label .req{color:#e53935;margin-left:2px}
    .form-control{padding:9px 12px;border:1px solid #ddd;border-radius:8px;font-size:.85rem;font-family:inherit;transition:border .2s;background:#fff}
    .form-control:focus{outline:none;border-color:#e65100;box-shadow:0 0 0 3px rgba(230,81,0,.1)}
    .form-control.error{border-color:#e53935}
    .form-error{font-size:.72rem;color:#e53935;margin-top:2px}
    .form-hint{font-size:.72rem;color:#888;margin-top:2px}
    .section-card{background:#fff;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,.06);margin-bottom:20px;overflow:hidden}
    .section-head{padding:14px 20px;border-bottom:1px solid #f0f0f0;display:flex;align-items:center;gap:8px}
    .section-head-icon{width:30px;height:30px;border-radius:8px;background:#fff3e0;color:#e65100;display:flex;align-items:center;justify-content:center;font-size:.85rem}
    .section-title{font-size:.9rem;font-weight:700;color:#1a1a2e}
    .section-body{padding:20px}
    .foto-preview-wrap{position:relative;display:inline-block}
    #preview-foto-utama{width:120px;height:120px;border-radius:10px;object-fit:cover;border:2px dashed #ddd;background:#f5f5f5;cursor:pointer}
    #peta-koordinat{width:100%;height:280px;border-radius:10px;border:1px solid #ddd;overflow:hidden}
    .koordinat-display{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:10px}
    .galeri-dropzone{border:2px dashed #ffe0b2;border-radius:10px;padding:20px;text-align:center;cursor:pointer;background:#fffde7;transition:border .2s}
    .galeri-dropzone:hover{border-color:#e65100;background:#fff3e0}
    .galeri-dropzone p{font-size:.82rem;color:#888;margin-top:8px}
    .galeri-preview-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(90px,1fr));gap:8px;margin-top:12px}
    .galeri-preview-item{position:relative;border-radius:8px;overflow:hidden;aspect-ratio:1;background:#f0f0f0}
    .galeri-preview-item img{width:100%;height:100%;object-fit:cover;display:block}
    .galeri-preview-remove{position:absolute;top:3px;right:3px;background:rgba(0,0,0,.55);color:#fff;border:none;border-radius:50%;width:20px;height:20px;font-size:.65rem;cursor:pointer;display:flex;align-items:center;justify-content:center}
    .form-actions{display:flex;gap:10px;padding:16px 20px;border-top:1px solid #f0f0f0;background:#fafafa}
    .btn{display:inline-flex;align-items:center;gap:6px;padding:9px 20px;border-radius:8px;font-size:.83rem;font-weight:600;cursor:pointer;border:none;text-decoration:none;transition:all .2s}
    .btn-primary{background:#e65100;color:#fff}.btn-primary:hover{background:#bf360c}
    .btn-primary{background:#e65100;color:#fff}.btn-primary:hover{background:#bf360c}
    .btn-secondary{background:#f0f0f0;color:#555}.btn-secondary:hover{background:#e0e0e0}
</style>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    /* Fix z-index issue with leaflet inside modal/form */
    .leaflet-container { z-index: 1; }
</style>
@endpush

{{-- Informasi Dasar --}}
<div class="section-card">
    <div class="section-head">
        <div class="section-head-icon"><i class="fas fa-store"></i></div>
        <div class="section-title">Informasi Toko</div>
    </div>
    <div class="section-body">
        <div class="form-grid">
            <div class="form-group full">
                <label class="form-label" for="nama_toko">Nama Toko <span class="req">*</span></label>
                <input type="text" id="nama_toko" name="nama_toko"
                       class="form-control {{ $errors->has('nama_toko') ? 'error' : '' }}"
                       value="{{ old('nama_toko', $toko->nama_toko ?? '') }}"
                       placeholder="Contoh: Toko Kerawang Bungong Jaro" required>
                @error('nama_toko') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group full">
                <label class="form-label" for="slug">Slug URL</label>
                <input type="text" id="slug" name="slug"
                       class="form-control {{ $errors->has('slug') ? 'error' : '' }}"
                       value="{{ old('slug', $toko->slug ?? '') }}"
                       placeholder="Otomatis diisi dari nama toko">
                @error('slug') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="jenis_produk">Jenis Produk <span class="req">*</span></label>
                <select id="jenis_produk" name="jenis_produk"
                        class="form-control {{ $errors->has('jenis_produk') ? 'error' : '' }}" required>
                    <option value="">-- Pilih Jenis Produk --</option>
                    @foreach(['Kerawang Gayo','Alat Musik','Pakaian Adat','Kerajinan Tangan','Campuran'] as $j)
                        <option value="{{ $j }}"
                            {{ old('jenis_produk', $toko->jenis_produk ?? '') === $j ? 'selected' : '' }}>
                            {{ $j }}
                        </option>
                    @endforeach
                </select>
                @error('jenis_produk') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="status">Status Toko <span class="req">*</span></label>
                <select id="status" name="status"
                        class="form-control {{ $errors->has('status') ? 'error' : '' }}" required>
                    <option value="Buka"  {{ old('status', $toko->status ?? 'Buka')  === 'Buka'  ? 'selected' : '' }}>🟢 Buka</option>
                    <option value="Tutup" {{ old('status', $toko->status ?? '') === 'Tutup' ? 'selected' : '' }}>🔴 Tutup</option>
                </select>
                @error('status') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="jam_buka">Jam Operasional</label>
                <input type="text" id="jam_buka" name="jam_buka"
                       class="form-control"
                       value="{{ old('jam_buka', $toko->jam_buka ?? '') }}"
                       placeholder="Contoh: 08:00 – 17:00 WIB">
                @error('jam_buka') <span class="form-error">{{ $message }}</span> @enderror
                <span class="form-hint">Hari dan jam buka operasional toko</span>
            </div>

            <div class="form-group">
                <label class="form-label" for="no_telepon">No. Telepon / WhatsApp</label>
                <input type="text" id="no_telepon" name="no_telepon"
                       class="form-control" placeholder="Contoh: 0812-3456-7890"
                       value="{{ old('no_telepon', $toko->no_telepon ?? '') }}">
                @error('no_telepon') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group full">
                <label class="form-label" for="deskripsi">Deskripsi Toko</label>
                <textarea id="deskripsi" name="deskripsi" rows="4"
                          class="form-control {{ $errors->has('deskripsi') ? 'error' : '' }}"
                          placeholder="Ceritakan produk unggulan, sejarah toko, dan keunikannya...">{{ old('deskripsi', $toko->deskripsi ?? '') }}</textarea>
            </div>
        </div>
    </div>
</div>

{{-- Lokasi & Koordinat --}}
<div class="section-card">
    <div class="section-head">
        <div class="section-head-icon"><i class="fas fa-map-marker-alt"></i></div>
        <div class="section-title">Lokasi & Koordinat GPS</div>
    </div>
    <div class="section-body">
        <div class="form-group" style="margin-bottom:14px">
            <label class="form-label" for="alamat">Alamat Lengkap <span class="req">*</span></label>
            <textarea id="alamat" name="alamat" rows="2"
                      class="form-control {{ $errors->has('alamat') ? 'error' : '' }}"
                      placeholder="Contoh: Jl. Reje Bukit No.5, Bebesen, Bener Meriah" required>{{ old('alamat', $toko->alamat ?? '') }}</textarea>
            @error('alamat') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-grid" style="margin-bottom:14px">
            <div class="form-group">
                <label class="form-label" for="kabupaten">Kabupaten <span class="req">*</span></label>
                <select id="kabupaten" name="kabupaten"
                        class="form-control {{ $errors->has('kabupaten') ? 'error' : '' }}" required>
                    <option value="">-- Pilih Kabupaten --</option>
                    @foreach(['Bener Meriah', 'Aceh Tengah'] as $kab)
                        <option value="{{ $kab }}"
                            {{ old('kabupaten', $toko->kabupaten ?? '') === $kab ? 'selected' : '' }}>
                            {{ $kab }}
                        </option>
                    @endforeach
                </select>
                @error('kabupaten') <span class="form-error">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="kecamatan">Kecamatan</label>
                <input type="text" id="kecamatan" name="kecamatan" class="form-control"
                       value="{{ old('kecamatan', $toko->kecamatan ?? '') }}" placeholder="Contoh: Bebesen">
            </div>
        </div>

        <label class="form-label" style="margin-bottom:8px;display:block">
            <i class="fas fa-crosshairs" style="color:#e65100"></i> Klik peta untuk menentukan koordinat
        </label>
        <div id="peta-koordinat"></div>
        <p style="font-size:.72rem;color:#888;margin-top:6px">💡 Klik atau geser pin di peta untuk koordinat akurat.</p>

        <div class="koordinat-display">
            <div class="form-group">
                <label class="form-label" for="latitude">Latitude <span class="req">*</span></label>
                <input type="text" id="latitude" name="latitude"
                       class="form-control {{ $errors->has('latitude') ? 'error' : '' }}"
                       value="{{ old('latitude', $toko->latitude ?? '') }}"
                       placeholder="Contoh: 4.6892345" required>
                @error('latitude') <span class="form-error">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="longitude">Longitude <span class="req">*</span></label>
                <input type="text" id="longitude" name="longitude"
                       class="form-control {{ $errors->has('longitude') ? 'error' : '' }}"
                       value="{{ old('longitude', $toko->longitude ?? '') }}"
                       placeholder="Contoh: 96.8012345" required>
                @error('longitude') <span class="form-error">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>
</div>

{{-- Foto Utama --}}
<div class="section-card">
    <div class="section-head">
        <div class="section-head-icon"><i class="fas fa-image"></i></div>
        <div class="section-title">Foto Utama</div>
    </div>
    <div class="section-body" style="display:flex;gap:20px;align-items:flex-start">
        <div class="foto-preview-wrap" onclick="document.getElementById('foto_utama').click()">
            <img id="preview-foto-utama"
                 src="{{ isset($toko) ? $toko->foto_utama_url : asset('images/placeholder-toko.jpg') }}"
                 alt="Preview foto utama">
            <div style="position:absolute;bottom:4px;right:4px;background:#e65100;color:#fff;border-radius:50%;width:24px;height:24px;display:flex;align-items:center;justify-content:center;font-size:.7rem">
                <i class="fas fa-camera"></i>
            </div>
        </div>
        <div style="flex:1">
            <input type="file" id="foto_utama" name="foto_utama"
                   accept="image/jpg,image/jpeg,image/png,image/webp"
                   style="display:none" onchange="previewFotoUtama(this)">
            <label class="form-label">Klik foto untuk mengubah</label>
            <p class="form-hint" style="margin-top:4px">Format: JPG, PNG, WebP. Maks 3MB.</p>
            @error('foto_utama') <span class="form-error">{{ $message }}</span> @enderror
        </div>
    </div>
</div>

{{-- Galeri Foto --}}
<div class="section-card">
    <div class="section-head">
        <div class="section-head-icon"><i class="fas fa-images"></i></div>
        <div class="section-title">Galeri Foto</div>
    </div>
    <div class="section-body">
        @if(isset($toko) && $toko->galeri->count() > 0)
            <div class="galeri-preview-grid">
                @foreach($toko->galeri as $foto)
                <div class="galeri-preview-item">
                    <img src="{{ $foto->url }}" alt="Galeri">
                    <button class="galeri-preview-remove btn-action-galeri" type="button" title="Hapus foto"
                            data-action="{{ route('admin.tokos.galeri.hapus', [$toko, $foto]) }}"
                            data-method="DELETE"
                            data-confirm="Hapus foto ini?">
                        <i class="fas fa-times"></i>
                    </button>
                    @if($foto->is_utama)
                        <div style="position:absolute;bottom:3px;left:3px;background:#e65100;color:#fff;border-radius:4px;padding:1px 5px;font-size:.6rem;font-weight:700">UTAMA</div>
                    @else
                        <button type="button" class="btn-action-galeri"
                                style="position:absolute;bottom:3px;left:3px;background:rgba(0,0,0,.45);color:#fff;border:none;border-radius:4px;padding:1px 5px;font-size:.6rem;cursor:pointer"
                                title="Jadikan foto utama"
                                data-action="{{ route('admin.tokos.galeri.utama', [$toko, $foto]) }}"
                                data-method="PATCH">
                            ★ Utama
                        </button>
                    @endif
                </div>
                @endforeach
            </div>
            <hr style="margin:16px 0;border:none;border-top:1px solid #f0f0f0">
        @endif

        <div class="galeri-dropzone" onclick="document.getElementById('galeri-input').click()">
            <i class="fas fa-cloud-upload-alt" style="font-size:2rem;color:#ffcc80"></i>
            <p>Klik atau seret foto ke sini</p>
            <p style="font-size:.7rem;color:#aaa">JPG, PNG, WebP — Maks 5MB per foto — Maks 10 foto</p>
        </div>
        <input type="file" id="galeri-input" name="galeri[]" accept="image/*" multiple style="display:none"
               onchange="previewGaleri(this)">
        <div class="galeri-preview-grid" id="galeri-preview-grid"></div>
    </div>
</div>

@push('scripts')
<script>
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

function previewFotoUtama(input) {
    if (!input.files.length) return;
    const reader = new FileReader();
    reader.onload = e => { document.getElementById('preview-foto-utama').src = e.target.result; };
    reader.readAsDataURL(input.files[0]);
}
function previewGaleri(input) {
    const grid = document.getElementById('galeri-preview-grid');
    grid.innerHTML = '';
    Array.from(input.files).forEach((file, i) => {
        const reader = new FileReader();
        reader.onload = e => {
            const item = document.createElement('div');
            item.className = 'galeri-preview-item';
            item.innerHTML = `<img src="${e.target.result}" alt="Preview ${i+1}">`;
            grid.appendChild(item);
        };
        reader.readAsDataURL(file);
    });
}
document.getElementById('nama_toko')?.addEventListener('input', function () {
    const slugInput = document.getElementById('slug');
    if (!slugInput.dataset.manual) {
        slugInput.value = this.value.toLowerCase().replace(/[^a-z0-9\s-]/g,'').replace(/\s+/g,'-').replace(/-+/g,'-').trim();
    }
});
document.getElementById('slug')?.addEventListener('input', function () { this.dataset.manual = 'true'; });

function initPetaKoordinat() {
    const defaultLat = parseFloat(document.getElementById('latitude').value)  || 4.6900;
    const defaultLng = parseFloat(document.getElementById('longitude').value) || 96.8000;
    
    const peta = L.map('peta-koordinat').setView([defaultLat, defaultLng], 13);
    
    L.tileLayer('https://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
        attribution: '&copy; Google Maps'
    }).addTo(peta);

    const marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(peta);
    
    function updateKoordinat(latLng) {
        document.getElementById('latitude').value  = latLng.lat.toFixed(7);
        document.getElementById('longitude').value = latLng.lng.toFixed(7);
    }
    
    peta.on('click', e => { 
        marker.setLatLng(e.latlng); 
        updateKoordinat(e.latlng); 
    });
    
    marker.on('dragend', e => {
        updateKoordinat(marker.getLatLng());
    });
    
    ['latitude','longitude'].forEach(id => {
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
