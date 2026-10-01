<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimoni;
use Illuminate\Http\Request;

class TestimoniAdminController extends Controller
{
    public function index()
    {
        $menunggu  = Testimoni::menunggu()->latest()->get();
        $disetujui = Testimoni::disetujui()->latest()->get();
        return view('admin.testimoni.index', compact('menunggu', 'disetujui'));
    }

    public function updateStatus(Testimoni $testimoni, Request $request)
    {
        $request->validate(['status' => 'required|in:disetujui,ditolak']);
        $testimoni->update(['status' => $request->status]);
        $label = $request->status === 'disetujui' ? 'disetujui' : 'ditolak';
        return back()->with('sukses', "Testimoni berhasil {$label}.");
    }

    public function destroy(Testimoni $testimoni)
    {
        $testimoni->delete();
        return back()->with('sukses', 'Testimoni berhasil dihapus.');
    }

    /**
     * Jumlah testimoni yang menunggu — untuk notifikasi real-time.
     */
    public function notifCount()
    {
        return response()->json([
            'testimoni' => Testimoni::menunggu()->count(),
        ]);
    }
}
