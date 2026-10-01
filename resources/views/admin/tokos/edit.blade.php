@extends(auth()->user()->role === 'master' ? 'layouts.master' : 'layouts.admin')
@section('title', 'Edit: ' . $toko->nama_toko)
@section('breadcrumb', 'Admin / Toko Kesenian / Edit')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:10px">
    <div>
        <h2 style="font-size:1.2rem;font-weight:800">Edit Toko</h2>
        <p style="color:#777;font-size:.8rem;margin-top:2px">Mengubah data: <strong>{{ $toko->nama_toko }}</strong></p>
    </div>
    <div style="display:flex;gap:8px">
        <a href="{{ route('publik.toko.show', $toko->slug) }}" target="_blank"
           style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;background:#fff3e0;border-radius:8px;color:#e65100;font-size:.8rem;font-weight:600;text-decoration:none">
            <i class="fas fa-external-link-alt"></i> Lihat Publik
        </a>
        <a href="{{ route('admin.tokos.index') }}"
           style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:#f0f0f0;border-radius:8px;color:#555;font-size:.82rem;font-weight:600;text-decoration:none">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div style="background:#fff3e0;border-radius:10px;padding:12px 16px;margin-bottom:20px;display:flex;align-items:center;gap:10px;font-size:.8rem;color:#e65100">
    <i class="fas fa-info-circle"></i>
    <span>ID: <strong>#{{ $toko->id }}</strong> —
    Dibuat: <strong>{{ $toko->created_at->format('d M Y') }}</strong> —
    Diperbarui: <strong>{{ $toko->updated_at->diffForHumans() }}</strong></span>
</div>

<form method="POST" action="{{ route('admin.tokos.update', $toko) }}" enctype="multipart/form-data" id="form-edit-toko">
    @csrf @method('PUT')
    @include('admin.tokos._form', compact('toko'))
    <div class="form-actions" style="background:#fff;border-radius:0 0 12px 12px;box-shadow:0 1px 4px rgba(0,0,0,.06);display:flex;justify-content:space-between;align-items:center">
        <div style="display:flex;gap:10px">
            <button type="submit" class="btn btn-primary" id="btn-update-toko">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
            <a href="{{ route('admin.tokos.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
        {{-- Tombol Hapus (trigger form terpisah di luar form update) --}}
        <button type="button" id="btn-hapus-toko"
                style="display:inline-flex;align-items:center;gap:6px;padding:9px 16px;background:#fce4ec;color:#c62828;border:none;border-radius:8px;font-size:.82rem;font-weight:600;cursor:pointer">
            <i class="fas fa-trash"></i> Hapus Toko
        </button>
    </div>
</form>

{{-- Form Hapus TERPISAH (di luar form update agar tidak nested) --}}
<form method="POST" action="{{ route('admin.tokos.destroy', $toko) }}"
      id="form-hapus-toko" style="display:none">
    @csrf @method('DELETE')
</form>

<script>
document.getElementById('btn-hapus-toko').addEventListener('click', function() {
    if (confirm('Hapus toko "{{ addslashes($toko->nama_toko) }}"? Tindakan ini tidak bisa dibatalkan.')) {
        document.getElementById('form-hapus-toko').submit();
    }
});
</script>
@endsection
