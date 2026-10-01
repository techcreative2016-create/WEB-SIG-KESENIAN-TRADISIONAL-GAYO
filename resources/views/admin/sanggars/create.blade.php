@extends(auth()->user()->role === 'master' ? 'layouts.master' : 'layouts.admin')

@section('title', 'Tambah Sanggar Baru')
@section('breadcrumb', 'Admin / Sanggar Seni / Tambah Baru')

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
    <div>
        <h2 style="font-size:1.2rem;font-weight:800">Tambah Sanggar Baru</h2>
        <p style="color:#777;font-size:0.8rem;margin-top:2px">Isi semua informasi sanggar seni</p>
    </div>
    <a href="{{ route('admin.sanggars.index') }}"
       style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:#f0f0f0;border-radius:8px;color:#555;font-size:0.82rem;font-weight:600;text-decoration:none">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<form method="POST"
      action="{{ route('admin.sanggars.store') }}"
      enctype="multipart/form-data"
      id="form-tambah-sanggar">
    @csrf

    {{-- Include reusable form --}}
    @include('admin.sanggars._form', ['sanggar' => null])

    {{-- Tombol Aksi --}}
    <div class="form-actions" style="background:#fff;border-radius:0 0 12px 12px;box-shadow:0 1px 4px rgba(0,0,0,0.06)">
        <button type="submit" class="btn btn-primary" id="btn-simpan-sanggar">
            <i class="fas fa-save"></i> Simpan Sanggar
        </button>
        <a href="{{ route('admin.sanggars.index') }}" class="btn btn-secondary">
            <i class="fas fa-times"></i> Batal
        </a>
    </div>
</form>

@endsection
