<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SanggarRequest;
use App\Models\Galeri;
use App\Models\Sanggar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * SanggarController — CRUD Sanggar Seni untuk panel Admin.
 *
 * Fitur:
 * - Index: daftar semua sanggar dengan filter + pagination
 * - Create / Store: tambah sanggar baru + upload foto utama
 * - Edit / Update: edit data + ganti foto utama
 * - Destroy: soft delete sanggar
 * - uploadGaleri: upload banyak foto ke galeri
 * - hapusGaleri: hapus satu foto dari galeri
 */
class SanggarController extends Controller
{
    // =========================================================================
    // INDEX — Daftar semua sanggar
    // =========================================================================

    public function index(Request $request): View
    {
        $query = Sanggar::withTrashed();
        if (auth()->user()->role !== 'master') {
            $query->where('user_id', auth()->id()); // Tampilkan hanya milik user ini jika bukan master
        }

        // Filter pencarian nama
        if ($request->filled('cari')) {
            $query->where('nama_sanggar', 'like', '%' . $request->cari . '%');
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter kabupaten
        if ($request->filled('kabupaten')) {
            $query->where('kabupaten', $request->kabupaten);
        }

        // Filter status (aktif/vakum/terhapus)
        if ($request->filled('status')) {
            if ($request->status === 'terhapus') {
                $query->onlyTrashed();
            } else {
                $query->where('status', $request->status)->whereNull('deleted_at');
            }
        } else {
            $query->whereNull('deleted_at'); // Default: tidak tampilkan yang terhapus
        }

        $sanggars = $query->latest()->paginate(15)->withQueryString();

        return view('admin.sanggars.index', compact('sanggars'));
    }

    // =========================================================================
    // CREATE — Form tambah sanggar baru
    // =========================================================================

    public function create(): View
    {
        return view('admin.sanggars.create');
    }

    // =========================================================================
    // STORE — Simpan sanggar baru ke database
    // =========================================================================

    public function store(SanggarRequest $request): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();
            $data['user_id'] = auth()->id();

            // Upload foto utama jika ada
            if ($request->hasFile('foto_utama')) {
                $data['foto_utama'] = $request->file('foto_utama')
                    ->store('sanggars/foto-utama', 'public');
                    
                \App\Models\Dokumentasi::create([
                    'judul' => 'Foto Utama Sanggar ' . $data['nama_sanggar'],
                    'tipe' => 'Foto',
                    'deskripsi' => 'Foto utama sanggar otomatis terunggah.',
                    'tanggal_dokumen' => now(),
                    'file_path' => $data['foto_utama'],
                    'status' => 'Aktif',
                ]);
            }

            $sanggar = Sanggar::create($data);

            // Upload galeri tambahan (multiple files)
            if ($request->hasFile('galeri')) {
                $this->simpanGaleri($sanggar, $request->file('galeri'));
            }

            DB::commit();

            return redirect()
                ->route('admin.sanggars.index')
                ->with('sukses', "Sanggar \"{$sanggar->nama_sanggar}\" berhasil ditambahkan.");

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan sanggar: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // SHOW — Detail sanggar (redirect ke edit)
    // =========================================================================

    public function show(Sanggar $sanggar): RedirectResponse
    {
        abort_if($sanggar->user_id !== auth()->id() && auth()->user()->role !== 'master', 403, 'Akses ditolak.');
        return redirect()->route('admin.sanggars.edit', $sanggar);
    }

    // =========================================================================
    // EDIT — Form edit sanggar
    // =========================================================================

    public function edit(Sanggar $sanggar): View
    {
        abort_if($sanggar->user_id !== auth()->id() && auth()->user()->role !== 'master', 403, 'Akses ditolak.');
        // Eager load galeri yang dimiliki sanggar ini
        $sanggar->load(['galeri' => fn($q) => $q->orderBy('urutan')]);

        return view('admin.sanggars.edit', compact('sanggar'));
    }

    // =========================================================================
    // UPDATE — Simpan perubahan data sanggar
    // =========================================================================

    public function update(SanggarRequest $request, Sanggar $sanggar): RedirectResponse
    {
        abort_if($sanggar->user_id !== auth()->id() && auth()->user()->role !== 'master', 403, 'Akses ditolak.');
        DB::beginTransaction();

        try {
            $data = $request->validated();

            // Ganti foto utama jika ada file baru diupload
            if ($request->hasFile('foto_utama')) {
                // Hapus foto lama dari storage
                if ($sanggar->foto_utama) {
                    Storage::disk('public')->delete($sanggar->foto_utama);
                }

                $data['foto_utama'] = $request->file('foto_utama')
                    ->store('sanggars/foto-utama', 'public');

                \App\Models\Dokumentasi::create([
                    'judul' => 'Pembaruan Foto Utama Sanggar ' . $data['nama_sanggar'],
                    'tipe' => 'Foto',
                    'deskripsi' => 'Pembaruan foto utama sanggar otomatis terunggah.',
                    'tanggal_dokumen' => now(),
                    'file_path' => $data['foto_utama'],
                    'status' => 'Aktif',
                ]);
            }

            $sanggar->update($data);

            // Upload galeri tambahan baru (jika ada)
            if ($request->hasFile('galeri')) {
                $this->simpanGaleri($sanggar, $request->file('galeri'));
            }

            DB::commit();

            return redirect()
                ->route('admin.sanggars.edit', $sanggar)
                ->with('sukses', "Data sanggar \"{$sanggar->nama_sanggar}\" berhasil diperbarui.");

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui sanggar: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // DESTROY — Soft delete sanggar
    // =========================================================================

    public function destroy(Sanggar $sanggar): RedirectResponse
    {
        abort_if($sanggar->user_id !== auth()->id() && auth()->user()->role !== 'master', 403, 'Akses ditolak.');
        $sanggar->delete(); // Soft delete — data tidak hilang dari DB

        return redirect()
            ->route('admin.sanggars.index')
            ->with('sukses', "Sanggar \"{$sanggar->nama_sanggar}\" berhasil dihapus.");
    }

    // =========================================================================
    // RESTORE — Pulihkan sanggar yang di-soft delete
    // =========================================================================

    public function restore(int $id): RedirectResponse
    {
        $query = Sanggar::onlyTrashed();
        if (auth()->user()->role !== 'master') {
            $query->where('user_id', auth()->id());
        }
        $sanggar = $query->findOrFail($id);
        $sanggar->restore();

        return redirect()
            ->route('admin.sanggars.index')
            ->with('sukses', "Sanggar \"{$sanggar->nama_sanggar}\" berhasil dipulihkan.");
    }

    // =========================================================================
    // UPLOAD GALERI — Upload banyak foto ke galeri sanggar
    // POST /admin/sanggars/{sanggar}/galeri
    // =========================================================================

    public function uploadGaleri(Request $request, Sanggar $sanggar): RedirectResponse
    {
        abort_if($sanggar->user_id !== auth()->id() && auth()->user()->role !== 'master', 403, 'Akses ditolak.');
        $request->validate([
            'galeri'         => ['required', 'array', 'max:10'],
            'galeri.*'       => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], // maks 5MB per file
            'galeri_judul.*' => ['nullable', 'string', 'max:100'],
        ], [
            'galeri.required'   => 'Pilih minimal satu foto.',
            'galeri.*.image'    => 'File harus berupa gambar (JPG, PNG, WebP).',
            'galeri.*.max'      => 'Ukuran setiap foto maksimal 5MB.',
        ]);

        $judul = $request->input('galeri_judul', []);

        $this->simpanGaleri($sanggar, $request->file('galeri'), $judul);

        return back()->with('sukses', count($request->file('galeri')) . ' foto berhasil diupload ke galeri.');
    }

    // =========================================================================
    // HAPUS GALERI — Hapus satu foto dari galeri
    // DELETE /admin/sanggars/{sanggar}/galeri/{galeri}
    // =========================================================================

    public function hapusGaleri(Sanggar $sanggar, Galeri $galeri): RedirectResponse
    {
        abort_if($sanggar->user_id !== auth()->id() && auth()->user()->role !== 'master', 403, 'Akses ditolak.');
        // Pastikan galeri benar-benar milik sanggar ini
        abort_unless(
            $galeri->galeri_able_type === Sanggar::class
            && $galeri->galeri_able_id === $sanggar->id,
            403,
            'Foto ini bukan milik sanggar yang dipilih.'
        );

        // Hapus file fisik dari storage
        Storage::disk('public')->delete($galeri->path_file);

        $galeri->delete();

        return back()->with('sukses', 'Foto berhasil dihapus dari galeri.');
    }

    // =========================================================================
    // SET FOTO UTAMA GALERI
    // PATCH /admin/sanggars/{sanggar}/galeri/{galeri}/utama
    // =========================================================================

    public function setFotoUtama(Sanggar $sanggar, Galeri $galeri): RedirectResponse
    {
        abort_if($sanggar->user_id !== auth()->id() && auth()->user()->role !== 'master', 403, 'Akses ditolak.');
        abort_unless(
            $galeri->galeri_able_type === Sanggar::class
            && $galeri->galeri_able_id === $sanggar->id,
            403
        );

        // Reset semua foto utama milik sanggar ini
        Galeri::where('galeri_able_type', Sanggar::class)
              ->where('galeri_able_id', $sanggar->id)
              ->update(['is_utama' => false]);

        // Set foto yang dipilih sebagai utama
        $galeri->update(['is_utama' => true]);

        // Sync ke kolom foto_utama di tabel sanggars
        $sanggar->update(['foto_utama' => $galeri->path_file]);

        return back()->with('sukses', 'Foto utama berhasil diperbarui.');
    }

    // =========================================================================
    // PRIVATE HELPER — Simpan array file ke galeri
    // =========================================================================

    /**
     * Simpan koleksi file gambar ke tabel galeri (polymorphic).
     *
     * @param  Sanggar  $sanggar
     * @param  array    $files    Array UploadedFile
     * @param  array    $judul    Opsional: array judul/caption per foto
     */
    private function simpanGaleri(Sanggar $sanggar, array $files, array $judul = []): void
    {
        // Tentukan urutan mulai dari yang sudah ada
        $urutanMulai = $sanggar->galeri()->max('urutan') + 1;

        foreach ($files as $index => $file) {
            $path = $file->store('sanggars/galeri', 'public');
            
            $judulFoto = $judul[$index] ?? ('Galeri Sanggar ' . $sanggar->nama_sanggar . ' ' . ($urutanMulai + $index));

            $sanggar->galeri()->create([
                'path_file'  => $path,
                'judul'      => $judul[$index] ?? null,
                'tipe_media' => 'foto',
                'urutan'     => $urutanMulai + $index,
                'is_utama'   => false,
            ]);

            \App\Models\Dokumentasi::create([
                'judul' => $judulFoto,
                'tipe' => 'Foto',
                'deskripsi' => 'Foto galeri sanggar otomatis terunggah.',
                'tanggal_dokumen' => now(),
                'file_path' => $path,
                'status' => 'Aktif',
            ]);
        }
    }
}
