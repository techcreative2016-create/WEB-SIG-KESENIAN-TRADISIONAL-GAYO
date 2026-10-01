<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class BeritaController extends Controller
{
    public function index()
    {
        $beritas = Berita::where('admin_id', Auth::id())->latest()->paginate(10);
        return view('admin.berita.index', compact('beritas'));
    }

    public function create()
    {
        return view('admin.berita.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'konten' => 'required|string',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $data = $validated;
        $data['admin_id'] = Auth::id();
        $data['status'] = 'pending'; // Berita baru menunggu persetujuan Master Admin

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('berita', 'public');
            
            \App\Models\Dokumentasi::create([
                'judul' => 'Thumbnail Berita: ' . $data['judul'],
                'tipe' => 'Foto',
                'deskripsi' => 'Thumbnail berita otomatis terunggah.',
                'tanggal_dokumen' => now(),
                'file_path' => $data['thumbnail'],
                'status' => 'Aktif',
            ]);
        }

        Berita::create($data);

        return redirect()->route('admin.berita.index')
            ->with('sukses', 'Berita berhasil dibuat dan menunggu persetujuan Master Admin.');
    }

    public function edit(Berita $beritum) // Model binding usually uses singular
    {
        // Ensure admin can only edit their own news
        if ($beritum->admin_id !== Auth::id()) {
            abort(403);
        }
        return view('admin.berita.edit', compact('beritum'));
    }

    public function update(Request $request, Berita $beritum)
    {
        if ($beritum->admin_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'konten' => 'required|string',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $data = $validated;
        
        // If edited, reset status to pending for re-approval
        $data['status'] = 'pending';

        if ($request->hasFile('thumbnail')) {
            if ($beritum->thumbnail) {
                Storage::disk('public')->delete($beritum->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('berita', 'public');
            
            \App\Models\Dokumentasi::create([
                'judul' => 'Pembaruan Thumbnail Berita: ' . $data['judul'],
                'tipe' => 'Foto',
                'deskripsi' => 'Pembaruan thumbnail berita otomatis terunggah.',
                'tanggal_dokumen' => now(),
                'file_path' => $data['thumbnail'],
                'status' => 'Aktif',
            ]);
        }

        $beritum->update($data);

        return redirect()->route('admin.berita.index')
            ->with('sukses', 'Berita berhasil diperbarui dan menunggu persetujuan Master Admin.');
    }

    public function destroy(Berita $beritum)
    {
        if ($beritum->admin_id !== Auth::id()) {
            abort(403);
        }

        if ($beritum->thumbnail) {
            Storage::disk('public')->delete($beritum->thumbnail);
        }

        $beritum->delete();

        return redirect()->route('admin.berita.index')
            ->with('sukses', 'Berita berhasil dihapus.');
    }
}
