<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\JadwalRequest;
use App\Models\JadwalPenampilan;
use App\Models\Sanggar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * JadwalController — CRUD Jadwal Penampilan Seni untuk panel Admin.
 *
 * Fitur:
 * - Index: daftar jadwal dengan filter tanggal, status, sanggar
 * - Create / Store: tambah jadwal baru + upload poster
 * - Edit / Update: edit jadwal + ganti poster
 * - Destroy: hapus jadwal permanen
 * - updateStatus: ubah status cepat (AJAX-friendly)
 */
class JadwalController extends Controller
{
    // =========================================================================
    // INDEX
    // =========================================================================

    public function index(Request $request): View
    {
        $query = JadwalPenampilan::where('user_id', auth()->id());

        // Filter pencarian nama acara
        if ($request->filled('cari')) {
            $query->where('nama_acara', 'like', '%' . $request->cari . '%');
        }

        // Filter sanggar
        if ($request->filled('nama_sanggar')) {
            $query->where('nama_sanggar', 'like', '%' . $request->nama_sanggar . '%');
        }

        // Filter jenis kesenian
        if ($request->filled('jenis_kesenian')) {
            $query->where('jenis_kesenian', $request->jenis_kesenian);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter rentang tanggal
        if ($request->filled('dari')) {
            $query->whereDate('tanggal', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal', '<=', $request->sampai);
        }

        $jadwals  = $query->orderByDesc('tanggal')->orderBy('waktu_mulai')->paginate(15)->withQueryString();

        return view('admin.jadwal.index', compact('jadwals'));
    }

    // =========================================================================
    // CREATE
    // =========================================================================

    public function create(): View
    {
        return view('admin.jadwal.create');
    }

    // =========================================================================
    // STORE
    // =========================================================================

    public function store(JadwalRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        if ($request->hasFile('poster')) {
            $data['poster'] = $request->file('poster')
                ->store('jadwal/poster', 'public');
                
            \App\Models\Dokumentasi::create([
                'judul' => 'Poster Jadwal: ' . $data['nama_acara'],
                'tipe' => 'Foto',
                'deskripsi' => 'Poster acara otomatis terunggah.',
                'tanggal_dokumen' => now(),
                'file_path' => $data['poster'],
                'status' => 'Aktif',
            ]);
        }

        $jadwal = JadwalPenampilan::create($data);

        return redirect()
            ->route('admin.jadwal.index')
            ->with('sukses', "Jadwal \"{$jadwal->nama_acara}\" berhasil ditambahkan.");
    }

    // =========================================================================
    // SHOW
    // =========================================================================

    public function show(JadwalPenampilan $jadwal): RedirectResponse
    {
        abort_if($jadwal->user_id !== auth()->id(), 403, 'Akses ditolak.');
        return redirect()->route('admin.jadwal.edit', $jadwal);
    }

    // =========================================================================
    // EDIT
    // =========================================================================

    public function edit(JadwalPenampilan $jadwal): View
    {
        abort_if($jadwal->user_id !== auth()->id(), 403, 'Akses ditolak.');

        return view('admin.jadwal.edit', compact('jadwal'));
    }

    // =========================================================================
    // UPDATE
    // =========================================================================

    public function update(JadwalRequest $request, JadwalPenampilan $jadwal): RedirectResponse
    {
        abort_if($jadwal->user_id !== auth()->id(), 403, 'Akses ditolak.');
        $data = $request->validated();

        if ($request->hasFile('poster')) {
            // Hapus poster lama
            if ($jadwal->poster) {
                Storage::disk('public')->delete($jadwal->poster);
            }
            $data['poster'] = $request->file('poster')
                ->store('jadwal/poster', 'public');
                
            \App\Models\Dokumentasi::create([
                'judul' => 'Pembaruan Poster Jadwal: ' . $data['nama_acara'],
                'tipe' => 'Foto',
                'deskripsi' => 'Pembaruan poster acara otomatis terunggah.',
                'tanggal_dokumen' => now(),
                'file_path' => $data['poster'],
                'status' => 'Aktif',
            ]);
        }

        $jadwal->update($data);

        return redirect()
            ->route('admin.jadwal.edit', $jadwal)
            ->with('sukses', "Jadwal \"{$jadwal->nama_acara}\" berhasil diperbarui.");
    }

    // =========================================================================
    // DESTROY (Permanen)
    // =========================================================================

    public function destroy(JadwalPenampilan $jadwal): RedirectResponse
    {
        abort_if($jadwal->user_id !== auth()->id(), 403, 'Akses ditolak.');
        // Hapus poster dari storage
        if ($jadwal->poster) {
            Storage::disk('public')->delete($jadwal->poster);
        }

        $jadwal->delete();

        return redirect()
            ->route('admin.jadwal.index')
            ->with('sukses', "Jadwal \"{$jadwal->nama_acara}\" berhasil dihapus.");
    }

    // =========================================================================
    // UPDATE STATUS CEPAT (tombol di index)
    // PATCH /admin/jadwal/{jadwal}/status
    // =========================================================================

    public function updateStatus(Request $request, JadwalPenampilan $jadwal): RedirectResponse
    {
        abort_if($jadwal->user_id !== auth()->id(), 403, 'Akses ditolak.');
        $request->validate([
            'status' => ['required', 'in:Terjadwal,Berlangsung,Selesai,Dibatalkan'],
        ]);

        $jadwal->update(['status' => $request->status]);

        return back()->with('sukses', "Status jadwal diubah ke \"{$request->status}\".");
    }
}
