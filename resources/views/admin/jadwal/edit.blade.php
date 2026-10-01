@extends(auth()->user()->role === 'master' ? 'layouts.master' : 'layouts.admin')
@section('title', 'Edit Jadwal: ' . $jadwal->nama_acara)
@section('breadcrumb', 'Admin / Jadwal Penampilan / Edit')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:10px">
    <div>
        <h2 style="font-size:1.2rem;font-weight:800">Edit Jadwal</h2>
        <p style="color:#777;font-size:.8rem;margin-top:2px">
            Mengubah: <strong>{{ $jadwal->nama_acara }}</strong>
        </p>
    </div>
    <a href="{{ route('admin.jadwal.index') }}"
       style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:#f0f0f0;border-radius:8px;color:#555;font-size:.82rem;font-weight:600;text-decoration:none">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div style="background:#f3e5f5;border-radius:10px;padding:12px 16px;margin-bottom:20px;display:flex;align-items:center;gap:10px;font-size:.8rem;color:#4a148c">
    <i class="fas fa-info-circle"></i>
    <span>
        ID: <strong>#{{ $jadwal->id }}</strong> —
        Tanggal: <strong>{{ $jadwal->tanggal->format('d M Y') }}</strong> —
        Status saat ini:
        <strong style="background:#f3e5f5;padding:2px 8px;border-radius:10px">{{ $jadwal->status }}</strong>
    </span>
</div>

<form method="POST" action="{{ route('admin.jadwal.update', $jadwal) }}" enctype="multipart/form-data" id="form-edit-jadwal">
    @csrf @method('PUT')
    @include('admin.jadwal._form', compact('jadwal'))

    <div class="form-actions" style="background:#fff;border-radius:0 0 12px 12px;box-shadow:0 1px 4px rgba(0,0,0,.06);display:flex;justify-content:space-between;align-items:center">
        <div style="display:flex;gap:10px">
            <button type="submit" class="btn btn-primary" id="btn-update-jadwal">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
            <a href="{{ route('admin.jadwal.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
        {{-- Tombol Hapus (trigger form terpisah di luar form update) --}}
        <button type="button" id="btn-hapus-jadwal"
                style="display:inline-flex;align-items:center;gap:6px;padding:9px 16px;background:#fce4ec;color:#c62828;border:none;border-radius:8px;font-size:.82rem;font-weight:600;cursor:pointer">
            <i class="fas fa-trash"></i> Hapus Jadwal
        </button>
    </div>
</form>

{{-- Form Hapus TERPISAH (di luar form update agar tidak nested) --}}
<form method="POST" action="{{ route('admin.jadwal.destroy', $jadwal) }}"
      id="form-hapus-jadwal" style="display:none">
    @csrf @method('DELETE')
</form>

<script>
document.getElementById('btn-hapus-jadwal').addEventListener('click', function() {
    if (confirm('Hapus jadwal "{{ addslashes($jadwal->nama_acara) }}"? Tindakan ini tidak bisa dibatalkan.')) {
        document.getElementById('form-hapus-jadwal').submit();
    }
});
</script>
@endsection
