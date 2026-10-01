<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TokoRequest;
use App\Models\Galeri;
use App\Models\Toko;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * TokoController — CRUD Toko Kesenian untuk panel Admin.
 *
 * Fitur:
 * - Index: daftar toko dengan filter + pagination
 * - Create / Store: tambah toko baru + upload foto utama
 * - Edit / Update: edit data + ganti foto utama
 * - Destroy: soft delete toko
 * - uploadGaleri / hapusGaleri / setFotoUtama: manajemen galeri
 */
class TokoController extends Controller
{
    // =========================================================================
    // INDEX
    // =========================================================================

    public function index(Request $request): View
    {
        $query = Toko::withTrashed()->where('user_id', auth()->id());

        if ($request->filled('cari')) {
            $query->where('nama_toko', 'like', '%' . $request->cari . '%');
        }

        if ($request->filled('jenis_produk')) {
            $query->where('jenis_produk', $request->jenis_produk);
        }

        if ($request->filled('kabupaten')) {
            $query->where('kabupaten', $request->kabupaten);
        }

        if ($request->filled('status')) {
            if ($request->status === 'terhapus') {
                $query->onlyTrashed();
            } else {
                $query->where('status', $request->status)->whereNull('deleted_at');
            }
        } else {
            $query->whereNull('deleted_at');
        }

        $tokos = $query->latest()->paginate(15)->withQueryString();

        return view('admin.tokos.index', compact('tokos'));
    }

    // =========================================================================
    // CREATE
    // =========================================================================

    public function create(): View
    {
        return view('admin.tokos.create');
    }

    // =========================================================================
    // STORE
    // =========================================================================

    public function store(TokoRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $data['user_id'] = auth()->id();

            if ($request->hasFile('foto_utama')) {
                $data['foto_utama'] = $request->file('foto_utama')
                    ->store('tokos/foto-utama', 'public');
                    
                \App\Models\Dokumentasi::create([
                    'judul' => 'Foto Utama Toko ' . $data['nama_toko'],
                    'tipe' => 'Foto',
                    'deskripsi' => 'Foto utama toko otomatis terunggah.',
                    'tanggal_dokumen' => now(),
                    'file_path' => $data['foto_utama'],
                    'status' => 'Aktif',
                ]);
            }

            $toko = Toko::create($data);

            if ($request->hasFile('galeri')) {
                $this->simpanGaleri($toko, $request->file('galeri'));
            }

            DB::commit();

            return redirect()
                ->route('admin.tokos.index')
                ->with('sukses', "Toko \"{$toko->nama_toko}\" berhasil ditambahkan.");

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan toko: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // SHOW
    // =========================================================================

    public function show(Toko $toko): RedirectResponse
    {
        abort_if($toko->user_id !== auth()->id(), 403, 'Akses ditolak.');
        return redirect()->route('admin.tokos.edit', $toko);
    }

    // =========================================================================
    // EDIT
    // =========================================================================

    public function edit(Toko $toko): View
    {
        abort_if($toko->user_id !== auth()->id(), 403, 'Akses ditolak.');
        $toko->load(['galeri' => fn($q) => $q->orderBy('urutan')]);
        return view('admin.tokos.edit', compact('toko'));
    }

    // =========================================================================
    // UPDATE
    // =========================================================================

    public function update(TokoRequest $request, Toko $toko): RedirectResponse
    {
        abort_if($toko->user_id !== auth()->id(), 403, 'Akses ditolak.');
        DB::beginTransaction();
        try {
            $data = $request->validated();

            if ($request->hasFile('foto_utama')) {
                if ($toko->foto_utama) {
                    Storage::disk('public')->delete($toko->foto_utama);
                }
                $data['foto_utama'] = $request->file('foto_utama')
                    ->store('tokos/foto-utama', 'public');

                \App\Models\Dokumentasi::create([
                    'judul' => 'Pembaruan Foto Utama Toko ' . $data['nama_toko'],
                    'tipe' => 'Foto',
                    'deskripsi' => 'Pembaruan foto utama toko otomatis terunggah.',
                    'tanggal_dokumen' => now(),
                    'file_path' => $data['foto_utama'],
                    'status' => 'Aktif',
                ]);
            }

            $toko->update($data);

            if ($request->hasFile('galeri')) {
                $this->simpanGaleri($toko, $request->file('galeri'));
            }

            DB::commit();

            return redirect()
                ->route('admin.tokos.edit', $toko)
                ->with('sukses', "Data toko \"{$toko->nama_toko}\" berhasil diperbarui.");

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui toko: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // DESTROY (Soft Delete)
    // =========================================================================

    public function destroy(Toko $toko): RedirectResponse
    {
        abort_if($toko->user_id !== auth()->id(), 403, 'Akses ditolak.');
        $toko->delete();

        return redirect()
            ->route('admin.tokos.index')
            ->with('sukses', "Toko \"{$toko->nama_toko}\" berhasil dihapus.");
    }

    // =========================================================================
    // RESTORE
    // =========================================================================

    public function restore(int $id): RedirectResponse
    {
        $toko = Toko::onlyTrashed()->where('user_id', auth()->id())->findOrFail($id);
        $toko->restore();

        return redirect()
            ->route('admin.tokos.index')
            ->with('sukses', "Toko \"{$toko->nama_toko}\" berhasil dipulihkan.");
    }

    // =========================================================================
    // UPLOAD GALERI
    // =========================================================================

    public function uploadGaleri(Request $request, Toko $toko): RedirectResponse
    {
        abort_if($toko->user_id !== auth()->id(), 403, 'Akses ditolak.');
        $request->validate([
            'galeri'   => ['required', 'array', 'max:10'],
            'galeri.*' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'galeri.required' => 'Pilih minimal satu foto.',
            'galeri.*.image'  => 'File harus berupa gambar (JPG, PNG, WebP).',
            'galeri.*.max'    => 'Ukuran setiap foto maksimal 5MB.',
        ]);

        $this->simpanGaleri($toko, $request->file('galeri'));

        return back()->with('sukses', count($request->file('galeri')) . ' foto berhasil diupload ke galeri.');
    }

    // =========================================================================
    // HAPUS GALERI
    // =========================================================================

    public function hapusGaleri(Toko $toko, Galeri $galeri): RedirectResponse
    {
        abort_if($toko->user_id !== auth()->id(), 403, 'Akses ditolak.');
        abort_unless(
            $galeri->galeri_able_type === Toko::class
            && $galeri->galeri_able_id === $toko->id,
            403, 'Foto ini bukan milik toko yang dipilih.'
        );

        Storage::disk('public')->delete($galeri->path_file);
        $galeri->delete();

        return back()->with('sukses', 'Foto berhasil dihapus dari galeri.');
    }

    // =========================================================================
    // SET FOTO UTAMA
    // =========================================================================

    public function setFotoUtama(Toko $toko, Galeri $galeri): RedirectResponse
    {
        abort_if($toko->user_id !== auth()->id(), 403, 'Akses ditolak.');
        abort_unless(
            $galeri->galeri_able_type === Toko::class
            && $galeri->galeri_able_id === $toko->id,
            403
        );

        Galeri::where('galeri_able_type', Toko::class)
              ->where('galeri_able_id', $toko->id)
              ->update(['is_utama' => false]);

        $galeri->update(['is_utama' => true]);
        $toko->update(['foto_utama' => $galeri->path_file]);

        return back()->with('sukses', 'Foto utama berhasil diperbarui.');
    }

    // =========================================================================
    // PRIVATE HELPER
    // =========================================================================

    private function simpanGaleri(Toko $toko, array $files): void
    {
        $urutanMulai = $toko->galeri()->max('urutan') + 1;

        foreach ($files as $index => $file) {
            $path = $file->store('tokos/galeri', 'public');
            
            $judulFoto = 'Galeri Toko ' . $toko->nama_toko . ' ' . ($urutanMulai + $index);

            $toko->galeri()->create([
                'path_file'  => $path,
                'tipe_media' => 'foto',
                'urutan'     => $urutanMulai + $index,
                'is_utama'   => false,
            ]);

            \App\Models\Dokumentasi::create([
                'judul' => $judulFoto,
                'tipe' => 'Foto',
                'deskripsi' => 'Foto galeri toko otomatis terunggah.',
                'tanggal_dokumen' => now(),
                'file_path' => $path,
                'status' => 'Aktif',
            ]);
        }
    }
}
