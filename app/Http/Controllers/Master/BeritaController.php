<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    public function index()
    {
        // Tampilkan semua berita, urutkan yang pending terlebih dahulu, lalu berdasarkan tanggal
        $beritas = Berita::with('penulis')
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->latest()
            ->paginate(15);
            
        return view('master.berita.index', compact('beritas'));
    }

    public function updateStatus(Request $request, Berita $beritum)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,pending'
        ]);

        $beritum->update(['status' => $request->status]);

        $pesan = $request->status == 'approved' ? 'Berita berhasil disetujui dan akan tayang.' : 'Berita ditolak.';
        
        return back()->with('sukses', $pesan);
    }

    public function destroy(Berita $beritum)
    {
        if ($beritum->thumbnail) {
            Storage::disk('public')->delete($beritum->thumbnail);
        }

        $beritum->delete();

        return back()->with('sukses', 'Berita berhasil dihapus secara permanen.');
    }
}
