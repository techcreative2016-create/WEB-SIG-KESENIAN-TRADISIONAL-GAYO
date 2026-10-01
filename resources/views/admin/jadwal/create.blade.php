@extends(auth()->user()->role === 'master' ? 'layouts.master' : 'layouts.admin')
@section('title', 'Tambah Jadwal Penampilan')
@section('breadcrumb', 'Admin / Jadwal Penampilan / Tambah Baru')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
    <div>
        <h2 style="font-size:1.2rem;font-weight:800">Tambah Jadwal Baru</h2>
        <p style="color:#777;font-size:.8rem;margin-top:2px">Buat jadwal penampilan seni</p>
    </div>
    <a href="{{ route('admin.jadwal.index') }}"
       style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:#f0f0f0;border-radius:8px;color:#555;font-size:.82rem;font-weight:600;text-decoration:none">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<form method="POST" action="{{ route('admin.jadwal.store') }}" enctype="multipart/form-data" id="form-tambah-jadwal">
    @csrf
    @include('admin.jadwal._form', ['jadwal' => null])
    <div class="form-actions" style="background:#fff;border-radius:0 0 12px 12px;box-shadow:0 1px 4px rgba(0,0,0,.06)">
        <button type="submit" class="btn btn-primary" id="btn-simpan-jadwal">
            <i class="fas fa-save"></i> Simpan Jadwal
        </button>
        <a href="{{ route('admin.jadwal.index') }}" class="btn btn-secondary">
            <i class="fas fa-times"></i> Batal
        </a>
    </div>
</form>
@endsection
