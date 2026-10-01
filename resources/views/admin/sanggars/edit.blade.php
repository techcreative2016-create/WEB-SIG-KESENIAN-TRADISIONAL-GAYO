@extends(auth()->user()->role === 'master' ? 'layouts.master' : 'layouts.admin')

@section('title', 'Edit: ' . $sanggar->nama_sanggar)
@section('breadcrumb', 'Admin / Sanggar Seni / Edit')

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:10px">
    <div>
        <h2 style="font-size:1.2rem;font-weight:800">Edit Sanggar</h2>
        <p style="color:#777;font-size:0.8rem;margin-top:2px">
            Mengubah data: <strong>{{ $sanggar->nama_sanggar }}</strong>
        </p>
    </div>
    <div style="display:flex;gap:8px">
        <a href="{{ route('publik.sanggar.show', $sanggar->slug) }}" target="_blank"
           style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;background:#e3f2fd;border-radius:8px;color:#1565c0;font-size:0.8rem;font-weight:600;text-decoration:none">
            <i class="fas fa-external-link-alt"></i> Lihat Publik
        </a>
        <a href="{{ route('admin.sanggars.index') }}"
           style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:#f0f0f0;border-radius:8px;color:#555;font-size:0.82rem;font-weight:600;text-decoration:none">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

{{-- Info singkat --}}
<div style="background:#ede7f6;border-radius:10px;padding:12px 16px;margin-bottom:20px;display:flex;align-items:center;gap:10px;font-size:0.8rem;color:#4a148c">
    <i class="fas fa-info-circle"></i>
    <span>
        ID: <strong>#{{ $sanggar->id }}</strong> —
        Dibuat: <strong>{{ $sanggar->created_at->format('d M Y') }}</strong> —
        Terakhir diperbarui: <strong>{{ $sanggar->updated_at->diffForHumans() }}</strong>
    </span>
</div>

{{-- Form Edit --}}
<form method="POST"
      action="{{ route('admin.sanggars.update', $sanggar) }}"
      enctype="multipart/form-data"
      id="form-edit-sanggar">
    @csrf
    @method('PUT')

    {{-- Include reusable form dengan data $sanggar --}}
    @include('admin.sanggars._form', compact('sanggar'))

    {{-- Tombol Aksi --}}
    <div class="form-actions"
         style="background:#fff;border-radius:0 0 12px 12px;box-shadow:0 1px 4px rgba(0,0,0,0.06);display:flex;justify-content:space-between;align-items:center">
        <div style="display:flex;gap:10px">
            <button type="submit" class="btn btn-primary" id="btn-update-sanggar">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
            <a href="{{ route('admin.sanggars.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>

        {{-- Tombol Hapus di pojok kanan (trigger form hapus di bawah) --}}
        <button type="button"
                style="display:inline-flex;align-items:center;gap:6px;padding:9px 16px;background:#fce4ec;color:#c62828;border:none;border-radius:8px;font-size:0.82rem;font-weight:600;cursor:pointer"
                id="btn-hapus-sanggar">
            <i class="fas fa-trash"></i> Hapus Sanggar
        </button>
    </div>
</form>

{{-- Form Hapus TERPISAH dari form Edit (di luar form update agar tidak nested) --}}
<form method="POST"
      action="{{ route('admin.sanggars.destroy', $sanggar) }}"
      id="form-hapus-sanggar"
      style="display:none">
    @csrf
    @method('DELETE')
</form>

<script>
document.getElementById('btn-hapus-sanggar').addEventListener('click', function() {
    if (confirm('Yakin ingin menghapus sanggar "{{ addslashes($sanggar->nama_sanggar) }}"? Data bisa dipulihkan nanti.')) {
        document.getElementById('form-hapus-sanggar').submit();
    }
});
</script>

@endsection
