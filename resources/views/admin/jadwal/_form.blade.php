{{--
    Form partial reusable untuk Create dan Edit Jadwal Penampilan.
    Variabel: $jadwal (null saat create), $sanggars (collection Sanggar aktif)
--}}
@push('styles')
<style>
    .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
    .form-grid .full{grid-column:1/-1}
    .form-group{display:flex;flex-direction:column;gap:5px}
    .form-label{font-size:.8rem;font-weight:600;color:#333}
    .form-label .req{color:#e53935;margin-left:2px}
    .form-control{padding:9px 12px;border:1px solid #ddd;border-radius:8px;font-size:.85rem;font-family:inherit;transition:border .2s;background:#fff}
    .form-control:focus{outline:none;border-color:#7b1fa2;box-shadow:0 0 0 3px rgba(123,31,162,.1)}
    .form-control.error{border-color:#e53935}
    .form-error{font-size:.72rem;color:#e53935;margin-top:2px}
    .form-hint{font-size:.72rem;color:#888;margin-top:2px}
    .section-card{background:#fff;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,.06);margin-bottom:20px;overflow:hidden}
    .section-head{padding:14px 20px;border-bottom:1px solid #f0f0f0;display:flex;align-items:center;gap:8px}
    .section-head-icon{width:30px;height:30px;border-radius:8px;background:#f3e5f5;color:#7b1fa2;display:flex;align-items:center;justify-content:center;font-size:.85rem}
    .section-title{font-size:.9rem;font-weight:700;color:#1a1a2e}
    .section-body{padding:20px}
    #peta-lokasi-acara{width:100%;height:260px;border-radius:10px;border:1px solid #ddd;overflow:hidden}
    .koordinat-display{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:10px}
    .poster-preview-wrap{position:relative;display:inline-block}
    #preview-poster{width:100px;height:140px;border-radius:10px;object-fit:cover;border:2px dashed #ddd;background:#f5f5f5;cursor:pointer}
    .btn{display:inline-flex;align-items:center;gap:6px;padding:9px 20px;border-radius:8px;font-size:.83rem;font-weight:600;cursor:pointer;border:none;text-decoration:none;transition:all .2s}
    .btn-primary{background:#4a148c;color:#fff}.btn-primary:hover{background:#311b92}
    .btn-secondary{background:#f0f0f0;color:#555}.btn-secondary:hover{background:#e0e0e0}
    .form-actions{display:flex;gap:10px;padding:16px 20px;border-top:1px solid #f0f0f0;background:#fafafa}

    /* Sanggar selector dengan info koordinat */
    .sanggar-info-box{background:#f3e5f5;border-radius:8px;padding:10px 14px;font-size:.78rem;color:#4a148c;margin-top:8px;display:none}
    .sanggar-info-box{background:#f3e5f5;border-radius:8px;padding:10px 14px;font-size:.78rem;color:#4a148c;margin-top:8px;display:none}
    .sanggar-info-box.show{display:block}
</style>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    /* Fix z-index issue with leaflet inside modal/form */
    .leaflet-container { z-index: 1; }
</style>
@endpush

{{-- ==================== INFORMASI ACARA ==================== --}}
<div class="section-card">
    <div class="section-head">
        <div class="section-head-icon"><i class="fas fa-calendar-star"></i></div>
        <div class="section-title">Informasi Acara</div>
    </div>
    <div class="section-body">
        <div class="form-grid">

            {{-- Nama Acara --}}
            <div class="form-group full">
                <label class="form-label" for="nama_acara">Nama Acara <span class="req">*</span></label>
                <input type="text" id="nama_acara" name="nama_acara"
                       class="form-control {{ $errors->has('nama_acara') ? 'error' : '' }}"
                       value="{{ old('nama_acara', $jadwal->nama_acara ?? '') }}"
                       placeholder="Contoh: Pentas Didong Jalu Malam Minggu" required>
                @error('nama_acara') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            {{-- Sanggar Penampil --}}
            <div class="form-group">
                <label class="form-label" for="nama_sanggar">Sanggar Penampil <span class="req">*</span></label>
                <input type="text" id="nama_sanggar" name="nama_sanggar"
                       class="form-control {{ $errors->has('nama_sanggar') ? 'error' : '' }}"
                       value="{{ old('nama_sanggar', $jadwal->nama_sanggar ?? '') }}"
                       placeholder="Contoh: Sanggar Pegayon" required>
                @error('nama_sanggar') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            {{-- Jenis Kesenian --}}
            <div class="form-group">
                <label class="form-label" for="jenis_kesenian">Jenis Kesenian <span class="req">*</span></label>
                <select id="jenis_kesenian" name="jenis_kesenian"
                        class="form-control {{ $errors->has('jenis_kesenian') ? 'error' : '' }}" required>
                    <option value="">-- Pilih Jenis --</option>
                    @foreach(['Didong','Tari Guel','Kerawang Gayo','Teganing','Campuran'] as $j)
                        <option value="{{ $j }}"
                            {{ old('jenis_kesenian', $jadwal->jenis_kesenian ?? '') === $j ? 'selected' : '' }}>
                            {{ $j }}
                        </option>
                    @endforeach
                </select>
                @error('jenis_kesenian') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            {{-- Status --}}
            <div class="form-group">
                <label class="form-label" for="status">Status Jadwal <span class="req">*</span></label>
                <select id="status" name="status" class="form-control" required>
                    @foreach(['Terjadwal','Berlangsung','Selesai','Dibatalkan'] as $s)
                        <option value="{{ $s }}"
                            {{ old('status', $jadwal->status ?? 'Terjadwal') === $s ? 'selected' : '' }}>
                            {{ $s }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Deskripsi --}}
            <div class="form-group full">
                <label class="form-label" for="deskripsi">Deskripsi Acara</label>
                <textarea id="deskripsi" name="deskripsi" rows="3"
                          class="form-control"
                          placeholder="Ceritakan tentang acara ini, penampil, dan hal menarik lainnya...">{{ old('deskripsi', $jadwal->deskripsi ?? '') }}</textarea>
            </div>

            {{-- Info Tiket --}}
            <div class="form-group full">
                <label class="form-label" for="tiket_info">Informasi Tiket / Harga</label>
                <input type="text" id="tiket_info" name="tiket_info" class="form-control"
                       value="{{ old('tiket_info', $jadwal->tiket_info ?? '') }}"
                       placeholder="Contoh: Gratis / Rp 20.000 per orang">
            </div>
        </div>
    </div>
</div>

{{-- ==================== WAKTU & JADWAL ==================== --}}
<div class="section-card">
    <div class="section-head">
        <div class="section-head-icon"><i class="fas fa-clock"></i></div>
        <div class="section-title">Waktu Penampilan</div>
    </div>
    <div class="section-body">
        <div class="form-grid">
            <div class="form-group full">
                <label class="form-label" for="tanggal">Tanggal Acara <span class="req">*</span></label>
                <input type="date" id="tanggal" name="tanggal"
                       class="form-control {{ $errors->has('tanggal') ? 'error' : '' }}"
                       value="{{ old('tanggal', isset($jadwal) ? $jadwal->tanggal->format('Y-m-d') : '') }}"
                       required>
                @error('tanggal') <span class="form-error">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="waktu_mulai">Waktu Mulai <span class="req">*</span></label>
                <input type="time" id="waktu_mulai" name="waktu_mulai"
                       class="form-control {{ $errors->has('waktu_mulai') ? 'error' : '' }}"
                       value="{{ old('waktu_mulai', isset($jadwal) ? \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') : '') }}"
                       required>
                @error('waktu_mulai') <span class="form-error">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="waktu_selesai">Waktu Selesai</label>
                <input type="time" id="waktu_selesai" name="waktu_selesai"
                       class="form-control {{ $errors->has('waktu_selesai') ? 'error' : '' }}"
                       value="{{ old('waktu_selesai', isset($jadwal) && $jadwal->waktu_selesai ? \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') : '') }}">
                @error('waktu_selesai') <span class="form-error">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>
</div>

{{-- ==================== LOKASI ACARA ==================== --}}
<div class="section-card">
    <div class="section-head">
        <div class="section-head-icon"><i class="fas fa-map-marker-alt"></i></div>
        <div class="section-title">Lokasi Acara</div>
    </div>
    <div class="section-body">
        <div class="form-grid" style="margin-bottom:14px">
            <div class="form-group">
                <label class="form-label" for="lokasi_acara">Nama Venue / Lokasi <span class="req">*</span></label>
                <input type="text" id="lokasi_acara" name="lokasi_acara"
                       class="form-control {{ $errors->has('lokasi_acara') ? 'error' : '' }}"
                       value="{{ old('lokasi_acara', $jadwal->lokasi_acara ?? '') }}"
                       placeholder="Contoh: Lapangan Bebesen, GOR Bener Meriah" required>
                @error('lokasi_acara') <span class="form-error">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="alamat_acara">Alamat Detail Lokasi</label>
                <input type="text" id="alamat_acara" name="alamat_acara"
                       class="form-control"
                       value="{{ old('alamat_acara', $jadwal->alamat_acara ?? '') }}"
                       placeholder="Alamat lengkap lokasi acara">
            </div>
        </div>

        <label class="form-label" style="margin-bottom:8px;display:block">
            <i class="fas fa-crosshairs" style="color:#7b1fa2"></i>
            Klik peta untuk menentukan koordinat lokasi acara (opsional)
        </label>
        <div id="peta-lokasi-acara"></div>
        <p style="font-size:.72rem;color:#888;margin-top:6px">
            💡 Pilih titik koordinat di peta untuk memberikan lokasi yang akurat.
        </p>

        <div class="koordinat-display">
            <div class="form-group">
                <label class="form-label" for="latitude">Latitude Acara</label>
                <input type="text" id="latitude" name="latitude"
                       class="form-control"
                       value="{{ old('latitude', $jadwal->latitude ?? '') }}"
                       placeholder="Opsional">
            </div>
            <div class="form-group">
                <label class="form-label" for="longitude">Longitude Acara</label>
                <input type="text" id="longitude" name="longitude"
                       class="form-control"
                       value="{{ old('longitude', $jadwal->longitude ?? '') }}"
                       placeholder="Opsional">
            </div>
        </div>
    </div>
</div>

{{-- ==================== POSTER ==================== --}}
<div class="section-card">
    <div class="section-head">
        <div class="section-head-icon"><i class="fas fa-image"></i></div>
        <div class="section-title">Poster Acara</div>
    </div>
    <div class="section-body" style="display:flex;gap:20px;align-items:flex-start">
        <div class="poster-preview-wrap" onclick="document.getElementById('poster').click()">
            <img id="preview-poster"
                 src="{{ isset($jadwal) && $jadwal->poster ? asset('storage/'.$jadwal->poster) : asset('images/placeholder-acara.jpg') }}"
                 alt="Preview poster">
            <div style="position:absolute;bottom:4px;right:4px;background:#7b1fa2;color:#fff;border-radius:50%;width:24px;height:24px;display:flex;align-items:center;justify-content:center;font-size:.7rem">
                <i class="fas fa-camera"></i>
            </div>
        </div>
        <div style="flex:1">
            <input type="file" id="poster" name="poster" accept="image/*"
                   style="display:none" onchange="previewPoster(this)">
            <label class="form-label">Klik poster untuk mengubah</label>
            <p class="form-hint" style="margin-top:4px">Format: JPG, PNG, WebP. Maks 4MB.<br>Rekomendasi rasio: potrait 2:3 (contoh: 600×900px)</p>
            @error('poster') <span class="form-error">{{ $message }}</span> @enderror
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewPoster(input) {
    if (!input.files.length) return;
    const reader = new FileReader();
    reader.onload = e => { document.getElementById('preview-poster').src = e.target.result; };
    reader.readAsDataURL(input.files[0]);
}



// Peta lokasi acara (Leaflet)
function initPetaLokasiAcara() {
    const defaultLat = parseFloat(document.getElementById('latitude').value)  || 4.7200;
    const defaultLng = parseFloat(document.getElementById('longitude').value) || 96.8500;

    window.petaAcaraInstance = L.map('peta-lokasi-acara').setView([defaultLat, defaultLng], 12);
    
    L.tileLayer('https://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
        attribution: '&copy; Google Maps'
    }).addTo(window.petaAcaraInstance);

    window.petaAcaraMarker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(window.petaAcaraInstance);

    function updateKoordinat(latLng) {
        document.getElementById('latitude').value  = latLng.lat.toFixed(7);
        document.getElementById('longitude').value = latLng.lng.toFixed(7);
    }

    window.petaAcaraInstance.on('click', e => {
        window.petaAcaraMarker.setLatLng(e.latlng);
        updateKoordinat(e.latlng);
    });
    
    window.petaAcaraMarker.on('dragend', e => {
        updateKoordinat(window.petaAcaraMarker.getLatLng());
    });

    // Fix map loading issue in hidden elements
    setTimeout(() => { window.petaAcaraInstance.invalidateSize(); }, 500);
}

document.addEventListener("DOMContentLoaded", function() {
    initPetaLokasiAcara();
});
</script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endpush
