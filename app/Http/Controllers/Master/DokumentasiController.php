<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * DokumentasiController — Manajemen Arsip Dokumentasi Seni (Master Admin only).
 *
 * Fitur:
 * - Index: daftar semua dokumentasi dengan filter tipe + pencarian
 * - Create / Store: tambah dokumentasi baru + upload file/thumbnail
 * - Edit / Update: edit data + ganti file
 * - Destroy: hapus dokumentasi + file fisik
 */
class DokumentasiController extends Controller
{
    public function index(Request $request): View
    {
        $dokumentasis = Dokumentasi::latest()->paginate(10);
        return view('master.dokumentasi.index', compact('dokumentasis'));
    }

    public function create(): View
    {
        return view('master.dokumentasi.create');
    }

    // =========================================================================
    // STORE
    // =========================================================================

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul'           => ['required', 'string', 'max:255'],
            'tipe'            => ['required', 'in:Foto,Video,Naskah,Audio'],
            'deskripsi'       => ['nullable', 'string'],
            'tanggal_dokumen' => ['required', 'date'],
            'file_path'       => ['nullable', 'file', 'max:51200'], // maks 50MB
            'thumbnail'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'url_eksternal'   => ['nullable', 'url', 'max:500'],
            'status'          => ['required', 'in:Aktif,Arsip'],
        ]);

        // Upload file utama
        if ($request->hasFile('file_path')) {
            $validated['file_path'] = $request->file('file_path')
                ->store('dokumentasi/files', 'public');
        }

        // Upload thumbnail
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')
                ->store('dokumentasi/thumbnails', 'public');
        }

        $dok = Dokumentasi::create($validated);

        return redirect()
            ->route('master.dokumentasi.index')
            ->with('sukses', "Dokumentasi \"{$dok->judul}\" berhasil ditambahkan.");
    }

    // =========================================================================
    // SHOW (redirect ke edit)
    // =========================================================================

    public function show(Dokumentasi $dokumentasi): RedirectResponse
    {
        return redirect()->route('master.dokumentasi.edit', $dokumentasi);
    }

    // =========================================================================
    // EDIT
    // =========================================================================

    public function edit(Dokumentasi $dokumentasi): View
    {
        return view('master.dokumentasi.edit', compact('dokumentasi'));
    }

    // =========================================================================
    // UPDATE
    // =========================================================================

    public function update(Request $request, Dokumentasi $dokumentasi): RedirectResponse
    {
        $validated = $request->validate([
            'judul'           => ['required', 'string', 'max:255'],
            'tipe'            => ['required', 'in:Foto,Video,Naskah,Audio'],
            'deskripsi'       => ['nullable', 'string'],
            'tanggal_dokumen' => ['required', 'date'],
            'file_path'       => ['nullable', 'file', 'max:51200'],
            'thumbnail'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'url_eksternal'   => ['nullable', 'url', 'max:500'],
            'status'          => ['required', 'in:Aktif,Arsip'],
        ]);

        // Ganti file utama jika ada upload baru
        if ($request->hasFile('file_path')) {
            if ($dokumentasi->file_path) {
                Storage::disk('public')->delete($dokumentasi->file_path);
            }
            $validated['file_path'] = $request->file('file_path')
                ->store('dokumentasi/files', 'public');
        } else {
            unset($validated['file_path']); // Jangan overwrite dengan null
        }

        // Ganti thumbnail jika ada upload baru
        if ($request->hasFile('thumbnail')) {
            if ($dokumentasi->thumbnail) {
                Storage::disk('public')->delete($dokumentasi->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')
                ->store('dokumentasi/thumbnails', 'public');
        } else {
            unset($validated['thumbnail']);
        }

        $dokumentasi->update($validated);

        return redirect()
            ->route('master.dokumentasi.edit', $dokumentasi)
            ->with('sukses', "Dokumentasi \"{$dokumentasi->judul}\" berhasil diperbarui.");
    }

    // =========================================================================
    // DESTROY
    // =========================================================================

    public function destroy(Dokumentasi $dokumentasi): RedirectResponse
    {
        // Hapus file fisik dari storage
        if ($dokumentasi->file_path) {
            Storage::disk('public')->delete($dokumentasi->file_path);
        }
        if ($dokumentasi->thumbnail) {
            Storage::disk('public')->delete($dokumentasi->thumbnail);
        }

        $judul = $dokumentasi->judul;
        $dokumentasi->delete();

        return redirect()
            ->route('master.dokumentasi.index')
            ->with('sukses', "Dokumentasi \"{$judul}\" berhasil dihapus.");
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer|exists:dokumentasis,id',
        ]);

        $dokuments = Dokumentasi::whereIn('id', $request->ids)->get();
        $count = $dokuments->count();

        foreach ($dokuments as $dok) {
            if ($dok->file_path) {
                Storage::disk('public')->delete($dok->file_path);
            }
            if ($dok->thumbnail) {
                Storage::disk('public')->delete($dok->thumbnail);
            }
            $dok->delete();
        }

        return redirect()->route('master.dokumentasi.index')->with('sukses', "Berhasil menghapus {$count} dokumentasi terpilih.");
    }
}
