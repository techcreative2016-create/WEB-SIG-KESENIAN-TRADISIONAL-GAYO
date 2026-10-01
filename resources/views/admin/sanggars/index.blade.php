@extends(auth()->user()->role === 'master' ? 'layouts.master' : 'layouts.admin')

@section('title', 'Daftar Sanggar Seni')
@section('breadcrumb', 'Admin / Sanggar Seni')

@push('styles')
<style>
    .page-actions { display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; flex-wrap:wrap; gap:12px; }
    .btn { display:inline-flex; align-items:center; gap:6px; padding:9px 18px; border-radius:8px; font-size:0.82rem; font-weight:600; cursor:pointer; border:none; text-decoration:none; transition:all 0.2s; }
    .btn-primary { background:#5c6bc0; color:#fff; }
    .btn-primary:hover { background:#3949ab; }
    .btn-sm { padding:5px 12px; font-size:0.75rem; border-radius:6px; }
    .btn-info    { background:#e3f2fd; color:#1565c0; }
    .btn-warning { background:#fff3e0; color:#e65100; }
    .btn-danger  { background:#fce4ec; color:#c62828; }
    .btn-success { background:#e8f5e9; color:#0D9373; }

    /* Filter bar */
    .filter-bar { background:#fff; border-radius:10px; padding:16px; margin-bottom:20px; display:flex; gap:10px; flex-wrap:wrap; box-shadow:0 1px 4px rgba(0,0,0,0.06); }
    .filter-bar input, .filter-bar select { padding:8px 12px; border:1px solid #e0e0e0; border-radius:6px; font-size:0.82rem; font-family:inherit; }
    .filter-bar input { flex:1; min-width:180px; }

    /* Tabel */
    .card { background:#fff; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,0.06); overflow:hidden; }
    table { width:100%; border-collapse:collapse; font-size:0.82rem; }
    thead th { background:#f5f5f5; padding:12px 16px; text-align:left; font-weight:600; color:#555; border-bottom:2px solid #e0e0e0; white-space:nowrap; }
    tbody td { padding:12px 16px; border-bottom:1px solid #f5f5f5; vertical-align:middle; }
    tbody tr:hover { background:#fafafa; }
    tbody tr:last-child td { border-bottom:none; }

    .foto-thumb { width:48px; height:48px; border-radius:8px; object-fit:cover; background:#f0f0f0; }

    .badge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:0.68rem; font-weight:700; }
    .badge-tari   { background:#e8f5e9; color:#0D9373; }
    .badge-musik  { background:#e3f2fd; color:#1565c0; }
    .badge-kriya  { background:#fff3e0; color:#e65100; }
    .badge-aktif  { background:#e8f5e9; color:#0D9373; }
    .badge-vakum  { background:#fce4ec; color:#c62828; }
    .badge-hapus  { background:#eceff1; color:#607d8b; }

    /* Aksi tabel */
    .aksi-group { display:flex; gap:4px; }

    /* Pagination */
    .pagination-wrap { padding:16px; display:flex; justify-content:space-between; align-items:center; }
    .pagination-info { font-size:0.78rem; color:#777; }
</style>
@endpush

@section('content')

{{-- Header halaman --}}
<div class="page-actions">
    <div>
        <h2 style="font-size:1.2rem;font-weight:800">Sanggar Seni</h2>
        <p style="color:#777;font-size:0.8rem;margin-top:2px">
            Total: {{ $sanggars->total() }} sanggar terdaftar
        </p>
    </div>
    <a href="{{ route('admin.sanggars.create') }}" class="btn btn-primary" id="btn-tambah-sanggar">
        <i class="fas fa-plus"></i> Tambah Sanggar
    </a>
</div>

{{-- Filter & Pencarian --}}
<form method="GET" action="{{ route('admin.sanggars.index') }}">
    <div class="filter-bar">
        <input type="text" name="cari" placeholder="🔍 Cari nama sanggar..."
               value="{{ request('cari') }}" id="input-cari-sanggar">

        <select name="kategori" id="filter-kategori-sanggar">
            <option value="">Semua Kategori</option>
            <option value="Tari"   {{ request('kategori') === 'Tari'  ? 'selected' : '' }}>Tari</option>
            <option value="Musik"  {{ request('kategori') === 'Musik' ? 'selected' : '' }}>Musik</option>
            <option value="Kriya"  {{ request('kategori') === 'Kriya' ? 'selected' : '' }}>Kriya</option>
        </select>

        <select name="kabupaten" id="filter-kabupaten-sanggar">
            <option value="">Semua Kabupaten</option>
            <option value="Bener Meriah" {{ request('kabupaten') === 'Bener Meriah' ? 'selected' : '' }}>Bener Meriah</option>
            <option value="Aceh Tengah" {{ request('kabupaten') === 'Aceh Tengah' ? 'selected' : '' }}>Aceh Tengah</option>
        </select>

        <select name="status" id="filter-status-sanggar">
            <option value="">Semua Status</option>
            <option value="Aktif"    {{ request('status') === 'Aktif'    ? 'selected' : '' }}>Aktif</option>
            <option value="Vakum"    {{ request('status') === 'Vakum'    ? 'selected' : '' }}>Vakum</option>
            <option value="terhapus" {{ request('status') === 'terhapus' ? 'selected' : '' }}>Terhapus</option>
        </select>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-filter"></i> Filter
        </button>
        <a href="{{ route('admin.sanggars.index') }}" class="btn" style="background:#f0f0f0;color:#555">
            <i class="fas fa-times"></i> Reset
        </a>
    </div>
</form>

{{-- Tabel Sanggar --}}
<div class="card">
    <table>
        <thead>
            <tr>
                <th width="56">Foto</th>
                <th>Nama Sanggar</th>
                <th>Kategori</th>
                <th>Kabupaten</th>
                <th>Koordinat</th>
                <th>Status</th>
                <th width="160">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sanggars as $sanggar)
            <tr id="row-sanggar-{{ $sanggar->id }}">
                {{-- Foto thumbnail --}}
                <td>
                    <img class="foto-thumb"
                         src="{{ $sanggar->foto_utama_url }}"
                         alt="{{ $sanggar->nama_sanggar }}"
                         onerror="this.src='{{ asset('images/placeholder-sanggar.jpg') }}'">
                </td>

                {{-- Nama + alamat --}}
                <td>
                    <div style="font-weight:600">{{ $sanggar->nama_sanggar }}</div>
                    <div style="color:#777;font-size:0.73rem;margin-top:2px">
                        {{ Str::limit($sanggar->alamat, 50) }}
                    </div>
                </td>

                {{-- Kategori --}}
                <td>
                    <span class="badge badge-{{ strtolower($sanggar->kategori) }}">
                        {{ $sanggar->kategori }}
                    </span>
                </td>

                {{-- Kabupaten --}}
                <td>{{ $sanggar->kabupaten }}</td>

                {{-- Koordinat --}}
                <td style="font-family:monospace;font-size:0.72rem;color:#777">
                    {{ number_format($sanggar->latitude, 5) }},<br>
                    {{ number_format($sanggar->longitude, 5) }}
                </td>

                {{-- Status --}}
                <td>
                    @if ($sanggar->deleted_at)
                        <span class="badge badge-hapus">Terhapus</span>
                    @else
                        <span class="badge badge-{{ strtolower($sanggar->status) }}">
                            {{ $sanggar->status }}
                        </span>
                    @endif
                </td>

                {{-- Aksi --}}
                <td>
                    <div class="aksi-group">
                        @if ($sanggar->deleted_at)
                            {{-- Tombol restore --}}
                            <form method="POST"
                                  action="{{ route('admin.sanggars.restore', $sanggar->id) }}"
                                  style="display:inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success"
                                        title="Pulihkan sanggar">
                                    <i class="fas fa-undo"></i>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('admin.sanggars.edit', $sanggar) }}"
                               class="btn btn-sm btn-info" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form method="POST"
                                  action="{{ route('admin.sanggars.destroy', $sanggar) }}"
                                  style="display:inline"
                                  onsubmit="return confirm('Hapus sanggar \'{{ addslashes($sanggar->nama_sanggar) }}\'?')">
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
                    <i class="fas fa-search" style="font-size:2rem;margin-bottom:8px;display:block"></i>
                    Tidak ada sanggar yang sesuai filter.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="pagination-wrap">
        <div class="pagination-info">
            Menampilkan {{ $sanggars->firstItem() }}–{{ $sanggars->lastItem() }}
            dari {{ $sanggars->total() }} sanggar
        </div>
        {{ $sanggars->links() }}
    </div>
</div>

@endsection
