@extends(auth()->user()->role === 'master' ? 'layouts.master' : 'layouts.admin')
@section('title', 'Daftar Toko Kesenian')
@section('breadcrumb', 'Admin / Toko Kesenian')

@push('styles')
<style>
    .page-actions{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px}
    .btn{display:inline-flex;align-items:center;gap:6px;padding:9px 18px;border-radius:8px;font-size:.82rem;font-weight:600;cursor:pointer;border:none;text-decoration:none;transition:all .2s}
    .btn-primary{background:#e65100;color:#fff}.btn-primary:hover{background:#bf360c}
    .btn-sm{padding:5px 12px;font-size:.75rem;border-radius:6px}
    .btn-info{background:#e3f2fd;color:#1565c0}.btn-warning{background:#fff3e0;color:#e65100}
    .btn-danger{background:#fce4ec;color:#c62828}.btn-success{background:#e8f5e9;color:#0D9373}
    .filter-bar{background:#fff;border-radius:10px;padding:16px;margin-bottom:20px;display:flex;gap:10px;flex-wrap:wrap;box-shadow:0 1px 4px rgba(0,0,0,.06)}
    .filter-bar input,.filter-bar select{padding:8px 12px;border:1px solid #e0e0e0;border-radius:6px;font-size:.82rem;font-family:inherit}
    .filter-bar input{flex:1;min-width:180px}
    .card{background:#fff;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,.06);overflow:hidden}
    table{width:100%;border-collapse:collapse;font-size:.82rem}
    thead th{background:#fff3e0;padding:12px 16px;text-align:left;font-weight:600;color:#555;border-bottom:2px solid #ffe0b2;white-space:nowrap}
    tbody td{padding:12px 16px;border-bottom:1px solid #f5f5f5;vertical-align:middle}
    tbody tr:hover{background:#fafafa}
    .foto-thumb{width:48px;height:48px;border-radius:8px;object-fit:cover;background:#f0f0f0}
    .badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:.68rem;font-weight:700}
    .badge-buka{background:#e8f5e9;color:#0D9373}.badge-tutup{background:#fce4ec;color:#c62828}
    .badge-toko{background:#fff3e0;color:#e65100}.badge-hapus{background:#eceff1;color:#607d8b}
    .aksi-group{display:flex;gap:4px}
    .pagination-wrap{padding:16px;display:flex;justify-content:space-between;align-items:center}
    .pagination-info{font-size:.78rem;color:#777}
</style>
@endpush

@section('content')
<div class="page-actions">
    <div>
        <h2 style="font-size:1.2rem;font-weight:800">Toko Kesenian</h2>
        <p style="color:#777;font-size:.8rem;margin-top:2px">Total: {{ $tokos->total() }} toko terdaftar</p>
    </div>
    <a href="{{ route('admin.tokos.create') }}" class="btn btn-primary" id="btn-tambah-toko">
        <i class="fas fa-plus"></i> Tambah Toko
    </a>
</div>

{{-- Filter --}}
<form method="GET" action="{{ route('admin.tokos.index') }}">
    <div class="filter-bar">
        <input type="text" name="cari" placeholder="🔍 Cari nama toko..." value="{{ request('cari') }}">
        <select name="jenis_produk">
            <option value="">Semua Jenis Produk</option>
            @foreach(['Kerawang Gayo','Alat Musik','Pakaian Adat','Kerajinan Tangan','Campuran'] as $j)
                <option value="{{ $j }}" {{ request('jenis_produk') === $j ? 'selected' : '' }}>{{ $j }}</option>
            @endforeach
        </select>
        <select name="kabupaten">
            <option value="">Semua Kabupaten</option>
            <option value="Bener Meriah" {{ request('kabupaten') === 'Bener Meriah' ? 'selected' : '' }}>Bener Meriah</option>
            <option value="Aceh Tengah" {{ request('kabupaten') === 'Aceh Tengah' ? 'selected' : '' }}>Aceh Tengah</option>
        </select>
        <select name="status">
            <option value="">Semua Status</option>
            <option value="Buka"     {{ request('status') === 'Buka'     ? 'selected' : '' }}>Buka</option>
            <option value="Tutup"    {{ request('status') === 'Tutup'    ? 'selected' : '' }}>Tutup</option>
            <option value="terhapus" {{ request('status') === 'terhapus' ? 'selected' : '' }}>Terhapus</option>
        </select>
        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
        <a href="{{ route('admin.tokos.index') }}" class="btn" style="background:#f0f0f0;color:#555">
            <i class="fas fa-times"></i> Reset
        </a>
    </div>
</form>

{{-- Tabel --}}
<div class="card">
    <table>
        <thead>
            <tr>
                <th width="56">Foto</th>
                <th>Nama Toko</th>
                <th>Jenis Produk</th>
                <th>Kabupaten</th>
                <th>Jam Buka</th>
                <th>Status</th>
                <th width="140">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tokos as $toko)
            <tr id="row-toko-{{ $toko->id }}">
                <td>
                    <img class="foto-thumb"
                         src="{{ $toko->foto_utama_url }}"
                         alt="{{ $toko->nama_toko }}"
                         onerror="this.src='{{ asset('images/placeholder-toko.jpg') }}'">
                </td>
                <td>
                    <div style="font-weight:600">{{ $toko->nama_toko }}</div>
                    <div style="color:#777;font-size:.73rem;margin-top:2px">{{ Str::limit($toko->alamat, 50) }}</div>
                </td>
                <td><span class="badge badge-toko">{{ $toko->jenis_produk }}</span></td>
                <td>{{ $toko->kabupaten }}</td>
                <td style="font-size:.78rem;color:#555">{{ $toko->jam_buka ?? '—' }}</td>
                <td>
                    @if ($toko->deleted_at)
                        <span class="badge badge-hapus">Terhapus</span>
                    @else
                        <span class="badge badge-{{ strtolower($toko->status) }}">{{ $toko->status }}</span>
                    @endif
                </td>
                <td>
                    <div class="aksi-group">
                        @if ($toko->deleted_at)
                            <form method="POST" action="{{ route('admin.tokos.restore', $toko->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success" title="Pulihkan">
                                    <i class="fas fa-undo"></i>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('admin.tokos.edit', $toko) }}" class="btn btn-sm btn-info" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.tokos.destroy', $toko) }}"
                                  onsubmit="return confirm('Hapus toko \'{{ addslashes($toko->nama_toko) }}\'?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;padding:40px;color:#aaa">
                    <i class="fas fa-store" style="font-size:2rem;margin-bottom:8px;display:block"></i>
                    Tidak ada toko yang sesuai filter.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination-wrap">
        <div class="pagination-info">
            Menampilkan {{ $tokos->firstItem() }}–{{ $tokos->lastItem() }} dari {{ $tokos->total() }} toko
        </div>
        {{ $tokos->links() }}
    </div>
</div>
@endsection
