@extends(auth()->user()->role === 'master' ? 'layouts.master' : 'layouts.admin')

@section('title', 'Dokumentasi Seni | Master Admin')
@section('breadcrumb', 'Master Admin / Dokumentasi Seni')

@push('styles')
<style>
    .page-actions { display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; flex-wrap:wrap; gap:12px; }
    .btn { display:inline-flex; align-items:center; gap:6px; padding:9px 18px; border-radius:8px; font-size:0.82rem; font-weight:600; cursor:pointer; border:none; text-decoration:none; transition:all 0.2s; }
    .btn-primary { background:#5c6bc0; color:#fff; }
    .btn-primary:hover { background:#3949ab; }
    .btn-sm { padding:5px 12px; font-size:0.75rem; border-radius:6px; }
    .btn-info    { background:#e3f2fd; color:#1565c0; }
    .btn-danger  { background:#fce4ec; color:#c62828; }
    .filter-bar { background:#fff; border-radius:10px; padding:16px; margin-bottom:20px; display:flex; gap:10px; flex-wrap:wrap; box-shadow:0 1px 4px rgba(0,0,0,0.06); }
    .filter-bar input, .filter-bar select { padding:8px 12px; border:1px solid #e0e0e0; border-radius:6px; font-size:0.82rem; font-family:inherit; }
    .filter-bar input { flex:1; min-width:180px; }
    .card { background:#fff; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,0.06); overflow:hidden; }
    table { width:100%; border-collapse:collapse; font-size:0.82rem; }
    thead th { background:#f5f5f5; padding:12px 16px; text-align:left; font-weight:600; color:#555; border-bottom:2px solid #e0e0e0; white-space:nowrap; }
    tbody td { padding:12px 16px; border-bottom:1px solid #f5f5f5; vertical-align:middle; }
    tbody tr:hover { background:#fafafa; }
    tbody tr:last-child td { border-bottom:none; }
    .badge { display:inline-block; padding:3px 10px; border-radius:99px; font-size:0.70rem; font-weight:700; }
    .badge-foto    { background:#e3f2fd; color:#1565c0; }
    .badge-video   { background:#f3e5f5; color:#6a1b9a; }
    .badge-naskah  { background:#fff8e1; color:#e65100; }
    .badge-audio   { background:#e8f5e9; color:#0D9373; }
    .badge-aktif   { background:#e8f5e9; color:#0D9373; }
    .badge-arsip   { background:#f5f5f5; color:#757575; }
</style>
@endpush

@section('content')
<div class="page-actions">
    <div>
        <h1 class="text-headline-lg font-headline-lg font-bold text-primary">Dokumentasi Seni</h1>
        <p class="text-on-surface-variant text-sm mt-1">Total: {{ $dokumentasis->total() }} dokumentasi terdaftar</p>
    </div>
    <div style="display:flex; gap:10px;">
        <button type="button" class="btn btn-danger" id="btnBulkDelete" style="display:none;" onclick="submitBulkDelete()">
            <span class="material-symbols-outlined text-[18px]">delete</span>
            Hapus Terpilih (<span id="selectedCount">0</span>)
        </button>
        <a href="{{ route('master.dokumentasi.create') }}" class="btn btn-primary">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Tambah Dokumentasi
        </a>
    </div>
</div>

@if(session('sukses'))
<div style="background:#e8f5e9;border:1px solid #a5d6a7;color:#0D9373;padding:12px 16px;border-radius:8px;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
    <span class="material-symbols-outlined text-[18px]">check_circle</span>
    {{ session('sukses') }}
</div>
@endif

{{-- Filter Bar --}}
<form method="GET" class="filter-bar">
    <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari judul dokumentasi...">
    <select name="tipe">
        <option value="">Semua Tipe</option>
        @foreach(['Foto','Video','Naskah','Audio'] as $t)
            <option value="{{ $t }}" {{ request('tipe') === $t ? 'selected' : '' }}>{{ $t }}</option>
        @endforeach
    </select>
    <select name="status">
        <option value="">Semua Status</option>
        <option value="Aktif" {{ request('status') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
        <option value="Arsip" {{ request('status') === 'Arsip' ? 'selected' : '' }}>Arsip</option>
    </select>
    <button type="submit" class="btn btn-primary" style="padding:8px 16px;">
        <span class="material-symbols-outlined text-[16px]">filter_alt</span> Filter
    </button>
    <a href="{{ route('master.dokumentasi.index') }}" class="btn" style="background:#f5f5f5;color:#555;">✕ Reset</a>
</form>

{{-- Table --}}
<form id="bulkDeleteForm" action="{{ route('master.dokumentasi.bulkDestroy') }}" method="POST">
    @csrf
    @method('DELETE')
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;"><input type="checkbox" id="selectAll"></th>
                    <th>Thumbnail</th>
                    <th>Judul</th>
                    <th>Tipe</th>
                    <th>Tanggal Dokumen</th>
                    <th>Status</th>
                    <th style="text-align:right">Aksi</th>
                </tr>
            </thead>
        <tbody>
            @forelse($dokumentasis as $dok)
            <tr>
                <td style="text-align: center;">
                    <input type="checkbox" name="ids[]" value="{{ $dok->id }}" class="rowCheckbox">
                </td>
                <td style="width:80px">
                    @if($dok->thumbnail_url)
                        <img src="{{ $dok->thumbnail_url }}" style="width:64px;height:48px;border-radius:6px;object-fit:cover;">
                    @else
                        <div style="width:64px;height:48px;border-radius:6px;background:#f5f5f5;display:flex;align-items:center;justify-content:center;">
                            <span class="material-symbols-outlined" style="color:#aaa;font-size:22px;">{{ $dok->icon }}</span>
                        </div>
                    @endif
                </td>
                <td>
                    <div style="font-weight:600;color:#1a1a1a;">{{ $dok->judul }}</div>
                    @if($dok->deskripsi)
                        <div style="color:#888;font-size:0.75rem;margin-top:2px;max-width:280px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            {{ $dok->deskripsi }}
                        </div>
                    @endif
                </td>
                <td>
                    @php
                        $tipeCls = ['Foto'=>'badge-foto','Video'=>'badge-video','Naskah'=>'badge-naskah','Audio'=>'badge-audio'];
                    @endphp
                    <span class="badge {{ $tipeCls[$dok->tipe] ?? '' }}">{{ $dok->tipe }}</span>
                </td>
                <td style="color:#555;white-space:nowrap;">{{ $dok->tanggal_dokumen->format('d M Y') }}</td>
                <td>
                    <span class="badge {{ $dok->status === 'Aktif' ? 'badge-aktif' : 'badge-arsip' }}">
                        {{ $dok->status }}
                    </span>
                </td>
                <td style="text-align:right;white-space:nowrap;">
                    <a href="{{ route('master.dokumentasi.edit', $dok) }}" class="btn btn-sm btn-info">
                        <span class="material-symbols-outlined text-[14px]">edit</span> Edit
                    </a>
                    <form action="{{ route('master.dokumentasi.destroy', $dok) }}" method="POST"
                          style="display:inline;"
                          onsubmit="return confirm('Hapus dokumentasi ini? File akan ikut terhapus.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <span class="material-symbols-outlined text-[14px]">delete</span> Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;padding:40px;color:#aaa;">
                    <span class="material-symbols-outlined" style="font-size:40px;display:block;margin-bottom:8px;">photo_library</span>
                    Belum ada dokumentasi.
                    <a href="{{ route('master.dokumentasi.create') }}" style="color:#5c6bc0;">Tambah sekarang</a>.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($dokumentasis->hasPages())
    <div style="padding:14px 16px;border-top:1px solid #f0f0f0;">
        {{ $dokumentasis->links() }}
    </div>
    @endif
</div>
</form>

<p style="font-size:0.8rem;color:#999;margin-top:10px;">
    @if($dokumentasis->total() > 0)
        Menampilkan {{ $dokumentasis->firstItem() }}–{{ $dokumentasis->lastItem() }} dari {{ $dokumentasis->total() }} dokumentasi
    @endif
</p>
@endsection

@push('scripts')
<script>
    const selectAll = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('.rowCheckbox');
    const btnBulkDelete = document.getElementById('btnBulkDelete');
    const selectedCount = document.getElementById('selectedCount');
    
    function updateBulkDeleteButton() {
        const checkedCount = document.querySelectorAll('.rowCheckbox:checked').length;
        if(checkedCount > 0) {
            btnBulkDelete.style.display = 'inline-flex';
            selectedCount.innerText = checkedCount;
        } else {
            btnBulkDelete.style.display = 'none';
        }
        if(selectAll) {
            selectAll.checked = checkedCount === rowCheckboxes.length && rowCheckboxes.length > 0;
        }
    }

    if(selectAll) {
        selectAll.addEventListener('change', function() {
            rowCheckboxes.forEach(cb => cb.checked = this.checked);
            updateBulkDeleteButton();
        });
    }

    rowCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkDeleteButton);
    });

    function submitBulkDelete() {
        if(confirm('Apakah Anda yakin ingin menghapus semua dokumentasi yang dipilih secara permanen?')) {
            document.getElementById('bulkDeleteForm').submit();
        }
    }
</script>
@endpush
