<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Testimoni;
use App\Models\Chat;
use Illuminate\Http\Request;

class TestimoniMasterController extends Controller
{
    public function index()
    {
        $menunggu  = Testimoni::menunggu()->latest()->get();
        $disetujui = Testimoni::disetujui()->latest()->get();
        $ditolak   = Testimoni::where('status', 'ditolak')->latest()->get();

        return view('master.testimoni.index', compact('menunggu', 'disetujui', 'ditolak'));
    }

    public function updateStatus(Testimoni $testimoni, Request $request)
    {
        $request->validate(['status' => 'required|in:disetujui,ditolak']);
        $testimoni->update(['status' => $request->status]);
        $label = $request->status === 'disetujui' ? 'disetujui' : 'ditolak';
        return back()->with('sukses', "Ulasan berhasil {$label}.");
    }

    public function destroy(Testimoni $testimoni)
    {
        $testimoni->delete();
        return back()->with('sukses', 'Ulasan berhasil dihapus.');
    }

    /**
     * Jumlah saran & ulasan yang menunggu — untuk notifikasi real-time Master Admin.
     */
    public function notifCount()
    {
        return response()->json([
            'testimoni' => Testimoni::menunggu()->count(),
            'chat' => Chat::where('is_admin', false)->where('is_read', false)->count(),
        ]);
    }
}
