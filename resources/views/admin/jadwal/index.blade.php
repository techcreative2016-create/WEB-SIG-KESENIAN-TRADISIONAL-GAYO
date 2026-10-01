@extends(auth()->user()->role === 'master' ? 'layouts.master' : 'layouts.admin')
@section('title', 'Jadwal Penampilan')
@section('breadcrumb', 'Admin / Jadwal Penampilan')

@push('styles')
<style>
    .page-actions{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px}
    .btn{display:inline-flex;align-items:center;gap:6px;padding:9px 18px;border-radius:8px;font-size:.82rem;font-weight:600;cursor:pointer;border:none;text-decoration:none;transition:all .2s}
    .btn-primary{background:#4a148c;color:#fff}.btn-primary:hover{background:#311b92}
    .btn-sm{padding:5px 12px;font-size:.75rem;border-radius:6px}
    .btn-info{background:#e8eaf6;color:#3949ab}.btn-danger{background:#fce4ec;color:#c62828}
    .filter-bar{background:#fff;border-radius:10px;padding:16px;margin-bottom:20px;display:flex;gap:10px;flex-wrap:wrap;box-shadow:0 1px 4px rgba(0,0,0,.06)}
    .filter-bar input,.filter-bar select{padding:8px 12px;border:1px solid #e0e0e0;border-radius:6px;font-size:.82rem;font-family:inherit}
    .filter-bar input{flex:1;min-width:150px}
    .card{background:#fff;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,.06);overflow:hidden}
    table{width:100%;border-collapse:collapse;font-size:.82rem}
    thead th{background:#ede7f6;padding:12px 16px;text-align:left;font-weight:600;color:#4a148c;border-bottom:2px solid #ce93d8;white-space:nowrap}
    tbody td{padding:12px 16px;border-bottom:1px solid #f5f5f5;vertical-align:middle}
    tbody tr:hover{background:#fafafa}
    .badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:.68rem;font-weight:700}
    .badge-terjadwal{background:#e3f2fd;color:#1565c0}
    .badge-berlangsung{background:#e8f5e9;color:#0D9373}
    .badge-selesai{background:#eceff1;color:#607d8b}
    .badge-dibatalkan{background:#fce4ec;color:#c62828}
    .badge-hari-ini{background:#fff9c4;color:#f57f17;border:1px solid #f9a825}
    .aksi-group{display:flex;gap:4px;flex-wrap:wrap}
    .status-select{padding:4px 8px;border:1px solid #ddd;border-radius:6px;font-size:.73rem;font-family:inherit;cursor:pointer;background:#f9f9f9}
    .pagination-wrap{padding:16px;display:flex;justify-content:space-between;align-items:center}
    .pagination-info{font-size:.78rem;color:#777}
    .poster-thumb{width:42px;height:60px;border-radius:6px;object-fit:cover;background:#f0f0f0}
</style>
@endpush

@section('content')
<div class="page-actions">
    <div>
        <h2 style="font-size:1.2rem;font-weight:800">Jadwal Penampilan</h2>
        <p style="color:#777;font-size:.8rem;margin-top:2px">Total: {{ $jadwals->total() }} jadwal terdaftar</p>
    </div>
    <a href="{{ route('admin.jadwal.create') }}" class="btn btn-primary" id="btn-tambah-jadwal">
        <i class="fas fa-plus"></i> Tambah Jadwal
    </a>
</div>

{{-- Filter --}}
<form method="GET" action="{{ route('admin.jadwal.index') }}">
    <div class="filter-bar">
        <input type="text" name="cari" placeholder="🔍 Cari nama acara..." value="{{ request('cari') }}">
        <input type="text" name="nama_sanggar" placeholder="Cari sanggar..." value="{{ request('nama_sanggar') }}">
        <select name="jenis_kesenian">
            <option value="">Semua Jenis</option>
            @foreach(['Didong','Tari Guel','Kerawang Gayo','Teganing','Campuran'] as $j)
                <option value="{{ $j }}" {{ request('jenis_kesenian') === $j ? 'selected' : '' }}>{{ $j }}</option>
            @endforeach
        </select>
        <select name="status">
            <option value="">Semua Status</option>
            @foreach(['Terjadwal','Berlangsung','Selesai','Dibatalkan'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $s }}</option>
            @endforeach
        </select>
        <input type="date" name="dari"   value="{{ request('dari') }}"   title="Dari tanggal">
        <input type="date" name="sampai" value="{{ request('sampai') }}" title="Sampai tanggal">
        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
        <a href="{{ route('admin.jadwal.index') }}" class="btn" style="background:#f0f0f0;color:#555">
            <i class="fas fa-times"></i> Reset
        </a>
    </div>
</form>

{{-- Tabel --}}
<div class="card">
    <table>
        <thead>
            <tr>
                <th width="52">Poster</th>
                <th>Nama Acara</th>
                <th>Sanggar</th>
                <th>Jenis</th>
                <th>Tanggal & Waktu</th>
                <th>Lokasi</th>
                <th>Status</th>
                <th width="130">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($jadwals as $jadwal)
            <tr id="row-jadwal-{{ $jadwal->id }}">
                <td>
                    @if ($jadwal->poster)
                        <img class="poster-thumb" src="{{ asset('storage/' . $jadwal->poster) }}" alt="Poster">
                    @else
                        <div class="poster-thumb" style="display:flex;align-items:center;justify-content:center;color:#bbb">
                            <i class="fas fa-calendar"></i>
                        </div>
                    @endif
                </td>
                <td>
                    <div style="font-weight:600">{{ $jadwal->nama_acara }}</div>
                    @if ($jadwal->tanggal->isToday())
                        <span class="badge badge-hari-ini" style="margin-top:3px">
                            <i class="fas fa-broadcast-tower"></i> Hari Ini
                        </span>
                    @endif
                </td>
                <td style="font-size:.78rem">{{ $jadwal->nama_sanggar ?? '—' }}</td>
                <td><span class="badge" style="background:#f3e5f5;color:#6a1b9a">{{ $jadwal->jenis_kesenian }}</span></td>
                <td style="font-size:.78rem">
                    <div style="font-weight:600">{{ $jadwal->tanggal->format('d M Y') }}</div>
                    <div style="color:#777">{{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }}
                        @if($jadwal->waktu_selesai)
                            – {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}
                        @endif
                    </div>
                </td>
                <td style="font-size:.78rem;max-width:150px">
                    <div>{{ $jadwal->lokasi_acara }}</div>
                    <div style="color:#777">{{ Str::limit($jadwal->alamat_acara, 30) }}</div>
                </td>
                <td>
                    {{-- Ubah status cepat --}}
                    <form method="POST" action="{{ route('admin.jadwal.status', $jadwal) }}" id="form-status-{{ $jadwal->id }}">
                        @csrf @method('PATCH')
                        <select name="status" class="status-select"
                                onchange="document.getElementById('form-status-{{ $jadwal->id }}').submit()"
                                title="Ubah status">
                            @foreach(['Terjadwal','Berlangsung','Selesai','Dibatalkan'] as $s)
                                <option value="{{ $s }}" {{ $jadwal->status === $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </form>
                </td>
                <td>
                    <div class="aksi-group">
                        <a href="{{ route('admin.jadwal.edit', $jadwal) }}" class="btn btn-sm btn-info" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.jadwal.destroy', $jadwal) }}"
                              onsubmit="return confirm('Hapus jadwal \'{{ addslashes($jadwal->nama_acara) }}\'?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center;padding:40px;color:#aaa">
                    <i class="fas fa-calendar-times" style="font-size:2rem;margin-bottom:8px;display:block"></i>
                    Tidak ada jadwal yang sesuai filter.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination-wrap">
        <div class="pagination-info">
            Menampilkan {{ $jadwals->firstItem() }}–{{ $jadwals->lastItem() }} dari {{ $jadwals->total() }} jadwal
        </div>
        {{ $jadwals->links() }}
    </div>
</div>
@endsection
